<?php

namespace Cerbero\Transformer\Transformations;

/**
 * Transform a value into a float.
 *
 */
class FloatTransformation extends AbstractTransformation
{
    /**
     * Apply the transformation
     *
     * @param array $parameters
     * @return mixed
     */
    public function apply(array $parameters)
    {
        $value = floatval($this->value);

        if (!isset($parameters[0])) {
            return $value;
        }

        $exponent = pow(10, $parameters[0]);

        return floor($value * $exponent) / $exponent;
    }
}
