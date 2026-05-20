<?php

namespace ovargas\fluentrules;

/**
 * SafeRuleBuilder helps build the 'safe' validation rule of Yii2.
 *
 * Maps directly to the `yii\validators\SafeValidator`. It does not perform any active validation on the value, but marks the attribute as "safe" to allow its mass assignment via `$model->load()`.
 *
 * @author Omar Vargas
 * @since 1.0.0
 */
class SafeRuleBuilder extends RuleBuilder
{
    /**
     * Initializes a new instance to mark an attribute as safe.
     *
     * @param Attribute $attribute The attribute associated with the rule.
     */
    public function __construct(Attribute $attribute)
    {
        parent::__construct($attribute, 'safe');
    }
}