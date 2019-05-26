<?php

namespace Cerbero\Transformer\Transformations;

/**
 * Transform a value into an enum.
 *
 */
class EnumTransformation extends AbstractTransformation
{
    const DELIMITER = '=';

    /**
     * Apply the transformation
     *
     * @param array $parameters
     * @return mixed
     */
    public function apply(array $parameters)
    {
        foreach ($parameters as $parameter) {
            list($actual, $transformed) = explode(static::DELIMITER, $parameter);
            // die(var_dump($this->value, $actual, $transformed));
            if ($this->value == $actual) {
                return is_numeric($transformed) ? (int)$transformed : $transformed;
            }
        }
    }
}
