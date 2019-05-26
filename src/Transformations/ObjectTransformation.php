<?php

namespace Cerbero\Transformer\Transformations;

/**
 * Transform a value into an object.
 *
 */
class ObjectTransformation extends AbstractTransformation
{
    /**
     * Apply the transformation
     *
     * @param array $parameters
     * @return mixed
     */
    public function apply(array $parameters)
    {
        $value = json_decode($this->value);

        return is_object($value) ? $value : (object)$this->value;
    }
}
