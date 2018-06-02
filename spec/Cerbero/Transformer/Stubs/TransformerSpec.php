<?php

namespace spec\Cerbero\Transformer\Stubs;

use PhpSpec\ObjectBehavior;

class TransformerSpec extends ObjectBehavior
{
    /**
     * @author    Andrea Marco Sartori
     * @var       array    $value    Value to transform.
     */
    protected $value = [
        'some' => [
            'nested' => ['property' => 'hello'],
        ],
        'color' => 'blue',
        'adminPermission' => '1',
        'status1' => 'pending',
        'status2' => 'accepted',
        'status3' => 'denied',
        'dates' => [
            'registration' => '2015-01-01T12:00:00+01:00'
        ],
        'custom' => 'baz',
        'integer' => '22',
        'float' => '0.00500',
        'float2' => 0.089,
        'string' => 2015,
        'array' => 500,
    ];

    /**
     * @author    Andrea Marco Sartori
     * @var       array    $expected    Transformed value.
     */
    protected $expected = [
        'property' => 'hello',
        'very' => [
            'nested' => ['value' => 'blue']
        ],
        'is_admin' => true,
        'statuses' => [2, 1, 0],
        'regDate' => '2015-01-01',
        'custom' => 'foo_bar_baz',
        'integer' => 22,
        'float' => 0.005,
        'float2' => 0.08,
        'string' => '2015',
        'array' => [500],
    ];

    public function it_is_initializable()
    {
        $this->shouldHaveType('Cerbero\Transformer\Stubs\Transformer');
    }

    /**
     * @testdox	It retrieves a collection if the value is a multidimensional array.
     *
     * @author	Andrea Marco Sartori
     * @return	void
     */
    public function it_retrieves_a_collection_if_the_value_is_a_multidimensional_array()
    {
        $this->transform([$this->value])->shouldHaveType('Illuminate\Support\Collection');
    }

    /**
     * @testdox    It retrieves the expected transformed value.
     *
     * @author    Andrea Marco Sartori
     * @return    void
     */
    public function it_retrieves_the_expected_transformed_value()
    {
        $this->transform($this->value)->shouldReturn($this->expected);
    }

    /**
     * @testdox    It transforms objects too.
     *
     * @author    Andrea Marco Sartori
     * @return    void
     */
    public function it_transforms_objects_too()
    {
        $this->transform((object) $this->value)->shouldReturn($this->expected);
    }

    /**
     * @testdox    It transforms multidimensional arrays.
     *
     * @author    Andrea Marco Sartori
     * @return    void
     */
    public function it_transforms_multidimensional_arrays()
    {
        $this->transform([$this->value, $this->value])->toArray()->shouldBe([$this->expected, $this->expected]);
    }
}
