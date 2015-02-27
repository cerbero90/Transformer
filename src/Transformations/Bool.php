<?php namespace Cerbero\Transformer\Transformations;

/**
 * Transform a value to a boolean.
 *
 * @author	Andrea Marco Sartori
 */
class Bool extends AbstractTransformation {

	/**
	 * Apply the transformation.
	 *
	 * @author	Andrea Marco Sartori
	 * @param	array	$params
	 * @return	boolean
	 */
	public function apply(array $params)
	{
		return (bool) $this->value;
	}

}