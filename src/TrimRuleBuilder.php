<?php

namespace ovargas\fluentrules;

/**
 * TrimRuleBuilder helps build the 'trim' validation rule of Yii2.
 *
 * Maps directly to the validator `yii\validators\FilterValidator` initialized with the 'trim'
 * function from the Yii2 core. Removes whitespace (or other specified characters) from the beginning and end of the value.
 *
 * @author Omar Vargas
 * @since 1.0.0
 */
class TrimRuleBuilder extends RuleBuilder
{
    /**
     * Initializes a new instance for the trim filter.
     *
     * @param Attribute $attribute The attribute associated with the rule.
     */
    public function __construct(Attribute $attribute)
    {
        parent::__construct($attribute, 'trim');
    }

    /**
     * Defines an explicit list of characters that the filter should remove from the ends of the value.
     * If not defined, traditional whitespace will be removed by default.
     *
     * @param string $chars List of characters to remove (character mask).
     * @return $this
     */
    public function chars(string $chars): static
    {
        $this->_params['chars'] = $chars;
        return $this;
    }

    /**
     * Configures whether the filter should be completely ignored if the attribute value is an array.
     *
     * @param bool $skipOnArray Default is true.
     * @return $this
     */
    public function skipOnArray(bool $skipOnArray = true): static
    {
        $this->_params['skipOnArray'] = $skipOnArray;
        return $this;
    }

    /**
     * Configures whether the filter should be completely ignored if the attribute value is empty.
     *
     * @param bool $skipOnEmpty Default is true.
     * @return $this
     */
    public function skipOnEmpty(bool $skipOnEmpty = true): static
    {
        $this->_params['skipOnEmpty'] = $skipOnEmpty;
        return $this;
    }
}