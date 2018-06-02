<?php namespace Cerbero\Transformer\Stubs;

use Cerbero\Transformer\AbstractTransformer;

class CustomTransformer extends AbstractTransformer
{
    protected function getStructure()
    {
        return [
            'property' => null,
            'very' => [
                'nested' => ['value' => null]
            ],
            'is_admin' => 'bool',
            'statuses' => [
                'enum:denied=0,accepted=1,pending=2',
                'enum:denied=0,accepted=1,pending=2',
                'enum:denied=0,accepted=1,pending=2',
            ],
            'regDate' => 'date:Y-m-d',
            'custom' => 'custom:foo,bar',
            'integer' => 'int',
            'float' => 'float',
            'float2' => 'float:2',
            'string' => 'string',
            'array' => 'arr',
        ];
    }

    /**
     * Retrieve custom keys associated to source keys.
     *
     * @author	Andrea Marco Sartori
     * @return	array
     */
    protected function getCustomKeys()
    {
        return [
            'property' => 'some.nested.property',
            'very.nested.value' => 'color',
            'is_admin' => 'adminPermission',
            'statuses.0' => 'status1',
            'statuses.1' => 'status2',
            'statuses.2' => 'status3',
            'regDate' => 'dates.registration',
            'custom' => 'custom',
            'integer' => 'integer',
            'float' => 'float',
            'float2' => 'float2',
            'string' => 'string',
            'array' => 'array',
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
