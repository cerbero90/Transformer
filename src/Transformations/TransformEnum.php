<?php namespace Cerbero\Transformer\Transformations;

/**
 * Transform an enumerated value.
 *
 * @author	Andrea Marco Sartori
 */
class TransformEnum extends AbstractTransformation {

	const DELIMITER = '=';

	/**
	 * Apply the transformation.
	 *
	 * @author	Andrea Marco Sartori
	 * @param	array	$params
	 * @return	boolean
	 */
	public function apply(array $params)
	{
		foreach ($params as $param)
		{
			list($searched, $transformed) = explode(static::DELIMITER, $param);

			if($this->value == $searched) return (int) $transformed;
		}
	}

}
