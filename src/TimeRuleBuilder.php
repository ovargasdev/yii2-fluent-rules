<?php

namespace ovargas\fluentrules;

use yii\validators\DateValidator;

/**
 * TimeRuleBuilder helps build the validation rule for time fields (Time).
 *
 * It reuses Yii2's `yii\validators\DateValidator` by internally configuring the type to [[DateValidator::TYPE_TIME]] to process strict hour, minute, and second formats.
 *
 * @author Omar Vargas
 * @since 1.0.0
 */
class TimeRuleBuilder extends RuleBuilder
{
    /**
     * Initializes a new instance for hour and time validation.
     *
     * @param Attribute $attribute The attribute associated with the rule.
     * @param string|null $format Expected time format (e.g., 'H:i:s').
     */
    public function __construct(Attribute $attribute, ?string $format = null)
    {
        parent::__construct($attribute, 'date');
        $this->_params['type'] = DateValidator::TYPE_TIME;

        if ($format !== null) {
            $this->format($format);
        }
    }

    /**
     * Specifies the date/time format that the input value must conform to.
     *
     * @param string $format ICU-based format or PHP format prefixed with 'php:'.
     * @return $this
     */
    public function format(string $format): static
    {
        $this->_params['format'] = $format;
        return $this;
    }

    /**
     * Defines the locale (region/language) that will be used to parse the time string.
     *
     * @param string $locale Locale identifier (e.g., 'es_ES', 'en_US').
     * @return $this
     */
    public function locale(string $locale): static
    {
        $this->_params['locale'] = $locale;
        return $this;
    }

    /**
     * Defines the time zone that will be used to interpret the time value provided by the user.
     *
     * @param string $tz Time zone identifier (e.g., 'America/Caracas', 'UTC').
     * @return $this
     */
    public function timeZone(string $tz): static
    {
        $this->_params['timeZone'] = $tz;
        return $this;
    }

    /**
     * Defines the name of a model attribute that will receive the time value converted to a UNIX timestamp.
     *
     * @param string $attr Name of the destination attribute.
     * @return $this
     */
    public function timestampAttribute(string $attr): static
    {
        $this->_params['timestampAttribute'] = $attr;
        return $this;
    }

    /**
     * Specifies the format in which the value will be stored within [[timestampAttribute]].
     *
     * @param string $format Target format.
     * @return $this
     */
    public function timestampAttributeFormat(string $format): static
    {
        $this->_params['timestampAttributeFormat'] = $format;
        return $this;
    }

    /**
     * Defines the time zone that will be applied when generating the value for [[timestampAttribute]].
     *
     * @param string $tz Target time zone identifier.
     * @return $this
     */
    public function timestampAttributeTimeZone(string $tz): static
    {
        $this->_params['timestampAttributeTimeZone'] = $tz;
        return $this;
    }

    /**
     * Sets the minimum allowed time value (lower bound).
     *
     * @param int|string $value Integer timestamp or human-readable time string.
     * @return $this
     */
    public function min(int|string $value): static
    {
        $this->_params['min'] = $value;
        return $this;
    }

    /**
     * Sets the maximum allowed time value (upper bound).
     *
     * @param int|string $value Integer timestamp or human-readable time string.
     * @return $this
     */
    public function max(int|string $value): static
    {
        $this->_params['max'] = $value;
        return $this;
    }

    /**
     * Defines a custom error message for when the value is less than [[min]].
     *
     * @param string $message Error message.
     * @return $this
     */
    public function tooSmall(string $message): static
    {
        $this->_params['tooSmall'] = $message;
        return $this;
    }

    /**
     * Defines a custom error message for when the value is greater than [[max]].
     *
     * @param string $message Error message.
     * @return $this
     */
    public function tooBig(string $message): static
    {
        $this->_params['tooBig'] = $message;
        return $this;
    }

    /**
     * Internal Yii2 attribute that stores the string representation of the minimum value.
     *
     * @param string $value Minimum time expression as a string.
     * @return $this
     */
    public function minString(string $value): static
    {
        $this->_params['minString'] = $value;
        return $this;
    }

    /**
     * Internal Yii2 attribute that stores the string representation of the maximum value.
     *
     * @param string $value Maximum time expression as a string.
     * @return $this
     */
    public function maxString(string $value): static
    {
        $this->_params['maxString'] = $value;
        return $this;
    }

    /**
     * Configures whether the time format should be validated strictly without allowing parsing tolerances.
     *
     * @param bool $strict Default is true.
     * @return $this
     */
    public function strictDateFormat(bool $strict = true): static
    {
        $this->_params['strictDateFormat'] = $strict;
        return $this;
    }

    /**
     * Defines the application's default time zone if none is specified in [[timeZone]].
     *
     * @param string $tz Default time zone identifier.
     * @return $this
     */
    public function defaultTimeZone(string $tz): static
    {
        $this->_params['defaultTimeZone'] = $tz;
        return $this;
    }
}