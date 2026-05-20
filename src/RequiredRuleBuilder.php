<?php

namespace ovargas\fluentrules;

/**
 * RequiredRuleBuilder helps build the 'required' validation rule of Yii2.
 *
 * Maps directly to the validator `yii\validators\RequiredValidator` to ensure
 * that the attribute is not sent empty and contains valid data in the input flow.
 *
 * @author Omar Vargas
 * @since 1.0.0
 */
class RequiredRuleBuilder extends RuleBuilder
{
    /**
     * Initializes a new instance for the required fields validator.
     *
     * @param Attribute $attribute The attribute associated with the rule.
     */
    public function __construct(Attribute $attribute)
    {
        parent::__construct($attribute, 'required');
    }

    /**
     * Determines whether the required rule should be skipped when the attribute is empty.
     *
     * @param bool $skip Default is true.
     * @return $this
     */
    public function skipOnEmpty(bool $skip = true): static
    {
        $this->_params['skipOnEmpty'] = $skip;
        return $this;
    }

    /**
     * Sets a specific exact value that the attribute must take in order to be considered valid.
     * If not defined, it will suffice that the field is not empty.
     *
     * @param mixed $value The strict required value.
     * @return $this
     */
    public function requiredValue(mixed $value): static
    {
        $this->_params['requiredValue'] = $value;
        return $this;
    }

    /**
     * Determines whether the comparison with the [[requiredValue]] will be performed with strict typing (`===`).
     * It only takes effect if a specific value was configured via `requiredValue()`.
     *
     * @param bool $strict Default is true.
     * @return $this
     */
    public function strict(bool $strict = true): static
    {
        $this->_params['strict'] = $strict;
        return $this;
    }
}