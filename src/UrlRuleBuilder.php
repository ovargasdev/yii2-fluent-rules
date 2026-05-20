<?php

namespace ovargas\fluentrules;

/**
 * UrlRuleBuilder helps build the 'url' validation rule of Yii2.
 *
 * Maps directly to the validator `yii\validators\UrlValidator` to check if the attribute contains a well‑formatted and valid web URL.
 *
 * @author Omar Vargas
 * @since 1.0.0
 */
class UrlRuleBuilder extends RuleBuilder
{
    /**
     * Initializes a new instance for URL validation.
     *
     * @param Attribute $attribute The attribute associated with the rule.
     */
    public function __construct(Attribute $attribute)
    {
        parent::__construct($attribute, 'url');
    }

    /**
     * Sets the default scheme that will be automatically added to the user input if it is not included (e.g., if the user types "google.com", it will become "http://google.com").
     *
     * @param string $scheme Default scheme (e.g., 'http' or 'https').
     * @return $this
     */
    public function defaultScheme(string $scheme): static
    {
        $this->_params['defaultScheme'] = $scheme;
        return $this;
    }

    /**
     * Defines a list of URI schemes allowed and considered valid by the system.
     *
     * @param array $schemes List of valid schemes. By default in Yii2 they are ['http', 'https'].
     * @return $this
     */
    public function validSchemes(array $schemes): static
    {
        $this->_params['validSchemes'] = $schemes;
        return $this;
    }

    /**
     * Enables or disables support for conversion of internationalized domain names (IDN), allowing special characters in the URL via Punycode encoding.
     *
     * @param bool $enable By default true.
     * @return $this
     */
    public function enableIDN(bool $enable = true): static
    {
        $this->_params['enableIDN'] = $enable;
        return $this;
    }

    /**
     * Determines whether the URL validation rule is skipped when the attribute value is empty.
     *
     * @param bool $skip By default true.
     * @return $this
     */
    public function skipOnEmpty(bool $skip = true): static
    {
        $this->_params['skipOnEmpty'] = $skip;
        return $this;
    }
}