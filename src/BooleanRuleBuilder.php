<?php

namespace ovargas\fluentrules;

/**
 * BooleanRuleBuilder configures validation rules for boolean attributes.
 *
 * Maps directly to the Yii2 validator `yii\validators\BooleanValidator`, which
 * checks if the attribute value is a boolean (or its configured equivalents).
 *
 * @author Omar Vargas
 * @since 1.0.0
 */
class BooleanRuleBuilder extends RuleBuilder
{
    /**
     * Initializes a new instance of the boolean validator.
     *
     * @param Attribute $attribute The attribute associated with the rule.
     */
    public function __construct(Attribute $attribute)
    {
        parent::__construct($attribute, 'boolean');
    }

    /**
     * Defines the value that represents true (TRUE).
     * By default in Yii2 is '1'.
     *
     * @param mixed $value The exact value that will be considered as true.
     * @return $this
     */
    public function trueValue(mixed $value): static
    {
        $this->_params['trueValue'] = $value;
        return $this;
    }

    /**
     * Defines the value that represents false (FALSE).
     * By default in Yii2 is '0'.
     *
     * @param mixed $value The exact value that will be considered as false.
     * @return $this
     */
    public function falseValue(mixed $value): static
    {
        $this->_params['falseValue'] = $value;
        return $this;
    }

    /**
     * Sets whether the validation should be performed strictly, checking also the data type.
     * If true, the input value must match exactly in type with [[trueValue]] or [[falseValue]].
     *
     * @param bool $strict Defaults to true when the method is invoked.
     * @return $this
     */
    public function strict(bool $strict = true): static
    {
        $this->_params['strict'] = $strict;
        return $this;
    }
}