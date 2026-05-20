<?php

namespace ovargas\fluentrules;

/**
 * StringRuleBuilder helps build the 'string' validation rule of Yii2.
 *
 * Maps directly to the validator `yii\validators\StringValidator` to verify that the attribute value is a valid text string and meets the specified length constraints.
 *
 * @author Omar Vargas
 * @since 1.0.0
 */
class StringRuleBuilder extends RuleBuilder
{
    /**
     * Initializes a new instance for string validation.
     *
     * By default sets a safe maximum length of 255 characters if none is provided.
     *
     * @param Attribute $attribute The attribute associated with the rule.
     * @param int|null $length Maximum length of the string. Default is 255.
     */
    public function __construct(Attribute $attribute, ?int $length = 255)
    {
        parent::__construct($attribute, 'string');
        $this->max($length ?? 255);
    }

    /**
     * Sets the maximum allowed number of characters for the string.
     *
     * @param int $length Maximum length in characters.
     * @return $this
     */
    public function max(int $length): static
    {
        $this->_params['max'] = $length;
        return $this;
    }

    /**
     * Sets the minimum required number of characters for the string.
     *
     * @param int $length Minimum length in characters.
     * @return $this
     */
    public function min(int $length): static
    {
        $this->_params['min'] = $length;
        return $this;
    }

    /**
     * Removes the maximum length restriction, allowing the string to act as unlimited free text
     * (equivalent to TEXT columns in a database).
     *
     * @return $this
     */
    public function asText(): static
    {
        unset($this->_params['max']);
        return $this;
    }
}