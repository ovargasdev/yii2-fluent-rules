<?php

namespace ovargas\fluentrules;

/**
 * GiiHelper provides utilities for integrating yii2-fluent-rules
 * with the Yii2 Gii code generator.
 *
 * @author Omar Vargas
 */
class GiiHelper
{
    /**
     * Processes Gii's native string-formatted rules array and transforms it
     * into an array of strings featuring the library's fluent syntax.
     *
     * @param array $rules The native $rules array provided by the Gii template.
     * @return array A list of formatted Attribute::create() code strings.
     */
    public static function parseGiiRules(array $rules): array
    {
        $fluentRules = [];
        $attributeData = [];

        foreach ($rules as $ruleStr) {
            $components = self::extractRuleComponents($ruleStr);
            if ($components === null) {
                continue;
            }

            // Group metadata by each attribute detected in the rule
            foreach ($components['attributes'] as $attribute) {
                if (!isset($attributeData[$attribute])) {
                    $attributeData[$attribute] = [
                        'type' => null,
                        'notNull' => false,
                        'defaultValue' => null,
                        'relations' => [],
                        'unique' => false
                    ];
                }

                $attributeData[$attribute] = self::mapValidatorToMetadata(
                    $components['validator'],
                    $components['rest'],
                    $attributeData[$attribute]
                );
            }
        }

        // Compile the structured map into native fluent syntax without visual indentation
        foreach ($attributeData as $attr => $data) {
            $methods = [];
            $methods[] = $data['type'] ?? "safe()";

            if ($data['notNull']) {
                $methods[] = "notNull()";
            }
            if ($data['defaultValue'] !== null) {
                $methods[] = "defaultValue({$data['defaultValue']})";
            }
            if ($data['unique']) {
                $methods[] = "unique()";
            }
            foreach ($data['relations'] as $relation) {
                $methods[] = $relation;
            }

            $chain = implode('->', $methods);
            $fluentRules[] = "Attribute::create('{$attr}')->{$chain}";
        }

        return $fluentRules;
    }

    /**
     * Helper 1: Extracts the essential components from a Gii rule string.
     *
     * @param string $ruleStr The raw rule string from Gii.
     * @return array|null The extracted components, or null if the string is invalid.
     */
    private static function extractRuleComponents(string $ruleStr): ?array
    {
        // Find the beginning of the attributes block '[['
        $startAttrs = strpos($ruleStr, '[[');
        if ($startAttrs === false) {
            return null;
        }

        // The end of the attributes block is the first ']' following '[['
        $endAttrs = strpos($ruleStr, ']', $startAttrs + 2);
        if ($endAttrs === false) {
            return null;
        }

        $attrsContent = substr($ruleStr, $startAttrs + 2, $endAttrs - ($startAttrs + 2));
        $attributes = array_map(function($item) {
            return trim($item, " '\"");
        }, explode(',', $attrsContent));

        // Isolate the remaining substring (validator and parameters)
        $remainder = substr($ruleStr, $endAttrs + 1);
        $remainder = trim($remainder, ", []\t\n\r\0\x0B");

        // Explode by single quotes to isolate the validator name
        $parts = explode("'", $remainder);
        $quoteChar = "'";
        if (!isset($parts[1])) {
            // Fallback in case double quotes are used instead
            $parts = explode('"', $remainder);
            $quoteChar = '"';
        }

        if (!isset($parts[1])) {
            return null;
        }

        // Rebuild the remaining parameters substring
        return [
            'attributes' => $attributes,
            'validator'  => trim($parts[1]),
            'rest'       => implode($quoteChar, array_slice($parts, 2))
        ];
    }

    /**
     * Helper 2: Maps the validator and its remaining text into the attribute's metadata schema.
     *
     * @param string $validator The validator name (e.g., 'required', 'string').
     * @param string $rest The remaining parameters substring.
     * @param array $metadata The current metadata array for the attribute.
     * @return array The updated metadata array.
     */
    private static function mapValidatorToMetadata(string $validator, string $rest, array $metadata): array
    {
        switch ($validator) {
            case 'required':
                $metadata['notNull'] = true;
                break;

            case 'default':
                // Extract the value parameter from 'value' => ...
                if (preg_match('/[\'"]value[\'"]\s*=>\s*(.+)/i', $rest, $valMatch)) {
                    $val = trim($valMatch[1], "], \t\n\r");
                    // Ignore database functions with () and Gii initialization nulls
                    if (strpos($val, '()') === false && $val !== 'null') {
                        $metadata['defaultValue'] = $val;
                    }
                }
                break;

            case 'string':
                // If a maximum length is assigned (e.g., max => 15)
                if (preg_match('/[\'"]max[\'"]\s*=>\s*(\d+)/i', $rest, $maxMatch)) {
                    $metadata['type'] = "string({$maxMatch[1]})";
                } elseif ($metadata['type'] === null) {
                    $metadata['type'] = "string()";
                }
                break;

            case 'integer':
                $metadata['type'] = "integer()";
                break;

            case 'boolean':
                $metadata['type'] = "boolean()";
                break;

            case 'unique':
                $metadata['unique'] = true;
                break;

            case 'exist':
                // Extract the related target class and its mapped target attribute
                if (preg_match('/[\'"]targetClass[\'"]\s*=>\s*([\w\\\\]+::class)/i', $rest, $classMatch)) {
                    $targetClass = $classMatch[1];
                    $targetCol = 'id';

                    if (preg_match('/[\'"]targetAttribute[\'"]\s*=>\s*\[.+=>\s*[\'"](.+?)[\'"]\s*\]/i', $rest, $colMatch)) {
                        $targetCol = $colMatch[1];
                    }
                    $metadata['relations'][] = "exist({$targetClass}, '{$targetCol}')";
                }
                break;
        }

        return $metadata;
    }
}