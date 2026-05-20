<?php

namespace ovargas\fluentrules\tests;

use PHPUnit\Framework\TestCase;
use ovargas\fluentrules\Attribute;

/**
 * RuleBuildersExhaustiveTest runs comprehensive verification across all
 * specialized RuleBuilder instances using Fully Qualified Class Names (FQCN).
 *
 * @author Omar Vargas
 * @since 1.0.0
 */
class RuleBuildersExhaustiveTest extends TestCase
{
    /**
     * @dataProvider realValidatorsProvider
     */
    public function testBuildersOutputExactYii2Structures(object $builder, array $expectedStructure): void
    {
        $this->assertEquals($expectedStructure, $builder->make());
    }

    /**
     * Data Provider verifying every single builder against default states
     * using absolute namespacing to prevent routing failures.
     */
    public static function realValidatorsProvider(): array
    {
        $attribute = Attribute::create('field');

        return [
            // =========================================================================
            // CORE VALIDATORS
            // =========================================================================
            'Required - Default' => [
                new \ovargas\fluentrules\RequiredRuleBuilder($attribute),
                ['field', 'required']
            ],
            'String - Max Length Option' => [
                new \ovargas\fluentrules\StringRuleBuilder($attribute, 255),
                ['field', 'string', 'max' => 255]
            ],
            'Email - Default' => [
                new \ovargas\fluentrules\EmailRuleBuilder($attribute),
                ['field', 'email']
            ],
            'Url - Default' => [
                new \ovargas\fluentrules\UrlRuleBuilder($attribute),
                ['field', 'url']
            ],
            'Ip - Default' => [
                new \ovargas\fluentrules\IpRuleBuilder($attribute),
                ['field', 'ip']
            ],
            'Match - Regex Option' => [
                new \ovargas\fluentrules\RegularExpressionRuleBuilder($attribute, '/^[A-Z0-9]+$/i'),
                ['field', 'match', 'pattern' => '/^[A-Z0-9]+$/i']
            ],'Filter - Callable Option'
             => [
                new \ovargas\fluentrules\FilterRuleBuilder($attribute, 'intval'),
                ['field', 'filter', 'filter' => 'intval', 'skipOnEmpty' => false]
            ],
            'Safe - Default' => [
                new \ovargas\fluentrules\SafeRuleBuilder($attribute),
                ['field', 'safe']
            ],

            // =========================================================================
            // NUMERIC & LOGICAL VALIDATORS
            // =========================================================================
            'Boolean - Default' => [
                new \ovargas\fluentrules\BooleanRuleBuilder($attribute),
                ['field', 'boolean']
            ],
            'Integer - Default' => [
                new \ovargas\fluentrules\IntegerRuleBuilder($attribute),
                ['field', 'integer']
            ],
            'Number - Default' => [
                new \ovargas\fluentrules\NumberRuleBuilder($attribute),
                ['field', 'number']
            ],

            // =========================================================================
            // RANGE & IN VALIDATORS
            // =========================================================================
            'Range - Default with Array' => [
                new \ovargas\fluentrules\RangeRuleBuilder($attribute, [1, 2, 3]),
                ['field', 'in', 'range' => [1, 2, 3]] // Cambiado 'range' por 'in'
            ],

            // =========================================================================
            // COMPARISON VALIDATORS
            // =========================================================================
            'Compare Value - Default' => [
                new \ovargas\fluentrules\CompareValueRuleBuilder($attribute, 10, '>='),
                ['field', 'compare', 'compareValue' => 10, 'operator' => '>=']
            ],
            'Compare Attribute - Default' => [
                new \ovargas\fluentrules\CompareAttributeRuleBuilder($attribute, 'password_repeat'),
                ['field', 'compare', 'compareAttribute' => 'password_repeat']
            ],

            // =========================================================================
            // DATABASE & ADVANCED VALIDATORS
            // =========================================================================
            'Exist - Target Class and Attribute' => [
                new \ovargas\fluentrules\ExistRuleBuilder($attribute, 'common\models\Tenant', 'id'),
                [
                    'field', 'exist',
                    'targetClass' => 'common\models\Tenant',
                    'targetAttribute' => 'id',
                    'skipOnError' => true // Añadido el comportamiento por defecto de tu clase
                ]
            ],
            'Unique - Target Class Option' => [
                new \ovargas\fluentrules\UniqueRuleBuilder($attribute, 'common\models\User'),
                ['field', 'unique', 'targetClass' => 'common\models\User']
            ],
            'Default Value - Explicit Assignment' => [
                new \ovargas\fluentrules\DefaultValueRuleBuilder($attribute, 'active'),
                ['field', 'default', 'value' => 'active']
            ],
            'Time - Format Option' => [
                new \ovargas\fluentrules\TimeRuleBuilder($attribute, 'H:i:s'),
                ['field', 'date', 'type' => 'time', 'format' => 'H:i:s']
            ],

            // =========================================================================
            // FILES & MEDIA VALIDATORS
            // =========================================================================
            'File - Default' => [
                new \ovargas\fluentrules\FileRuleBuilder($attribute),
                ['field', 'file']
            ],
            'Image - Default' => [
                new \ovargas\fluentrules\ImageRuleBuilder($attribute),
                ['field', 'image']
            ],

            // =========================================================================
            // MISC VALIDATORS
            // =========================================================================
            'Captcha - Default' => [
                new \ovargas\fluentrules\CaptchaRuleBuilder($attribute),
                ['field', 'captcha']
            ],
        ];
    }

    /**
     * Verifies that the core RuleBuilder::rules() master compiler correctly flattens
     * a hybrid combination of fluent Attributes and native standalone Yii2 validation arrays.
     */
    public function testMasterRulesCompilerWithHybridSet(): void
    {
        $compiledRules = \ovargas\fluentrules\RuleBuilder::rules([
            \ovargas\fluentrules\Attribute::create('code')->string(2)->skipOnEmpty(true),
            \ovargas\fluentrules\Attribute::create('price')->integer(),
            ['population', 'integer'] // Regla nativa pura estilo Yii2
        ]);

        $expectedLayout = [
            ['code', 'string', 'max' => 2, 'skipOnEmpty' => true],
            ['price', 'integer'],
            ['population', 'integer']
        ];

        $this->assertEquals($expectedLayout, $compiledRules);
    }
}