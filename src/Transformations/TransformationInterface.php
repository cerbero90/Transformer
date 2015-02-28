<?php namespace Cerbero\Transformer\Transformations;

/**
 * Interface for transformations.
 *
 * @author	Andrea Marco Sartori
 */
interface TransformationInterface {

	/**
	 * Apply the transformation.
	 *
	 * @author	Andrea Marco Sartori
	 * @param	array	$params
	 * @return	mixed
	 */
	public function apply(array $params);

}
