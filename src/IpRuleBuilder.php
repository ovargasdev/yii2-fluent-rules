<?php

namespace ovargas\fluentrules;

/**
 * IpRuleBuilder assists in building the 'ip' validation rule of Yii2.
 *
 * Maps directly to the `yii\validators\IpValidator` to verify whether the attribute contains a valid IP address,
 * allowing filtering configurations by protocol version, CIDR routing, and specific network ranges.
 *
 * @author Omar Vargas
 * @since 1.0.0
 */
class IpRuleBuilder extends RuleBuilder
{
    /**
     * Initializes a new instance for IP address validation.
     *
     * @param Attribute $attribute The attribute associated with the rule.
     */
    public function __construct(Attribute $attribute)
    {
        parent::__construct($attribute, 'ip');
    }

    /**
     * Defines whether IPv4 addresses are allowed.
     *
     * @param bool $allow Default is true.
     * @return $this
     */
    public function ipv4(bool $allow = true): static
    {
        $this->_params['ipv4'] = $allow;
        return $this;
    }

    /**
     * Defines whether IPv6 addresses are allowed.
     *
     * @param bool $allow Default is true.
     * @return $this
     */
    public function ipv6(bool $allow = true): static
    {
        $this->_params['ipv6'] = $allow;
        return $this;
    }

    /**
     * Configures whether CIDR subnet notation (e.g., "192.168.1.0/24") is allowed.
     * If set to `false`, only flat IPs will be accepted.
     *
     * @param bool $allowSubnet Default is true.
     * @return $this
     */
    public function subnet(bool $allowSubnet = true): static
    {
        $this->_params['subnet'] = $allowSubnet;
        return $this;
    }

    /**
     * Indicates whether the initial negation character (!) is allowed to invert the matching of allowed IP ranges.
     *
     * @param bool $allowNegation Default is true.
     * @return $this
     */
    public function negation(bool $allowNegation = true): static
    {
        $this->_params['negation'] = $allowNegation;
        return $this;
    }

    /**
     * Defines a list of IP ranges, subnets, or explicit aliases that will be validated by the rule.
     * Valid Yii2 aliases include: 'any', 'private', 'public', etc.
     *
     * @param array $ranges List of allowed or denied ranges.
     * @return $this
     */
    public function ranges(array $ranges): static
    {
        $this->_params['ranges'] = $ranges;
        return $this;
    }
}