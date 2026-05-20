<?php

namespace ovargas\fluentrules;

/**
 * CompareAttributeRuleBuilder helps to build the 'compare' validation rule of Yii2
 * focused on comparing two attributes of the same model.
 *
 * It directly maps to the validator `yii\validators\CompareValidator` when it is required
 * to verify the value of an attribute against another (e.g., 'password' against 'password_repeat').
 *
 * @see \yii\validators\CompareValidator
 * @see CompareRuleBuilder
 * @author Omar Vargas
 * @since 1.0.0
 */
class CompareAttributeRuleBuilder extends CompareRuleBuilder
{
    /**
     * Initializes a new instance for comparing attributes.
     *
     * @param Attribute $attribute The base attribute to be validated.
     * @param string $compareWithAttributeName The name of the attribute against which to compare.
     * @param string|null $operator The comparison operator (e.g., '==', '===', '!=', '>', '>=', '<', '<=').
     * @param string|null $message Custom error message in case of failure.
     */
    public function __construct(Attribute $attribute, string $compareWithAttributeName, ?string $operator = null, ?string $message = null)
    {
        parent::__construct($attribute, false, $operator, $message);
        $this->compareAttribute($compareWithAttributeName);
    }

    /**
     * Explicitly defines the name of the attribute with which the comparison will be made.
     *
     * @param string $attributeName Name of the destination attribute (e.g., 'password_repeat').
     * @return $this
     */
    public function compareAttribute(string $attributeName): static
    {
        $this->_params['compareAttribute'] = $attributeName;
        return $this;
    }

    /**
     * Configures the rule to validate that the base attribute is strictly greater than the specified attribute.
     *
     * @param string $attribute Name of the attribute against which to compare.
     * @param string|null $message Optional custom error message.
     * @return $this
     */
    public function greaterThan(string $attribute, ?string $message = null): static
    {
        return $this->_greaterThan($attribute, $message);
    }

    /**
     * Configures the rule to validate that the base attribute is greater than or equal to the specified attribute.
     *
     * @param string $attribute Name of the attribute against which to compare.
     * @param string|null $message Optional custom error message.
     * @return $this
     */
    public function greaterThanOrEqual(string $attribute, ?string $message = null): static
    {
        return $this->_greaterThanOrEqual($attribute, $message);
    }

    /**
     * Configures the rule to validate that the base attribute is strictly less than the specified attribute.
     *
     * @param string $attribute Name of the attribute against which to compare.
     * @param string|null $message Optional custom error message.
     * @return $this
     */
    public function lessThan(string $attribute, ?string $message = null): static
    {
        return $this->_lessThan($attribute, $message);
    }

    /**
     * Configures the rule to validate that the base attribute is less than or equal to the specified attribute.
     *
     * @param string $attribute Name of the attribute against which to compare.
     * @param string|null $message Optional custom error message.
     * @return $this
     */
    public function lessThanOrEqual(string $attribute, ?string $message = null): static
    {
        return $this->_lessThanOrEqual($attribute, $message);
    }
}