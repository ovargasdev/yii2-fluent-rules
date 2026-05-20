<?php

namespace ovargas\fluentrules;

/**
 * CompareValueRuleBuilder helps build the Yii2 'compare' validation rule focused on comparing an attribute against a static or constant value.
 *
 * It maps directly to the `yii\validators\CompareValidator` when it is necessary to verify that an attribute's value satisfies a condition relative to a fixed value (e.g., validating that 'age' is greater than or equal to 18).
 *
 * @see \yii\validators\CompareValidator
 * @see CompareRuleBuilder
 * @author Omar Vargas
 * @since 1.0.0
 */
class CompareValueRuleBuilder extends CompareRuleBuilder
{
    /**
     * Initializes a new instance for comparison against a static value.
     *
     * @param Attribute $attribute The base attribute to be validated.
     * @param mixed $value The constant value to compare against initially.
     * @param string|null $operator The comparison operator (e.g., '==', '===', '!=', '>', '>=', '<', '<=').
     * @param string|null $message Optional custom error message.
     */
    public function __construct(Attribute $attribute, mixed $value, ?string $operator = null, ?string $message = null)
    {
        parent::__construct($attribute, true, $operator, $message);
        $this->compareValue($value);
    }

    /**
     * Explicitly defines or modifies the constant value with which the comparison will be performed.
     *
     * @param mixed $value The constant value.
     * @return $this
     */
    public function compareValue(mixed $value): static
    {
        $this->_params['compareValue'] = $value;
        return $this;
    }

    /**
     * Configures the rule to validate that the attribute is strictly GREATER THAN the specified value.
     *
     * @param mixed $value The constant value to compare against.
     * @param string|null $message Optional custom error message.
     * @return $this
     */
    public function greaterThan(mixed $value, ?string $message = null): static
    {
        return $this->_greaterThan($value, $message);
    }

    /**
     * Configures the rule to validate that the attribute is GREATER OR EQUAL TO the specified value.
     *
     * @param mixed $value The constant value to compare against.
     * @param string|null $message Optional custom error message.
     * @return $this
     */
    public function greaterThanOrEqual(mixed $value, ?string $message = null): static
    {
        return $this->_greaterThanOrEqual($value, $message);
    }

    /**
     * Configures the rule to validate that the attribute is strictly LESS THAN the specified value.
     *
     * @param mixed $value The constant value to compare against.
     * @param string|null $message Optional custom error message.
     * @return $this
     */
    public function lessThan(mixed $value, ?string $message = null): static
    {
        return $this->_lessThan($value, $message);
    }

    /**
     * Configures the rule to validate that the attribute is LESS OR EQUAL TO the specified value.
     *
     * @param mixed $value The constant value to compare against.
     * @param string|null $message Optional custom error message.
     * @return $this
     */
    public function lessThanOrEqual(mixed $value, ?string $message = null): static
    {
        return $this->_lessThanOrEqual($value, $message);
    }
}