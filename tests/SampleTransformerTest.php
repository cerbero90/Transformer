<?php

namespace Cerbero\Transformer;

use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * The sample transformer test.
 *
 */
class SampleTransformerTest extends TestCase
{
    /**
     * @test
     */
    public function cannotInstantiateWithInvalidData()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Only objects or arrays can be transformed.');

        new SampleTransformer(100);
    }

    /**
     * @test
     */
    public function cannotInstantiateStaticallyWithInvalidData()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Only objects or arrays can be transformed.');

        SampleTransformer::from('foo');
    }
    /**
     * @test
     */
    public function canInstantiateWithValidData()
    {
        $transformer1 = new SampleTransformer([]);
        $transformer2 = new SampleTransformer(new stdClass);

        $this->assertInstanceOf(SampleTransformer::class, $transformer1);
        $this->assertInstanceOf(SampleTransformer::class, $transformer2);
    }

    /**
     * @test
     */
    public function canInstantiateStaticallyWithValidData()
    {
        $transformer1 = SampleTransformer::from([]);
        $transformer2 = SampleTransformer::from(new stdClass);

        $this->assertInstanceOf(SampleTransformer::class, $transformer1);
        $this->assertInstanceOf(SampleTransformer::class, $transformer2);
    }

    /**
     * @test
     */
    public function cannotTransformIntoInvalidData()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Unable to transform data into the given value.');

        SampleTransformer::from([])->transformInto(10);
    }

    /**
     * @test
     */
    public function cannotTransformInvalidData()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Only objects or associative arrays can be transformed.');

        SampleTransformer::from([1, 2, 3])->transform();
    }

    /**
     * @test
     */
    public function cannotTransformInvalidDataIntoObject()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Only objects or associative arrays can be transformed.');

        SampleTransformer::from([1, 2, 3])->transformInto(new stdClass);
    }

    /**
     * @test
     */
    public function cannotTransformWithUnexistingTransformation()
    {
        $this->expectException('BadMethodCallException');
        $this->expectExceptionMessage('Unable to call the transformation un3x1st1ngTr4nsf0rm4t10n');

        SampleTransformer::from(['foo' => 'bar'])
            ->overrideStructure(['foo' => 'foo un3x1st1ngTr4nsf0rm4t10n'])
            ->transform();
    }

    /**
     * @test
     */
    public function cannotTransformWithInvalidTransformation()
    {
        $this->expectException('BadMethodCallException');
        $this->expectExceptionMessage(
            'Custom transformations need to implement Cerbero\Transformer\Transformations\AbstractTransformation'
        );

        SampleTransformer::from(['foo' => 'bar'])
            ->overrideStructure(['foo' => 'foo invalidCustom'])
            ->transform();
    }

    /**
     * @test
     */
    public function cannotTransformWithInvalidCallableTransformation()
    {
        $this->expectException('BadMethodCallException');
        $this->expectExceptionMessage(
            'Unable to call Illuminate\Support\Str::start: ' .
                'Too few arguments to function Illuminate\Support\Str::start(), 1 passed and exactly 2 expected'
        );

        SampleTransformer::from(['foo' => 'bar'])
            ->overrideStructure(['foo' => 'foo Illuminate\Support\Str::start'])
            ->transform();
    }

    /**
     * @test
     */
    public function canTransformArray()
    {
        $data = [
            'foo' => 'foo',
            'money' => '$100.168',
            'nested' => [
                'key' => '25',
            ],
            'excluded' => 123.4,
            'name' => 'Cerbero Transformer',
            'greet' => 'hello world',
            'qux' => 'string',
            'chars' => 'abc',
        ];

        $expected = [
            'foo' => 'foo',
            'money' => 100.16,
            'key' => 25,
            'new_key' => 'bar',
            'package' => [
                'name' => 'CT',
            ],
            'baz' => ' world',
            'QUX' => 'STRING',
            'mid_char' => 'b',
        ];

        $actual = SampleTransformer::from($data)->transform();

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function canTransformCollectionOfArrays()
    {
        $data = [
            [
                'foo' => 'foo',
                'money' => '$100.168',
                'nested' => [
                    'key' => '25',
                ],
                'excluded' => 123.4,
                'name' => 'Cerbero Transformer',
                'greet' => 'hello world',
                'qux' => 'string',
                'chars' => 'abc',
            ],
            [
                'foo' => 'oof',
                'money' => '$0.9999',
                'nested' => [
                    'key' => '41.7',
                ],
                'excluded' => 888,
                'name' => 'One Two Three',
                'greet' => 'hello people',
                'qux' => 'make_me_upper',
                'chars' => 'goal',
            ],
        ];

        $expected = [
            [
                'foo' => 'foo',
                'money' => 100.16,
                'key' => 25,
                'new_key' => 'bar',
                'package' => [
                    'name' => 'CT',
                ],
                'baz' => ' world',
                'QUX' => 'STRING',
                'mid_char' => 'b',
            ],
            [
                'foo' => 'oof',
                'money' => 0.99,
                'key' => 41,
                'new_key' => 'bar',
                'package' => [
                    'name' => 'OTT',
                ],
                'baz' => ' people',
                'QUX' => 'MAKE_ME_UPPER',
                'mid_char' => 'a',
            ]
        ];

        $actual = SampleTransformer::from($data)->transform();

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function canTransformObject()
    {
        $data = (object)[
            'foo' => 'foo',
            'money' => '$100.168',
            'nested' => (object)[
                'key' => '25',
            ],
            'excluded' => 123.4,
            'name' => 'Cerbero Transformer',
            'greet' => 'hello world',
            'qux' => 'string',
            'chars' => 'abc',
        ];

        $expected = [
            'foo' => 'foo',
            'money' => 100.16,
            'key' => 25,
            'new_key' => 'bar',
            'package' => [
                'name' => 'CT',
            ],
            'baz' => ' world',
            'QUX' => 'STRING',
            'mid_char' => 'b',
        ];

        $actual = SampleTransformer::from($data)->transform();

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function canTransformCollectionOfObjects()
    {
        $data = [
            (object)[
                'foo' => 'foo',
                'money' => '$100.168',
                'nested' => (object)[
                    'key' => '25',
                ],
                'excluded' => 123.4,
                'name' => 'Cerbero Transformer',
                'greet' => 'hello world',
                'qux' => 'string',
                'chars' => 'abc',
            ],
            (object)[
                'foo' => 'oof',
                'money' => '$0.9999',
                'nested' => (object)[
                    'key' => '41.7',
                ],
                'excluded' => 888,
                'name' => 'One Two Three',
                'greet' => 'hello people',
                'qux' => 'make_me_upper',
                'chars' => 'goal',
            ],
        ];

        $expected = [
            [
                'foo' => 'foo',
                'money' => 100.16,
                'key' => 25,
                'new_key' => 'bar',
                'package' => [
                    'name' => 'CT',
                ],
                'baz' => ' world',
                'QUX' => 'STRING',
                'mid_char' => 'b',
            ],
            [
                'foo' => 'oof',
                'money' => 0.99,
                'key' => 41,
                'new_key' => 'bar',
                'package' => [
                    'name' => 'OTT',
                ],
                'baz' => ' people',
                'QUX' => 'MAKE_ME_UPPER',
                'mid_char' => 'a',
            ]
        ];

        $actual = SampleTransformer::from($data)->transform();

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function canTransformArrayIntoObject()
    {
        $data = [
            'foo' => 'foo',
            'money' => '$100.168',
            'nested' => [
                'key' => '25',
            ],
            'excluded' => 123.4,
            'name' => 'Cerbero Transformer',
            'greet' => 'hello world',
            'qux' => 'string',
            'chars' => 'abc',
        ];

        $expected = new SampleObject([
            'foo' => 'foo',
            'money' => 100.16,
            'key' => 25,
            'new_key' => 'bar',
            'package' => [
                'name' => 'CT',
            ],
            'baz' => ' world',
            'QUX' => 'STRING',
            'mid_char' => 'b',
        ]);

        $actual = SampleTransformer::from($data)->transformInto(new SampleObject);

        $this->assertEquals($expected, $actual);
        $this->assertInstanceOf(SampleObject::class, $actual);
    }

    /**
     * @test
     */
    public function canTransformCollectionOfArraysIntoObject()
    {
        $data = [
            [
                'foo' => 'foo',
                'money' => '$100.168',
                'nested' => [
                    'key' => '25',
                ],
                'excluded' => 123.4,
                'name' => 'Cerbero Transformer',
                'greet' => 'hello world',
                'qux' => 'string',
                'chars' => 'abc',
            ],
            [
                'foo' => 'oof',
                'money' => '$0.9999',
                'nested' => [
                    'key' => '41.7',
                ],
                'excluded' => 888,
                'name' => 'One Two Three',
                'greet' => 'hello people',
                'qux' => 'make_me_upper',
                'chars' => 'goal',
            ],
        ];

        $expected = [
            new SampleObject([
                'foo' => 'foo',
                'money' => 100.16,
                'key' => 25,
                'new_key' => 'bar',
                'package' => [
                    'name' => 'CT',
                ],
                'baz' => ' world',
                'QUX' => 'STRING',
                'mid_char' => 'b',
            ]),
            new SampleObject([
                'foo' => 'oof',
                'money' => 0.99,
                'key' => 41,
                'new_key' => 'bar',
                'package' => [
                    'name' => 'OTT',
                ],
                'baz' => ' people',
                'QUX' => 'MAKE_ME_UPPER',
                'mid_char' => 'a',
            ]),
        ];

        $actual = SampleTransformer::from($data)->transformInto(new SampleObject);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function canTransformObjectIntoObject()
    {
        $data = (object)[
            'foo' => 'foo',
            'money' => '$100.168',
            'nested' => (object)[
                'key' => '25',
            ],
            'excluded' => 123.4,
            'name' => 'Cerbero Transformer',
            'greet' => 'hello world',
            'qux' => 'string',
            'chars' => 'abc',
        ];

        $expected = new SampleObject([
            'foo' => 'foo',
            'money' => 100.16,
            'key' => 25,
            'new_key' => 'bar',
            'package' => [
                'name' => 'CT',
            ],
            'baz' => ' world',
            'QUX' => 'STRING',
            'mid_char' => 'b',
        ]);

        $actual = SampleTransformer::from($data)->transformInto(new SampleObject);

        $this->assertEquals($expected, $actual);
        $this->assertInstanceOf(SampleObject::class, $actual);
    }

    /**
     * @test
     */
    public function canTransformCollectionOfObjectsIntoObject()
    {
        $data = [
            (object)[
                'foo' => 'foo',
                'money' => '$100.168',
                'nested' => (object)[
                    'key' => '25',
                ],
                'excluded' => 123.4,
                'name' => 'Cerbero Transformer',
                'greet' => 'hello world',
                'qux' => 'string',
                'chars' => 'abc',
            ],
            (object)[
                'foo' => 'oof',
                'money' => '$0.9999',
                'nested' => (object)[
                    'key' => '41.7',
                ],
                'excluded' => 888,
                'name' => 'One Two Three',
                'greet' => 'hello people',
                'qux' => 'make_me_upper',
                'chars' => 'goal',
            ],
        ];

        $expected = [
            new SampleObject([
                'foo' => 'foo',
                'money' => 100.16,
                'key' => 25,
                'new_key' => 'bar',
                'package' => [
                    'name' => 'CT',
                ],
                'baz' => ' world',
                'QUX' => 'STRING',
                'mid_char' => 'b',
            ]),
            new SampleObject([
                'foo' => 'oof',
                'money' => 0.99,
                'key' => 41,
                'new_key' => 'bar',
                'package' => [
                    'name' => 'OTT',
                ],
                'baz' => ' people',
                'QUX' => 'MAKE_ME_UPPER',
                'mid_char' => 'a',
            ]),
        ];

        $actual = SampleTransformer::from($data)->transformInto(new SampleObject);

        $this->assertEquals($expected, $actual);
    }
}

class SampleObject
{
    public $foo;
    public $money;
    public $key;
    public $new_key;
    public $package;
    public $baz;
    public $QUX;
    public $mid_char;

    public function __construct(array $parameters = [])
    {
        foreach ($parameters as $parameter => $value) {
            $this->$parameter = $value;
        }
    }
}
