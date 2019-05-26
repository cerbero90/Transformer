<?php

namespace Cerbero\Transformer\Transformations;

/**
 * Transform a value into a boolean.
 *
 */
class BoolTransformation extends AbstractTransformation
{
    /**
     * Apply the transformation
     *
     * @param array $parameters
     * @return mixed
     */
    public function apply(array $parameters)
    {
        return (bool)$this->value;
    }
}
