<?php namespace Cerbero\Transformer\Stubs;

use Cerbero\Transformer\AbstractTransformer;

class Transformer extends AbstractTransformer {

	protected function getStructure()
	{
		return [
			'property' => 'some.nested.property',
			'very' => [
				'nested' => ['value' => 'color']
			],
			'is_admin' => 'adminPermission bool',
			'statuses' => [
				'status1 enum:denied=0,accepted=1,pending=2',
				'status2 enum:denied=0,accepted=1,pending=2',
				'status3 enum:denied=0,accepted=1,pending=2',
			],
			'regDate' => 'dates.registration date:Y-m-d',
			'custom' => 'custom custom:foo,bar',
            'integer' => 'integer int',
            'float' => 'float float',
            'float2' => 'float2 float:2',
            'string' => 'string string',
            'array' => 'array arr',
		];
	}

	/**
	 * Custom transformation.
	 *
	 * @author	Andrea Marco Sartori
	 * @param	array	$params
	 * @return	string
	 */
	public function custom($a, $b)
	{
		return "{$a}_{$b}_" . $this->value;
	}

}
