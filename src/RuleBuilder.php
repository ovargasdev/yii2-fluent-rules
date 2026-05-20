<?php

namespace ovargas\fluentrules;

use Exception;

/**
 * RuleBuilder is the abstract base class for fluent validation rule building in Yii2.
 *
 * It provides the core entry point to flatten and compile accumulated fluent
 * structures into the native multi-dimensional arrays expected by a Yii2 Model's `rules()` method.
 *
 * @author Omar Vargas
 * @since 1.0.0
 */
abstract class RuleBuilder
{
    /**
     * @var Attribute The target model attribute instance associated with this rule builder.
     */
    protected Attribute $_attribute;

    /**
     * @var string The underlying Yii2 validator name (e.g., 'string', 'integer', 'required').
     */
    private string $_type;

    /**
     * @var array Accumulated configuration options and parameters for the validator.
     */
    protected array $_params = [];

    /**
     * Initializes a new rule builder instance.
     *
     * @param Attribute $attribute The target model attribute context.
     * @param string $type The specific native Yii2 validator identifier.
     */
    public function __construct(Attribute $attribute, string $type)
    {
        $this->_attribute = $attribute;
        $this->_type = $type;
    }

    /**
     * Compiles a mixed set of Attribute objects, RuleBuilder objects, or native arrays
     * into a flat list of standard Yii2 validation rules.
     *
     * @param array $rules List containing fluent builders, attributes, or traditional arrays.
     * @return array Linear structure of native rules ready for the Yii2 validator engine.
     */
    public static function rules(array $rules): array
    {
        return self::flatten($rules);
    }

    /**
     * Recursively flattens an array tree of rules into the sequential layout required by Yii2.
     *
     * @param array $rules Collection of mixed rules and builders.
     * @return array Flattened list of rule validation arrays.
     */
    private static function flatten(array $rules): array
    {
        $result = [];

        foreach ($rules as $rule) {
            if ($rule instanceof RuleBuilder) {
                $result = array_merge($result, $rule->toArray());
            } elseif (is_array($rule)) {
                if (self::isNativeYiiRule($rule)) {
                    $result[] = $rule;
                } else {
                    $result = array_merge($result, self::flatten($rule));
                }
            }
        }

        return $result;
    }

    /**
     * Determines whether an array matches a standard standalone Yii2 rule format.
     * A valid native rule usually has the layout: `[['attributes'], 'validator-name', 'option' => 'value']`
     *
     * @param array $rule The array structure to evaluate.
     * @return bool True if it mirrors Yii2's elementary validation rule shape.
     */
    private static function isNativeYiiRule(array $rule): bool
    {
        if (count($rule) < 2) {
            return false;
        }

        $first = reset($rule);
        $second = next($rule);

        return (is_string($first) || is_array($first)) && (is_string($second) || is_callable($second));
    }

    /**
     * Compiles and outputs the native single Yii2 validation array for the current rule context.
     *
     * @return array Formatted native rule, for example: `['code', 'string', 'max' => 2]`
     */
    public function make(): array
    {
        return array_merge(
            [$this->_attribute->name, $this->_type],
            $this->_params
        );
    }

    /**
     * Magic call router to seamlessly redirect unhandled builder methods back to the parent Attribute,
     * maintaining uninterrupted fluent chaining.
     *
     * @param string $name Name of the missing method being invoked.
     * @param array $arguments Parameters passed to the method execution.
     * @return mixed The matching rule builder instance returned by the Attribute object.
     * @throws Exception
     */
    public function __call(string $name, array $arguments): mixed
    {
        return call_user_func_array([$this->_attribute, $name], $arguments);
    }

    /**
     * Delegates full export of all compiled rules belonging to the linked attribute context.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->_attribute->toArray();
    }

    // =========================================================================
    // Global chainable methods representing native core Yii2 validator choices
    // =========================================================================

    /**
     * Sets a custom error message for this specific validation rule.
     *
     * @param string $message The customized error text.
     * @return $this
     */
    public function message(string $message): static
    {
        $this->_params['message'] = $message;
        return $this;
    }

    /**
     * Specifies the validation scenarios where this rule must be active.
     *
     * @param array|string $scenarios A single scenario name or an array of scenario labels.
     * @return $this
     */
    public function on(array|string $scenarios): static
    {
        $this->_params['on'] = $scenarios;
        return $this;
    }

    /**
     * Specifies the validation scenarios where this rule must be skipped.
     *
     * @param array|string $scenarios A single scenario name or an array of scenario labels.
     * @return $this
     */
    public function except(array|string $scenarios): static
    {
        $this->_params['except'] = $scenarios;
        return $this;
    }

    /**
     * Sets whether this validation rule should be skipped if the attribute already has an error.
     *
     * @param bool $skip Defaults to true.
     * @return $this
     */
    public function skipOnError(bool $skip = true): static
    {
        $this->_params['skipOnError'] = $skip;
        return $this;
    }

    /**
     * Sets whether this validation rule should be skipped if the attribute value is empty.
     *
     * @param bool $skip Defaults to true.
     * @return $this
     */
    public function skipOnEmpty(bool $skip = true): static
    {
        $this->_params['skipOnEmpty'] = $skip;
        return $this;
    }

    /**
     * Enables or disables frontend client-side validation logic for this rule.
     *
     * @param bool $enable Defaults to true.
     * @return $this
     */
    public function enableClientValidation(bool $enable = true): static
    {
        $this->_params['enableClientValidation'] = $enable;
        return $this;
    }

    /**
     * Configures a custom callback (inline function) to assess whether an attribute value is empty.
     *
     * @param callable $callable A function matching signature `function ($value): bool`.
     * @return $this
     */
    public function isEmpty(callable $callable): static
    {
        $this->_params['isEmpty'] = $callable;
        return $this;
    }

    /**
     * Controls server-side execution of the rule conditionally using a PHP callback.
     *
     * @param callable $callable A function matching signature `function ($model, $attribute): bool`.
     * @return $this
     */
    public function when(callable $callable): static
    {
        $this->_params['when'] = $callable;
        return $this;
    }

    /**
     * Controls client-side execution of the rule conditionally using a inline JavaScript function expression.
     *
     * @param string $jsFunction JavaScript code (e.g., "function(attribute, value) { return true; }").
     * @return $this
     */
    public function whenClient(string $jsFunction): static
    {
        $this->_params['whenClient'] = $jsFunction;
        return $this;
    }
}