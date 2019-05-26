<?php

namespace Cerbero\Transformer\Transformations;

/**
 * Transform a missing value.
 *
 */
class DefaultTransformation extends AbstractTransformation
{
    /**
     * Apply the transformation
     *
     * @param array $parameters
     * @return mixed
     */
    public function apply(array $parameters)
    {
        return $this->value === null ? $parameters[0] : $this->value;
    }
}
