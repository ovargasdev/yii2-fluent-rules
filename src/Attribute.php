<?php

namespace ovargas\fluentrules;

/**
 * Attribute acts as the target field context model and serves as the initiation point
 * of fluent method chaining to apply multiple ordered validation constraints consecutively.
 *
 * @method Attribute|BooleanRuleBuilder boolean()
 * @method Attribute|CustomRuleBuilder custom(callable|string $methodName)
 * @method Attribute|DateRuleBuilder date(?string $format = null)
 * @method Attribute|DateTimeRuleBuilder dateTime(?string $format = null)
 * @method Attribute|DefaultValueRuleBuilder defaultValue(mixed $value = null)
 * @method Attribute|EmailRuleBuilder email()
 * @method Attribute|ExistRuleBuilder exist(string $targetClass, string|array|null $targetAttribute)
 * @method Attribute|IntegerRuleBuilder integer()
 * @method Attribute|NumberRuleBuilder number()
 * @method Attribute|RangeRuleBuilder in(\Closure|null|iterable $range = null)
 * @method Attribute|RangeRuleBuilder range(\Closure|null|iterable $range = null)
 * @method Attribute|RequiredRuleBuilder notNull()
 * @method Attribute|RequiredRuleBuilder required()
 * @method Attribute|StringRuleBuilder string(?int $length = null)
 * @method Attribute|TrimRuleBuilder trim()
 * @method Attribute|TrimmedStringRuleBuilder strim()
 * @method Attribute|StringRuleBuilder text(?int $length = null)
 * @method Attribute|FilterRuleBuilder filter(callable|string|null $filter = null)
 * @method Attribute|TimeRuleBuilder time(?string $format = null)
 * @method Attribute|IntegerRuleBuilder timestamp()
 * @method Attribute|UniqueRuleBuilder unique(?string $targetClass = null)
 * @method Attribute|CompareValueRuleBuilder compareValue(mixed $value, ?string $operator = null, ?string $message = null)
 * @method Attribute|CompareAttributeRuleBuilder compareAttr(string $compareWithAttributeName, ?string $operator = null, ?string $message = null)
 * @method Attribute|FileRuleBuilder file()
 * @method Attribute|ImageRuleBuilder image()
 * @method Attribute|RegularExpressionRuleBuilder match(string $pattern)
 * @method Attribute|IpRuleBuilder ip()
 * @method Attribute|UrlRuleBuilder url()
 * @method Attribute|CaptchaRuleBuilder captcha()
 * @method Attribute|SafeRuleBuilder safe()
 *
 * @author Omar Vargas
 * @since 1.0.0
 */
final class Attribute
{
    /**
     * @var array<string, RuleBuilder> Instantiated rule builders cached and indexed by their core type.
     */
    protected array $_ruleBuilders = [];

    /**
     * @var array Custom or raw legacy native Yii2 rule configurations injected straight into the sequence.
     */
    protected array $_extraRules = [];

    /**
     * @var string|array The explicit field name string or an array of fields sharing this sequence.
     */
    public readonly string|array $name;

    /**
     * Private constructor to strictly enforce initialization via the static factory method `create()`.
     *
     * @param string|array $name Target model attribute identifier(s).
     */
    private function __construct(string|array $name)
    {
        $this->name = $name;
    }

    /**
     * Entry point for the fluent interface API. Generates a fresh Attribute tracking context.
     *
     * @param string|array $name Target attribute identifier in the model (accepts array for multi-field rule assignment).
     * @return static Brand new Attribute instance ready for chaining validation rules.
     */
    public static function create(string|array $name): static
    {
        return new static($name);
    }

    /**
     * Intercepts virtual magic calls mapped to specific validation signatures declared in the PHPDoc metadata,
     * routing fulfillment through the builder factory.
     *
     * @param string $name Name of the validation rule handler invoked.
     * @param array $arguments Configuration rules passed as arguments.
     * @return RuleBuilder The target specialized rule builder mapped to this call context.
     */
    public function __call(string $name, array $arguments): mixed
    {
        return $this->createBuilder($name, $arguments);
    }

    /**
     * Internal factory tasked with mapping custom intuitive aliases to core Yii2 validators,
     * dynamically executing constructor allocations, and managing internal caching.
     *
     * @param string $type Target validator name or convenience shorthand alias.
     * @param array $arguments Arguments passed for builder constructor delivery.
     * @return mixed A concrete instance implementing RuleBuilder specs.
     */
    private function createBuilder(string $type, array $arguments): mixed
    {
        // Handle migration routing and semantic mapping optimizations
        if ($type === 'notNull') {
            $type = 'required';
        } elseif ($type === 'timestamp') {
            $type = 'integer';
        } elseif ($type === 'text') {
            $type = 'string';
        } elseif ($type === 'in') {
            $type = 'range';
        } elseif ($type === 'strim') {
            $type = 'trimmedString';
        } elseif ($type === 'compareAttr') {
            $type = 'compareAttribute';
        }

        // Returns cached item immediately to safeguard against double instantiation of the same validator rule type
        if (isset($this->_ruleBuilders[$type])) {
            return $this->_ruleBuilders[$type];
        }

        // Resolve class name dynamically under the package root namespace
        $class = '\\ovargas\\fluentrules\\' . ucfirst($type) . 'RuleBuilder';

        return $this->_ruleBuilders[$type] = new $class($this, ...$arguments);
    }

    /**
     * Allows seamless inclusion of traditional, non-fluent native Yii2 array rules into the attribute queue.
     *
     * @param array $rules Array collection of traditional native Yii2 rule configurations.
     * @return $this
     */
    public function rules(array $rules): static
    {
        foreach ($rules as $rule) {
            $this->_extraRules[] = $rule;
        }
        return $this;
    }

    /**
     * Compiles and outputs all fluently configured rule objects alongside manual injections into a linear array.
     *
     * @return array Fully resolved linear structures ready for model injection.
     */
    public function toArray(): array
    {
        $builderRules = array_values(array_map(fn($rule) => $rule->make(), $this->_ruleBuilders));
        return array_merge($builderRules, $this->_extraRules);
    }
}