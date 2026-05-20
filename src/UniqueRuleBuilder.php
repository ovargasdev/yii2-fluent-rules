<?php

namespace ovargas\fluentrules;

/**
 * UniqueRuleBuilder helps build the 'unique' validation rule of Yii2.
 *
 * Maps directly to the validator `yii\validators\UniqueValidator` to verify
 * that the attribute value is unique and not repeated in the target database table.
 *
 * @author Omar Vargas
 * @since 1.0.0
 */
class UniqueRuleBuilder extends RuleBuilder
{
    /**
     * Initializes a new instance for database uniqueness checking.
     *
     * @param Attribute $attribute The attribute associated with the rule.
     * @param string|null $targetClass Fully qualified class name of the optional ActiveRecord model where the collision will be searched.
     */
    public function __construct(Attribute $attribute, ?string $targetClass = null)
    {
        parent::__construct($attribute, 'unique');
        if ($targetClass !== null) {
            $this->targetClass($targetClass);
        }
    }

    /**
     * Defines or modifies the target ActiveRecord model where the uniqueness query will be performed.
     *
     * @param string|null $class Fully qualified class name of the model (e.g., `common\models\User::class`).
     * @return $this
     */
    public function targetClass(?string $class): static
    {
        $this->_params['targetClass'] = $class;
        return $this;
    }

    /**
     * Defines the attribute or attribute mapping of the target table that will be used to compare for collision search.
     *
     * @param string|array|null $attr Column name or associative array mapping for composite uniqueness.
     * @return $this
     */
    public function targetAttribute(string|array|null $attr): static
    {
        $this->_params['targetAttribute'] = $attr;
        return $this;
    }

    /**
     * Allows applying an additional filter or condition to the uniqueness query in the database
     * (e.g., checking uniqueness only for users with active status).
     *
     * @param string|array|\Closure $filter Additional filtering criteria for the query.
     * @return $this
     */
    public function filter(string|array|\Closure $filter): static
    {
        $this->_params['filter'] = $filter;
        return $this;
    }

    /**
     * Sets a custom error message for when a combination of multiple attributes fails the composite uniqueness rule.
     *
     * @param string $message Custom error message.
     * @return $this
     */
    public function comboNotUnique(string $message): static
    {
        $this->_params['comboNotUnique'] = $message;
        return $this;
    }

    /**
     * Configures the logical conjunction that will join the search conditions when evaluating multiple columns.
     *
     * @param string $junction Valid values: 'and' or 'or'.
     * @return $this
     */
    public function targetAttributeJunction(string $junction): static
    {
        $this->_params['targetAttributeJunction'] = $junction;
        return $this;
    }

    /**
     * Forces the uniqueness verification query to execute against the Master database server to mitigate collisions due to replication delays.
     *
     * @param bool $force Default is true.
     * @return $this
     */
    public function forceMasterDb(bool $force = true): static
    {
        $this->_params['forceMasterDb'] = $force;
        return $this;
    }
}