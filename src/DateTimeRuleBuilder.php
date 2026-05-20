<?php

namespace ovargas\fluentrules;

use yii\validators\DateValidator;

/**
 * DateTimeRuleBuilder configures validation rules for dates that include time (Timestamp).
 *
 * Maps directly to the `yii\validators\DateValidator` validator of Yii2, configuring it
 * explicitly to validate complete combined date and time structures.
 *
 * @author Omar Vargas
 * @since 1.0.0
 */
class DateTimeRuleBuilder extends RuleBuilder
{
    /**
     * Initializes a new instance of the date and time validator.
     *
     * @param Attribute $attribute The attribute associated with the rule.
     * @param string|null $format The expected date and time format (e.g. 'php:Y-m-d H:i:s').
     */
    public function __construct(Attribute $attribute, ?string $format = null)
    {
        parent::__construct($attribute, 'date');
        $this->_params['type'] = DateValidator::TYPE_DATETIME;

        if ($format !== null) {
            $this->format($format);
        }
    }

    /**
     * Specifies the format in which the date and time is expected to be received.
     * It can use ICU format (e.g. 'yyyy-MM-dd HH:mm:ss') or the 'php:' prefix (e.g. 'php:Y-m-d H:i:s').
     *
     * @param string $format The format pattern.
     * @return $this
     */
    public function format(string $format): static
    {
        $this->_params['format'] = $format;
        return $this;
    }

    /**
     * Defines the locale that will be used to parse the text string.
     *
     * @param string $locale Locale identifier (e.g. 'es_ES', 'en_US').
     * @return $this
     */
    public function locale(string $locale): static
    {
        $this->_params['locale'] = $locale;
        return $this;
    }

    /**
     * Specifies the source time zone that will be used to interpret the received value.
     *
     * @param string $tz Time zone name (e.g. 'America/Caracas', 'UTC').
     * @return $this
     */
    public function timeZone(string $tz): static
    {
        $this->_params['timeZone'] = $tz;
        return $this;
    }

    /**
     * Defines the name of the target attribute that will receive the parsed value in UNIX timestamp format.
     *
     * @param string $attr Target attribute name.
     * @return $this
     */
    public function timestampAttribute(string $attr): static
    {
        $this->_params['timestampAttribute'] = $attr;
        return $this;
    }

    /**
     * Defines the specific format that will be applied when saving the value in [[timestampAttribute]].
     *
     * @param string $format Destination format (e.g. 'php:Y-m-d'). Defaults to storing a UNIX integer.
     * @return $this
     */
    public function timestampAttributeFormat(string $format): static
    {
        $this->_params['timestampAttributeFormat'] = $format;
        return $this;
    }

    /**
     * Specifies the time zone that will be applied to the value saved in [[timestampAttribute]].
     *
     * @param string $tz Destination time zone name.
     * @return $this
     */
    public function timestampAttributeTimeZone(string $tz): static
    {
        $this->_params['timestampAttributeTimeZone'] = $tz;
        return $this;
    }

    /**
     * Sets the lower bound of the allowed date and time. It can be an integer (timestamp) or a string representing the date/time.
     *
     * @param int|string $value Minimum limit.
     * @return $this
     */
    public function min(int|string $value): static
    {
        $this->_params['min'] = $value;
        return $this;
    }

    /**
     * Sets the upper bound of the allowed date and time. It can be an integer (timestamp) or a string representing the date/time.
     *
     * @param int|string $value Maximum limit.
     * @return $this
     */
    public function max(int|string $value): static
    {
        $this->_params['max'] = $value;
        return $this;
    }

    /**
     * Defines a custom error message if the entered value is less than the [[min]] limit.
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
     * Defines a custom error message if the entered value is greater than the [[max]] limit.
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
     * Defines a readable string that will replace the [[min]] value within error messages.
     *
     * @param string $value Representative label for the minimum limit.
     * @return $this
     */
    public function minString(string $value): static
    {
        $this->_params['minString'] = $value;
        return $this;
    }

    /**
     * Defines a readable string that will replace the [[max]] value within error messages.
     *
     * @param string $value Representative label for the maximum limit.
     * @return $this
     */
    public function maxString(string $value): static
    {
        $this->_params['maxString'] = $value;
        return $this;
    }

    /**
     * Indicates whether a strict check of the input format should be enforced.
     *
     * @param bool $strict Defaults to true.
     * @return $this
     */
    public function strictDateFormat(bool $strict = true): static
    {
        $this->_params['strictDateFormat'] = $strict;
        return $this;
    }

    /**
     * Defines the default time zone that will be used if the global application does not have one configured.
     *
     * @param string $tz Default time zone name.
     * @return $this
     */
    public function defaultTimeZone(string $tz): static
    {
        $this->_params['defaultTimeZone'] = $tz;
        return $this;
    }
}