<?php

namespace Cerbero\Transformer\Transformations;

/**
 * The abstract transformation.
 *
 */
abstract class AbstractTransformation
{
    /**
     * The value to transform.
     *
     * @var mixed
     */
    protected $value;

    /**
     * The item containing the value to transform.
     *
     * @var array|object
     */
    protected $item;

    /**
     * Set the dependencies.
     *
     * @param mixed $value
     * @param array|object $item
     */
    public function __construct($value, $item)
    {
        $this->value = $value;
        $this->item = $item;
    }

    /**
     * Apply the transformation
     *
     * @param array $parameters
     * @return void
     */
    abstract public function apply(array $parameters);
}
