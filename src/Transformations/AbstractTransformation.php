<?php namespace Cerbero\Transformer\Transformations;

/**
 * Abstract implementation of a transformation.
 *
 * @author	Andrea Marco Sartori
 */
abstract class AbstractTransformation implements TransformationInterface {

	/**
	 * @author	Andrea Marco Sartori
	 * @var		mixed	$value	Value to transform.
	 */
	protected $value;
	
	/**
	 * Set the value to transform.
	 *
	 * @author	Andrea Marco Sartori
	 * @param	mixed	$value
	 * @return	void
	 */
	public function __construct($value)
	{
		$this->value = $value;
	}

	/**
	 * Apply the transformation.
	 *
	 * @author	Andrea Marco Sartori
	 * @param	array	$params
	 * @return	mixed
	 */
	abstract public function apply(array $params);

}
