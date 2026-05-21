<?php

const INDENT = "\t\t\t";

/**
 * This is the template for generating the model class of a specified table.
 */

/** @var $enum array list of ENUM fields */
/** @var yii\web\View $this */
/** @var yii\gii\generators\model\Generator $generator */
/** @var string $tableName full table name */
/** @var string $className class name */
/** @var string $queryClassName query class name */
/** @var yii\db\TableSchema $tableSchema */
/** @var array $properties list of properties (property => [type, name. comment]) */
/** @var string[] $labels list of attribute labels (name => label) */
/** @var string[] $rules list of validation rules */
/** @var array $relations list of relations (name => relation declaration) */
/** @var array $relationsClassHints */

// ============================================================================
// CRITICAL: FLUENT RULES GENERATION
// ============================================================================
// This line is essential for the template to work with yii2-fluent-rules.
// It parses Yii2's native rules into the fluent builder syntax.
//
// If you are customizing this template or creating your own, you MUST include
// this line to populate the $fluentRules variable, unless you choose to
// implement your own custom rule parsing algorithm.
// ============================================================================
$fluentRules = \ovargas\fluentrules\GiiHelper::parseGiiRules($rules);

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use Yii;
use ovargas\fluentrules\RuleBuilder;
use ovargas\fluentrules\Attribute;

/**
 * This is the model class for table "<?= $generator->generateTableName($tableName) ?>".
 *
<?php foreach ($properties as $property => $data): ?>
 * @property <?= "{$data['type']} \${$property}"  . ($data['comment'] ? ' ' . strtr($data['comment'], ["\n" => ' ']) : '') . "\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{
<?php if (!empty($enum)): ?>
    /**
     * ENUM field values
     */
<?php
    foreach($enum as $columnName => $columnData) {
        foreach ($columnData['values'] as $enumValue){
            echo '    const ' . $enumValue['constName'] . ' = \'' . $enumValue['value'] . '\';' . PHP_EOL;
        }
    }
endif
?>

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '<?= $generator->generateTableName($tableName) ?>';
    }
<?php if ($generator->db !== 'db'): ?>

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return RuleBuilder::rules([
<?php
    echo INDENT . implode(',' . PHP_EOL . INDENT, $fluentRules) . PHP_EOL;
?>
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
            <?= "'$name' => " . $generator->generateString($label) . ",\n" ?>
<?php endforeach; ?>
        ];
    }
<?php foreach ($relations as $name => $relation): ?>

    /**
     * Gets query for [[<?= $name ?>]].
     *
     * @return <?= $relationsClassHints[$name] . "\n" ?>
     */
    public function get<?= $name ?>()
    {
        <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>
<?php if ($queryClassName): ?>
<?php
    $queryClassFullName = ($generator->ns === $generator->queryNs) ? $queryClassName : '\\' . $generator->queryNs . '\\' . $queryClassName;
    echo "\n";
?>
    /**
     * {@inheritdoc}
     * @return <?= $queryClassFullName ?> the active query used by this AR class.
     */
    public static function find()
    {
        return new <?= $queryClassFullName ?>(get_called_class());
    }
<?php endif; ?>

<?php if ($enum): ?>
<?php     foreach ($enum as $columnName => $columnData): ?>

    /**
     * column <?= $columnName ?> ENUM value labels
     * @return string[]
     */
    public static function <?= $columnData['funcOptsName'] ?>()
    {
        return [
<?php         foreach ($columnData['values'] as $k => $value): ?>
<?php
        if ($generator->enableI18N) {
            echo '            self::' . $value['constName'] . ' => Yii::t(\'' . $generator->messageCategory . '\', \'' . $value['value'] . "'),\n";
        } else {
            echo '            self::' . $value['constName'] . ' => \'' . $value['value'] . "',\n";
        }
    ?>
<?php         endforeach; ?>
        ];
    }
<?php     endforeach; ?>
<?php     foreach ($enum as $columnName => $columnData): ?>

    /**
     * @return string
     */
    public function <?= $columnData['displayFunctionPrefix'] ?>()
    {
        return self::<?= $columnData['funcOptsName'] ?>()[$this-><?=$columnName?>];
    }
<?php         foreach ($columnData['values'] as $enumValue): ?>

    /**
     * @return bool
     */
    public function <?= $columnData['isFunctionPrefix'] . $enumValue['functionSuffix'] ?>()
    {
        return $this-><?= $columnName ?> === self::<?= $enumValue['constName'] ?>;
    }

    public function <?= $columnData['setFunctionPrefix'] . $enumValue['functionSuffix'] ?>()
    {
        $this-><?= $columnName ?> = self::<?= $enumValue['constName'] ?>;
    }
<?php         endforeach; ?>
<?php     endforeach; ?>
<?php endif; ?>
}
