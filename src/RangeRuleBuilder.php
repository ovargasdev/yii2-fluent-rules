<?php

namespace ovargas\fluentrules;

/**
 * RangeRuleBuilder helps build the 'in' (Range) validation rule of Yii2.
 *
 * Maps directly to the validator `yii\validators\RangeValidator` to ensure
 * that the attribute value is within a predefined list or dataset.
 *
 * @author Omar Vargas
 * @since 1.0.0
 */
class RangeRuleBuilder extends RuleBuilder
{
    /**
     * Initializes a new instance for range validation.
     *
     * @param Attribute $attribute The attribute associated with the rule.
     * @param \Closure|iterable|null $range List of allowed values or an anonymous function that returns them.
     */
    public function __construct(Attribute $attribute, \Closure|iterable|null $range = null)
    {
        parent::__construct($attribute, 'in');
        if ($range !== null) {
            $this->range($range);
        }
    }

    /**
     * Sets the valid dataset against which the attribute will be compared.
     * Can accept an array, any iterable object, or an anonymous function.
     *
     * @param \Closure|iterable $range Dataset or callback.
     * @return $this
     */
    public function range(\Closure|iterable $range): static
    {
        $this->_params['range'] = $range;
        return $this;
    }

    /**
     * Configures whether the comparison between the received value and the range should be strict (same type and value, `===`).
     *
     * @param bool $strict By default true.
     * @return $this
     */
    public function strict(bool $strict = true): static
    {
        $this->_params['strict'] = $strict;
        return $this;
    }

    /**
     * Inverts the validation logic. If enabled, the attribute value will be considered valid only if it is **not** within the defined range.
     *
     * @param bool $not By default true.
     * @return $this
     */
    public function not(bool $not = true): static
    {
        $this->_params['not'] = $not;
        return $this;
    }

    /**
     * Configures whether the validator should accept an array of values. Useful when validating a multi-select field (checkbox list / multi-select), ensuring each element belongs to the range.
     *
     * @param bool $allow By default true.
     * @return $this
     */
    public function allowArray(bool $allow = true): static
    {
        $this->_params['allowArray'] = $allow;
        return $this;
    }
}