<?php

namespace Cerbero\Transformer\CustomTransformations;

use Cerbero\Transformer\Transformations\AbstractTransformation;

class SampleCustomTransformation extends AbstractTransformation
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
