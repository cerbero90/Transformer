<?php

namespace spec\Cerbero\Transformer;

use PhpSpec\ObjectBehavior;

class ParserSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('foo');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Cerbero\Transformer\Parser');
    }

    /**
     * @testdox	It returns the original string if there are no transformations.
     *
     * @author	Andrea Marco Sartori
     * @return	void
     */
    public function it_returns_the_original_string_if_there_are_no_transformations()
    {
        $this->getKey()->shouldReturn('foo');
    }

    /**
     * @testdox    It returns the custom key if set.
     *
     * @author    Andrea Marco Sartori
     * @return    void
     */
    public function it_returns_the_custom_key_if_set()
    {
        $this->beConstructedWith('foo', 'bar');

        $this->getKey()->shouldReturn('bar');
    }

    /**
     * @testdox    It returns the custom key if set and the string is empty.
     *
     * @author    Andrea Marco Sartori
     * @return    void
     */
    public function it_returns_the_custom_key_if_set_and_the_string_is_empty()
    {
        $this->beConstructedWith('', 'foo');

        $this->getKey()->shouldReturn('foo');
    }

    /**
     * @testdox	It returns the parsed key if there are transformations.
     *
     * @author	Andrea Marco Sartori
     * @return	void
     */
    public function it_returns_the_parsed_key_if_there_are_transformations()
    {
        $this->beConstructedWith('foo.bar bool');

        $this->getKey()->shouldReturn('foo.bar');
    }

    /**
     * @testdox	It returns an empty array if there are no transformations.
     *
     * @author	Andrea Marco Sartori
     * @return	void
     */
    public function it_returns_an_empty_array_if_there_are_no_transformations()
    {
        $this->getTransformations()->shouldReturn([]);
    }

    /**
     * @testdox	It returns the transformations associated to an empty array if no arguments.
     *
     * @author	Andrea Marco Sartori
     * @return	void
     */
    public function it_returns_the_transformations_associated_to_an_empty_array_if_no_arguments()
    {
        $this->beConstructedWith('foo bool');

        $this->getTransformations()->shouldReturn(['bool' => []]);
    }

    /**
     * @testdox	It returns transformations associated to their arguments.
     *
     * @author	Andrea Marco Sartori
     * @return	void
     */
    public function it_returns_transformations_associated_to_their_arguments()
    {
        $this->beConstructedWith('foo date:Y-m-d');

        $this->getTransformations()->shouldReturn(['date' => ['Y-m-d']]);
    }

    /**
     * @testdox	It returns all arguments of a transformation.
     *
     * @author	Andrea Marco Sartori
     * @return	void
     */
    public function it_returns_all_arguments_of_a_transformation()
    {
        $this->beConstructedWith('foo custom:bar,baz');

        $this->getTransformations()->shouldReturn(['custom' => ['bar', 'baz']]);
    }

    /**
     * @testdox	It returns all transformations.
     *
     * @author	Andrea Marco Sartori
     * @return	void
     */
    public function it_returns_all_transformations()
    {
        $this->beConstructedWith('foo bool|date:Y|custom:bar,baz');

        $expected = [
            'bool' => [],
            'date' => ['Y'],
            'custom' => ['bar', 'baz'],
        ];

        $this->getTransformations()->shouldReturn($expected);
    }
}
