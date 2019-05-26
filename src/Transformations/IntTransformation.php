<?php

namespace Cerbero\Transformer\Transformations;

/**
 * Transform a value into an integer.
 *
 */
class IntTransformation extends AbstractTransformation
{
    /**
     * Apply the transformation
     *
     * @param array $parameters
     * @return mixed
     */
    public function apply(array $parameters)
    {
        return (int)$this->value;
    }
}
