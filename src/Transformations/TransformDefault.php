<?php namespace Cerbero\Transformer\Transformations;

/**
 * Transform a value to a string.
 *
 * @author	Andrea Marco Sartori
 */
class TransformDefault extends AbstractTransformation
{
    /**
     * Apply the transformation.
     *
     * @author	Andrea Marco Sartori
     * @param	array	$params
     * @return	string
     */
    public function apply(array $params)
    {
        return empty($this->value) ? $params[0] : $this->value;
    }
}
