<?php namespace Cerbero\Transformer\Transformations;

/**
 * Transform a value to a string.
 *
 * @author	Andrea Marco Sartori
 */
class TransformString extends AbstractTransformation {

	/**
	 * Apply the transformation.
	 *
	 * @author	Andrea Marco Sartori
	 * @param	array	$params
	 * @return	TransformString
	 */
	public function apply(array $params)
	{
		$value = json_encode($this->value);

		return is_string($value) ? $value : (string) $this->value;
	}

}
