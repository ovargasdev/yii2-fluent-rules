<?php

namespace ovargas\fluentrules;

/**
 * EmailRuleBuilder helps build the 'email' validation rule of Yii2.
 *
 * Maps directly to the validator `yii\validators\EmailValidator` to check
 * if the attribute value is a valid email address according to RFC specifications.
 *
 * @author Omar Vargas
 * @since 1.0.0
 */
class EmailRuleBuilder extends RuleBuilder
{
    /**
     * Initializes a new instance for email validation.
     *
     * @param Attribute $attribute The attribute associated with the rule.
     */
    public function __construct(Attribute $attribute)
    {
        parent::__construct($attribute, 'email');
    }

    /**
     * Defines the regular expression to validate normal email addresses.
     *
     * @param string $regex Custom regular expression.
     * @return $this
     */
    public function pattern(string $regex): static
    {
        $this->_params['pattern'] = $regex;
        return $this;
    }

    /**
     * Defines the regular expression that validates emails containing the user's display name
     * (e.g. "Omar Vargas <omar@example.com>"). Requires [[allowName]] to be enabled.
     *
     * @param string $regex Full regular expression.
     * @return $this
     */
    public function fullPattern(string $regex): static
    {
        $this->_params['fullPattern'] = $regex;
        return $this;
    }

    /**
     * Defines the regular expression to validate the ASCII section of an email when [[enableIDN]] is active.
     *
     * @param string $regex Regular expression for ASCII characters.
     * @return $this
     */
    public function patternASCII(string $regex): static
    {
        $this->_params['patternASCII'] = $regex;
        return $this;
    }

    /**
     * Defines the full regular expression to validate the ASCII section with display name.
     *
     * @param string $regex Full ASCII regular expression.
     * @return $this
     */
    public function fullPatternASCII(string $regex): static
    {
        $this->_params['fullPatternASCII'] = $regex;
        return $this;
    }

    /**
     * Allows or denies the email format with display name (e.g. "Name <email@domain.com>").
     *
     * @param bool $allow Default is true.
     * @return $this
     */
    public function allowName(bool $allow = true): static
    {
        $this->_params['allowName'] = $allow;
        return $this;
    }

    /**
     * Indicates whether a DNS (MX or A) record check should be performed to ensure the domain exists.
     *
     * @param bool $check Default is true.
     * @return $this
     */
    public function checkDNS(bool $check = true): static
    {
        $this->_params['checkDNS'] = $check;
        return $this;
    }

    /**
     * Enables support for internationalized domain names (IDN) that contain non-ASCII characters.
     *
     * @param bool $enable Default is true.
     * @return $this
     */
    public function enableIDN(bool $enable = true): static
    {
        $this->_params['enableIDN'] = $enable;
        return $this;
    }

    /**
     * Enables IDN support specifically for the local part (the part before the @) of the email address.
     *
     * @param bool $enable Default is true.
     * @return $this
     */
    public function enableLocalIDN(bool $enable = true): static
    {
        $this->_params['enableLocalIDN'] = $enable;
        return $this;
    }
}