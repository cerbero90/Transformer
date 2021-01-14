<?php

namespace Cerbero\Transformer\CustomTransformations;

class InvalidCustomTransformation
{
    /**
     * Apply the transformation
     *
     * @param array $parameters
     * @return void
     */
    public function apply(array $parameters)
    {
        $position = floor(strlen($this->value) / 2);

        return substr($this->value, $position, 1);
    }
}
