<?php namespace Cerbero\Transformer\Transformations;

/**
 * Transform a value to an integer.
 *
 * @author	Andrea Marco Sartori
 */
class TransformInt extends AbstractTransformation {

	/**
	 * Apply the transformation.
	 *
	 * @author	Andrea Marco Sartori
	 * @param	array	$params
	 * @return	integer
	 */
	public function apply(array $params)
	{
		return (int) $this->value;
	}

}
