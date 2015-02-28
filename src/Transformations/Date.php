<?php namespace Cerbero\Transformer\Transformations;

use DateTime;

/**
 * Transform a value to a DateTime instance or a formatted date.
 *
 * @author	Andrea Marco Sartori
 */
class Date extends AbstractTransformation {

	/**
	 * Apply the transformation.
	 *
	 * @author	Andrea Marco Sartori
	 * @param	array	$params
	 * @return	DateTime|string
	 */
	public function apply(array $params)
	{
		$date = $this->createDate();

		if( ! isset($params[0])) return $date;

		return $date->format($params[0]);
	}

	/**
	 * Retrieve an instance of DateTime.
	 *
	 * @author	Andrea Marco Sartori
	 * @return	DateTime
	 */
	protected function createDate()
	{
		if($this->value instanceof DateTime)
		{
			return $this->value;
		}

		return new DateTime($this->value);
	}

}
