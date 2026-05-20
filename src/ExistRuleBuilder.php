<?php

namespace ovargas\fluentrules;

/**
 * ExistRuleBuilder helps build the 'exist' validation rule of Yii2.
 *
 * Maps directly to the `yii\validators\ExistValidator` validator to check whether the attribute value already exists in a database table (Foreign Keys).
 *
 * @author Omar Vargas
 * @since 1.0.0
 */
class ExistRuleBuilder extends RuleBuilder
{
    /**
     * Initializes a new instance to validate the existence of a relation or value in the database.
     *
     * @param Attribute $attribute The attribute associated with the rule.
     * @param string $targetClass The name of the ActiveRecord (Model) class that will be used to search for the value.
     * @param string|array|null $targetAttribute The attribute or columns in the target table where the existence will be searched.
     */
    public function __construct(Attribute $attribute, string $targetClass, string|array|null $targetAttribute)
    {
        parent::__construct($attribute, 'exist');
        $this->targetClass($targetClass);
        $this->targetAttribute($targetAttribute);
        $this->skipOnError(true);
    }

    /**
     * Defines or modifies the target ActiveRecord model where the existence query will be performed.
     *
     * @param string|null $class Fully qualified class name of the model (e.g. `common\models\User::class`).
     * @return $this
     */
    public function targetClass(?string $class): static
    {
        $this->_params['targetClass'] = $class;
        return $this;
    }

    /**
     * Defines the attribute or attribute mapping of the target table that will be used for the search comparison.
     *
     * @param string|array|null $attr Name of the column or associative array mapping.
     * @return $this
     */
    public function targetAttribute(string|array|null $attr): static
    {
        $this->_params['targetAttribute'] = $attr;
        return $this;
    }

    /**
     * Defines the name of a model relation to automatically validate existence based on it.
     *
     * @param string $relation Name of the relation defined in the ActiveRecord.
     * @return $this
     */
    public function targetRelation(string $relation): static
    {
        $this->_params['targetRelation'] = $relation;
        return $this;
    }

    /**
     * Allows applying an additional filter or condition to the search query in the database.
     * It can be a query condition array, an anonymous function, or a scope method name.
     *
     * @param string|array|\Closure $filter Additional filtering criterion.
     * @return $this
     */
    public function filter(string|array|\Closure $filter): static
    {
        $this->_params['filter'] = $filter;
        return $this;
    }

    /**
     * Configures whether the validator should accept an array of input values (Batch existence validation).
     *
     * @param bool $allow Default is true.
     * @return $this
     */
    public function allowArray(bool $allow = true): static
    {
        $this->_params['allowArray'] = $allow;
        return $this;
    }

    /**
     * Configures the logical conjunction that will join the existence conditions when evaluating multiple attributes.
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
     * Forces the verification query to execute directly against the Master database to avoid replication delays in distributed read/write database architectures.
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