<?php namespace Cerbero\Transformer\Transformations;

/**
 * Transform a value to an array.
 *
 * @author	Andrea Marco Sartori
 */
class TransformArr extends AbstractTransformation
{
    /**
     * Apply the transformation.
     *
     * @author	Andrea Marco Sartori
     * @param	array	$params
     * @return	array
     */
    public function apply(array $params)
    {
        $value = json_decode($this->value, true);

        return is_array($value) ? $value : (array) $this->value;
    }
}
