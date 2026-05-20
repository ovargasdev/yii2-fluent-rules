<?php

namespace ovargas\fluentrules;

/**
 * NumberRuleBuilder helps build the 'number' validation rule of Yii2.
 *
 * Maps directly to the `yii\validators\NumberValidator` validator to check
 * if the attribute contains a valid numeric value. It also serves as a base
 * class for integer validation.
 *
 * @author Omar Vargas
 * @since 1.0.0
 */
class NumberRuleBuilder extends RuleBuilder
{
    /**
     * Initializes a new instance for numeric validation.
     *
     * @param Attribute $attribute The attribute associated with the rule.
     * @param string $type The internal Yii2 validator type (default 'number').
     */
    public function __construct(Attribute $attribute, string $type = 'number')
    {
        parent::__construct($attribute, $type);
    }

    /**
     * Configures whether the validator should accept an array of input values.
     *
     * @param bool $allow Default is true.
     * @return $this
     */
    public function allowArray(bool $allow = true): static
    {
        $this->_params['allowArray'] = $allow;
        return $this;
    }

    /**
     * Determines if the value must be strictly an integer.
     *
     * @param bool $integerOnly Default is true.
     * @return $this
     */
    public function integerOnly(bool $integerOnly = true): static
    {
        $this->_params['integerOnly'] = $integerOnly;
        return $this;
    }

    /**
     * Sets the minimum numeric limit allowed (inclusive).
     *
     * @param int|float $value Minimum value.
     * @return $this
     */
    public function min(int|float $value): static
    {
        $this->_params['min'] = $value;
        return $this;
    }

    /**
     * Sets the maximum numeric limit allowed (inclusive).
     *
     * @param int|float $value Maximum value.
     * @return $this
     */
    public function max(int|float $value): static
    {
        $this->_params['max'] = $value;
        return $this;
    }

    /**
     * Sets a custom error message for when the value is less than [[min]].
     *
     * @param string $message Error message.
     * @return $this
     */
    public function tooSmall(string $message): static
    {
        $this->_params['tooSmall'] = $message;
        return $this;
    }

    /**
     * Sets a custom error message for when the value is greater than [[max]].
     *
     * @param string $message Error message.
     * @return $this
     */
    public function tooBig(string $message): static
    {
        $this->_params['tooBig'] = $message;
        return $this;
    }

    /**
     * Sets the regular expression to validate integers when [[integerOnly]] is active.
     *
     * @param string $pattern Regular expression.
     * @return $this
     */
    public function integerPattern(string $pattern): static
    {
        $this->_params['integerPattern'] = $pattern;
        return $this;
    }

    /**
     * Sets the regular expression to validate general decimal/float numbers.
     *
     * @param string $pattern Regular expression.
     * @return $this
     */
    public function numberPattern(string $pattern): static
    {
        $this->_params['numberPattern'] = $pattern;
        return $this;
    }
}