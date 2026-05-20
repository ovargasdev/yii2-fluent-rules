<?php

/**
 * Script para automatizar la internacionalización de los bloques PHPDoc y comentarios
 * utilizando la API local de Ollama.
 */

// Configuración básica
$ollamaUrl = 'http://localhost:11434/api/generate';
//$model     = 'qwen2.5-coder:7b'; // Cambia por el modelo que tengas descargado (ej. mistral, qwen2.5, etc.)
$model     = 'gpt-oss:20b-cloud'; // Cambia por el modelo que tengas descargado (ej. mistral, qwen2.5, etc.)
$srcDir    = __DIR__ . '/src';

if (!is_dir($srcDir)) {
    die("Error: No se encontró la carpeta 'src' en: $srcDir\n");
}

// Escanear recursivamente la carpeta src en busca de archivos PHP
$directory = new RecursiveDirectoryIterator($srcDir);
$iterator  = new RecursiveIteratorIterator($directory);
$regex     = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

echo "Iniciando traducción automática de documentación con Ollama ($model)...\n";
echo "----------------------------------------------------------------------\n";

foreach ($regex as $filePath => $object) {
    // Evitamos procesar las clases base que ya traducimos manualmente para ahorrar tiempo
    $filename = basename($filePath);
    if ($filename === 'RuleBuilder.php' || $filename === 'Attribute.php') {
        echo "[OMITIDO] $filename (Ya está en inglés)\n";
        continue;
    }

    echo "[PROCESANDO] $filename... ";
    $originalCode = file_get_contents($filePath);

    // Construimos un prompt estricto para que el LLM no invente código ni destruya la sintaxis PHP
    $prompt = "You are an expert PHP developer. Your task is to translate ALL Spanish comments, inline explanations, and PHPDoc blocks in the following PHP code into professional technical English. 
CRITICAL RULES:
1. Do NOT modify any PHP logic, variable names, method names, namespaces, classes, or code structures.
2. Only translate the text inside comments (//, /*, /**).
3. Return ONLY the raw translated PHP code. Do NOT wrap the output in markdown code blocks (```php), do not add introductory or concluding text.

Here is the code to translate:
\n\n" . $originalCode;

    // Preparar la petición a la API de Ollama
    $data = [
        'model'  => $model,
        'prompt' => $prompt,
        'stream' => false, // Solicitamos la respuesta completa de un solo golpe
        'options' => [
            'temperature' => 0.1 // Temperatura baja para evitar alucinaciones y mantener fidelidad
        ]
    ];

    $ch = curl_init($ollamaUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo "ERROR de conexión con Ollama: " . curl_error($ch) . "\n";
        curl_close($ch);
        continue;
    }

    curl_close($ch);

    $jsonResponse = json_decode($response, true);

    if (isset($jsonResponse['response'])) {
        $translatedCode = $jsonResponse['response'];

        // Limpieza de seguridad por si el modelo ignora la regla del markdown block
        $translatedCode = preg_replace('/^```php[\s\n]*/i', '', $translatedCode);
        $translatedCode = preg_replace('/```$/', '', $translatedCode);
        $translatedCode = trim($translatedCode);

        // Validar mínimamente que la respuesta empiece con <?php para no romper el archivo
        if (str_starts_with($translatedCode, '<?php')) {
            file_put_contents($filePath, $translatedCode);
            echo "¡ÉXITO!\n";
        } else {
            echo "FALLÓ (El modelo devolvió un formato inválido o texto plano explicativo)\n";
        }
    } else {
        echo "FALLÓ (No se recibió respuesta válida del JSON de Ollama)\n";
    }
}

echo "----------------------------------------------------------------------\n";
echo "Proceso terminado. Ejecuta 'vendor/bin/phpunit' para validar la integridad del código.\n";