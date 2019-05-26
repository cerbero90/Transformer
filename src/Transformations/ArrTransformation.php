<?php

namespace Cerbero\Transformer\Transformations;

/**
 * Transform a value into an array.
 *
 */
class ArrTransformation extends AbstractTransformation
{
    /**
     * Apply the transformation
     *
     * @param array $parameters
     * @return mixed
     */
    public function apply(array $parameters)
    {
        $value = json_decode($this->value, true);

        return is_array($value) ? $value : (array)$this->value;
    }
}
