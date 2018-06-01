<?php namespace Cerbero\Transformer\Transformations;

/**
 * Transform a value to a float.
 *
 * @author	Andrea Marco Sartori
 */
class TransformFloat extends AbstractTransformation {

	/**
	 * Apply the transformation.
	 *
	 * @author	Andrea Marco Sartori
	 * @param	array	$params
	 * @return	TransformFloat
	 */
	public function apply(array $params)
	{
		$value = (float) $this->value;

		if(isset($params[0]))
		{
			$num = pow(10, $params[0]);

			return floor($value * $num) / $num;
		}

		return $value;
	}

}
