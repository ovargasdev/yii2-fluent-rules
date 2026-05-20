<?php

namespace ovargas\fluentrules;

/**
 * IntegerRuleBuilder helps build the 'integer' validation rule of Yii2.
 *
 * Maps directly to the `yii\validators\NumberValidator` validator with the
 * [[integerOnly]] property internally set to true by the Yii2 core. Ensures
 * that the attribute value corresponds to a valid integer.
 *
 * @author Omar Vargas
 * @since 1.0.0
 */
class IntegerRuleBuilder extends NumberRuleBuilder
{
    /**
     * Initializes a new instance for integer validation.
     *
     * @param Attribute $attribute The attribute associated with the rule.
     */
    public function __construct(Attribute $attribute)
    {
        parent::__construct($attribute, 'integer');
    }
}