<?php

namespace ovargas\fluentrules;

/**
 * DefaultValueRuleBuilder helps configure the 'default' validation rule of Yii2.
 *
 * Instead of validating, this constructor intercepts empty attributes during the validation process
 * and assigns them a default value before saving the model.
 *
 * @author Omar Vargas
 * @since 1.0.0
 */
class DefaultValueRuleBuilder extends RuleBuilder
{
    /**
     * Initializes a new instance to assign a default value to an attribute.
     *
     * @param Attribute $attribute The attribute associated with the rule.
     * @param mixed $value The initial default value that will be assigned if the field is empty.
     */
    public function __construct(Attribute $attribute, mixed $value = null)
    {
        parent::__construct($attribute, 'default');

        if ($value !== null) {
            $this->value($value);
        }
    }

    /**
     * Sets the value that will be assigned to the attribute if it is empty.
     *
     * @param mixed $value The default value (can be a string, int, array, etc.).
     * @return $this
     */
    public function value(mixed $value): static
    {
        $this->_params['value'] = $value;
        return $this;
    }

    /**
     * Determines whether the rule should be skipped if the attribute is empty.
     *
     * Note: In Yii2's 'default' validator, this value is usually kept at `false`
     * to ensure that the default value is applied precisely when there is no data.
     *
     * @param bool $skip Default is false to ensure assignment in empty fields.
     * @return $this
     */
    public function skipOnEmpty(bool $skip = false): static
    {
        $this->_params['skipOnEmpty'] = $skip;
        return $this;
    }
}