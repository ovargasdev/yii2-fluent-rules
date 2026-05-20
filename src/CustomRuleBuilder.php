<?php

namespace ovargas\fluentrules;

/**
 * CustomRuleBuilder allows configuring custom validation rules (Inline Validators).
 *
 * It is used to link the validation of an attribute with a method defined within the
 * model itself or via an anonymous function (Closure) that contains specific business logic.
 *
 * @author Omar Vargas
 * @since 1.0.0
 */
class CustomRuleBuilder extends RuleBuilder
{
    /**
     * Initializes a new instance for a custom validation.
     *
     * @param Attribute $attribute The attribute associated with the rule.
     * @param string|callable $methodName The name of the method in the model or an anonymous validation function.
     */
    public function __construct(Attribute $attribute, string|callable $methodName)
    {
        // In Yii2, the method name or anonymous function directly acts
        // as the validator type definition in the rules array.
        parent::__construct($attribute, $methodName);
    }
}