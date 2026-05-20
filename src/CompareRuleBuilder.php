<?php

namespace ovargas\fluentrules;

/**
 * CompareRuleBuilder is the abstract base class for building comparison rules.
 *
 * Consolidates the shared logic between validating constant values and comparing
 * attributes within the same model, directly mapping to `yii\validators\CompareValidator`.
 *
 * @see \yii\validators\CompareValidator
 * @author Omar Vargas
 * @since 1.0.0
 */
abstract class CompareRuleBuilder extends RuleBuilder
{
    /**
     * @var bool Determines whether the rule compares against a static value (true) or against another attribute (false).
     */
    private bool $comparingValue;

    /**
     * Initializes the base configuration of the comparison validator.
     *
     * @param Attribute $attribute The attribute to be validated.
     * @param bool $comparingValue True if comparison is against a static value, false if against another attribute.
     * @param string|null $operator The initial comparison operator (e.g., '==', '===', '>', etc).
     * @param string|null $message Custom error message.
     */
    public function __construct(Attribute $attribute, bool $comparingValue, ?string $operator = null, ?string $message = null)
    {
        parent::__construct($attribute, 'compare');
        $this->comparingValue = $comparingValue;

        if ($operator !== null) {
            $this->operator($operator === '=' ? '==' : $operator);
        }
        if ($message !== null) {
            $this->message($message);
        }
    }

    /**
     * Sets the static constant value against which to perform the comparison.
     *
     * @param mixed $value The constant value.
     * @return $this
     */
    protected function compareValue(mixed $value): static
    {
        $this->_params['compareValue'] = $value;
        return $this;
    }

    /**
     * Sets the name of the attribute against which to perform the comparison.
     *
     * @param string $attributeName The attribute name in the model.
     * @return $this
     */
    protected function compareAttribute(string $attributeName): static
    {
        $this->_params['compareAttribute'] = $attributeName;
        return $this;
    }

    /**
     * Specifies the comparison operator that the Yii2 validator will use.
     *
     * @param string $operator Valid values are: '==', '===', '!=', '!==', '>', '>=', '<', '<='.
     * @return $this
     */
    public function operator(string $operator): static
    {
        $this->_params['operator'] = $operator;
        return $this;
    }

    /**
     * Internal utility method to simultaneously configure the target, operator, and message.
     *
     * @param mixed $valueOrAttributeName Constant value or attribute name.
     * @param string $operator Comparison operator to apply.
     * @param string|null $message Optional error message.
     * @return $this
     */
    protected function _setOperator(mixed $valueOrAttributeName, string $operator, ?string $message): static
    {
        if ($this->comparingValue) {
            $this->compareValue($valueOrAttributeName);
        } else {
            $this->compareAttribute($valueOrAttributeName);
        }

        $this->operator($operator);

        if ($message !== null) {
            $this->message($message);
        }
        return $this;
    }

    /**
     * Internal logic to configure a "greater than" comparison.
     *
     * @param mixed $valueOrAttributeName Constant value or attribute name.
     * @param string|null $message Optional error message.
     * @return $this
     */
    protected function _greaterThan(mixed $valueOrAttributeName, ?string $message = null): static
    {
        return $this->_setOperator($valueOrAttributeName, '>', $message);
    }

    /**
     * Internal logic to configure a "greater than or equal to" comparison.
     *
     * @param mixed $valueOrAttributeName Constant value or attribute name.
     * @param string|null $message Optional error message.
     * @return $this
     */
    protected function _greaterThanOrEqual(mixed $valueOrAttributeName, ?string $message = null): static
    {
        return $this->_setOperator($valueOrAttributeName, '>=', $message);
    }

    /**
     * Internal logic to configure a "less than" comparison.
     *
     * @param mixed $valueOrAttributeName Constant value or attribute name.
     * @param string|null $message Optional error message.
     * @return $this
     */
    protected function _lessThan(mixed $valueOrAttributeName, ?string $message = null): static
    {
        return $this->_setOperator($valueOrAttributeName, '<', $message);
    }

    /**
     * Internal logic to configure a "less than or equal to" comparison.
     *
     * @param mixed $valueOrAttributeName Constant value or attribute name.
     * @param string|null $message Optional error message.
     * @return $this
     */
    protected function _lessThanOrEqual(mixed $valueOrAttributeName, ?string $message = null): static
    {
        return $this->_setOperator($valueOrAttributeName, '<=', $message);
    }

    /**
     * Sets the comparison type at the data format level.
     *
     * @param string $type Native allowed values in Yii2 are 'string' or 'number'.
     * @return $this
     */
    public function type(string $type): static
    {
        $this->_params['type'] = $type;
        return $this;
    }

    /**
     * Explicitly forces the comparison to be evaluated numerically.
     *
     * @return $this
     */
    public function asNumber(): static
    {
        return $this->type('number');
    }

    /**
     * Applies a fluent shortcut to transform the operator into a strictly identical comparison (===).
     *
     * @return $this
     */
    public function strict(): static
    {
        return $this->operator('===');
    }
}