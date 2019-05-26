<?php

namespace Cerbero\Transformer\Transformations;

/**
 * Transform a value into a string.
 *
 */
class StringTransformation extends AbstractTransformation
{
    /**
     * Apply the transformation
     *
     * @param array $parameters
     * @return mixed
     */
    public function apply(array $parameters)
    {
        $value = json_encode($this->value);

        return is_string($value) ? $value : (string)$this->value;
    }
}
