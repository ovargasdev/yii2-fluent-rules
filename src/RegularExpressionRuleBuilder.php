<?php

namespace ovargas\fluentrules;

/**
 * RegularExpressionRuleBuilder helps build the 'match' validation rule of Yii2.
 *
 * Maps directly to the validator `yii\validators\RegularExpressionValidator` to check
 * that the value matches a specified PCRE regular expression.
 *
 * @author Omar Vargas
 * @since 1.0.0
 */
class RegularExpressionRuleBuilder extends RuleBuilder
{
    /**
     * Initializes a new instance for regular expression validation.
     *
     * @param Attribute $attribute The attribute associated with the rule.
     * @param string $pattern Matching regular expression (must include delimiters, e.g. '/^[A-Z0-9]$/i').
     */
    public function __construct(Attribute $attribute, string $pattern)
    {
        parent::__construct($attribute, 'match');
        $this->_params['pattern'] = $pattern;
    }

    /**
     * Inverts the pattern validation. If enabled, the attribute will be valid only if the value **does not match** the specified regular expression.
     *
     * @param bool $invertMatch Default is true.
     * @return $this
     */
    public function not(bool $invertMatch = true): static
    {
        $this->_params['not'] = $invertMatch;
        return $this;
    }

    /**
     * Determines whether the regular expression rule is skipped when the attribute value is empty.
     *
     * @param bool $skip Default is true.
     * @return $this
     */
    public function skipOnEmpty(bool $skip = true): static
    {
        $this->_params['skipOnEmpty'] = $skip;
        return $this;
    }
}