<?php

namespace ovargas\fluentrules;

/**
 * FilterRuleBuilder helps configure the 'filter' validation rule of Yii2.
 *
 * Maps directly to the validator `yii\validators\FilterValidator`. Unlike others,
 * this validator does not generate errors, but modifies the attribute value by
 * applying a transformation or cleaning function (data sanitization) before
 * other validations.
 *
 * @author Omar Vargas
 * @since 1.0.0
 */
class FilterRuleBuilder extends RuleBuilder
{
    /**
     * Initializes a new instance for data filtering and transformation.
     *
     * By default, the native validator has 'skipOnEmpty' set to false, a behavior
     * that is initially replicated in this class's parameters.
     *
     * @param Attribute $attribute The attribute to which the rule is applied.
     * @param callable|string|null $filter The filtering function or callback
     * (e.g., 'trim', anonymous functions, etc.).
     */
    public function __construct(Attribute $attribute, mixed $filter = null)
    {
        parent::__construct($attribute, 'filter');

        $this->_params['skipOnEmpty'] = false;

        if ($filter !== null) {
            $this->filter($filter);
        }
    }

    /**
     * Defines the function or callback that will process and modify the value.
     *
     * @param callable|string $callback Native PHP function, static method, or
     * anonymous function.
     * @return $this
     */
    public function filter(callable|string $callback): static
    {
        $this->_params['filter'] = $callback;
        return $this;
    }

    /**
     * Configures whether the filter should be ignored if the input value is an array.
     *
     * @param bool $skip Default is true.
     * @return $this
     */
    public function skipOnArray(bool $skip = true): static
    {
        $this->_params['skipOnArray'] = $skip;
        return $this;
    }

    /**
     * Determines whether the filter should be omitted when the attribute value is empty.
     *
     * Important note: It is vital to set this to `true` if you use native PHP 8.1+
     * functions that no longer accept null values implicitly (such as trim()).
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