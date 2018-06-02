<?php namespace Cerbero\Transformer\Transformations;

/**
 * Transform a value to an object.
 *
 * @author	Andrea Marco Sartori
 */
class TransformObject extends AbstractTransformation {

	/**
	 * Apply the transformation.
	 *
	 * @author	Andrea Marco Sartori
	 * @param	array	$params
	 * @return	object
	 */
	public function apply(array $params)
	{
		$value = json_decode($this->value);

		return is_object($value) ? $value : (object) $this->value;
	}

}
