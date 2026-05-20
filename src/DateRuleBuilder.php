<?php

namespace ovargas\fluentrules;

use yii\validators\DateValidator;

/**
 * DateRuleBuilder configures validation rules for dates.
 *
 * Maps directly to the `yii\validators\DateValidator` validator of Yii2, which ensures that the attribute is a date, time, or datetime with a valid format.
 *
 * @author Omar Vargas
 * @since 1.0.0
 */
class DateRuleBuilder extends RuleBuilder
{
    /**
     * Initializes a new instance of the date validator.
     *
     * @param Attribute $attribute The attribute associated with the rule.
     * @param string|null $format The expected date format (e.g., 'php:Y-m-d').
     */
    public function __construct(Attribute $attribute, ?string $format = null)
    {
        parent::__construct($attribute, 'date');
        $this->_params['type'] = DateValidator::TYPE_DATE;

        if ($format !== null) {
            $this->format($format);
        }
    }

    /**
     * Specifies the format in which the date is expected to be received.
     * Can use ICU format (e.g., 'yyyy-MM-dd') or the 'php:' prefix (e.g., 'php:Y-m-d').
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
     * Defines the locale that will be used to parse the date string.
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
     * Specifies the time zone that will be used to parse the date value.
     *
     * @param string $tz Time zone name (e.g., 'America/Caracas', 'UTC').
     * @return $this
     */
    public function timeZone(string $tz): static
    {
        $this->_params['timeZone'] = $tz;
        return $this;
    }

    /**
     * Defines the name of the attribute that will receive the parsed value in UNIX timestamp format.
     * Useful for storing the clean date in another model property.
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
     * Defines the format that will be applied when saving the value in [[timestampAttribute]].
     *
     * @param string $format Target format. Default is a UNIX timestamp integer.
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
     * Sets the minimum allowed date (lower bound).
     * Can be an integer (timestamp) or a string representing the date.
     *
     * @param int|string $value Lower bound of the date.
     * @return $this
     */
    public function min(int|string $value): static
    {
        $this->_params['min'] = $value;
        return $this;
    }

    /**
     * Sets the maximum allowed date (upper bound).
     * Can be an integer (timestamp) or a string representing the date.
     *
     * @param int|string $value Upper bound of the date.
     * @return $this
     */
    public function max(int|string $value): static
    {
        $this->_params['max'] = $value;
        return $this;
    }

    /**
     * Defines a custom error message if the entered date is less than [[min]].
     *
     * @param string $message The error message.
     * @return $this
     */
    public function tooSmall(string $message): static
    {
        $this->_params['tooSmall'] = $message;
        return $this;
    }

    /**
     * Defines a custom error message if the entered date is greater than [[max]].
     *
     * @param string $message The error message.
     * @return $this
     */
    public function tooBig(string $message): static
    {
        $this->_params['tooBig'] = $message;
        return $this;
    }

    /**
     * Defines a readable string that will replace the value of [[min]] in error messages.
     *
     * @param string $value Label or representative text of the minimum limit.
     * @return $this
     */
    public function minString(string $value): static
    {
        $this->_params['minString'] = $value;
        return $this;
    }

    /**
     * Defines a readable string that will replace the value of [[max]] in error messages.
     *
     * @param string $value Label or representative text of the maximum limit.
     * @return $this
     */
    public function maxString(string $value): static
    {
        $this->_params['maxString'] = $value;
        return $this;
    }

    /**
     * Indicates whether a strict format check of the date should be enforced.
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
     * Defines the default time zone that will be used if the application has none configured.
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