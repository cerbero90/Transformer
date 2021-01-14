<?php

namespace Cerbero\Transformer;

use DateTime;
use PHPUnit\Framework\TestCase;
use Cerbero\Transformer\Transformations\ArrTransformation;
use Cerbero\Transformer\Transformations\BoolTransformation;
use Cerbero\Transformer\Transformations\DateTransformation;
use Cerbero\Transformer\Transformations\DefaultTransformation;
use Cerbero\Transformer\Transformations\EnumTransformation;
use Cerbero\Transformer\Transformations\FloatTransformation;
use Cerbero\Transformer\Transformations\IntTransformation;
use Cerbero\Transformer\Transformations\ObjectTransformation;
use Cerbero\Transformer\Transformations\StringTransformation;

/**
 * The transformation test.
 *
 */
class TransformationTest extends TestCase
{
    /**
     * @test
     */
    public function transformsIntoArray()
    {
        $transformation1 = (new ArrTransformation(null, []))->apply([]);
        $transformation2 = (new ArrTransformation(true, []))->apply([]);
        $transformation3 = (new ArrTransformation(false, []))->apply([]);
        $transformation4 = (new ArrTransformation(0, []))->apply([]);
        $transformation5 = (new ArrTransformation(1, []))->apply([]);
        $transformation6 = (new ArrTransformation(1.1, []))->apply([]);
        $transformation7 = (new ArrTransformation('foo', []))->apply([]);
        $transformation8 = (new ArrTransformation('{"foo":1}', []))->apply([]);

        $this->assertSame([], $transformation1);
        $this->assertSame([true], $transformation2);
        $this->assertSame([false], $transformation3);
        $this->assertSame([0], $transformation4);
        $this->assertSame([1], $transformation5);
        $this->assertSame([1.1], $transformation6);
        $this->assertSame(['foo'], $transformation7);
        $this->assertSame(['foo' => 1], $transformation8);
    }

    /**
     * @test
     */
    public function transformsIntoBoolean()
    {
        $transformation1 = (new BoolTransformation(null, []))->apply([]);
        $transformation2 = (new BoolTransformation(true, []))->apply([]);
        $transformation3 = (new BoolTransformation(false, []))->apply([]);
        $transformation4 = (new BoolTransformation(0, []))->apply([]);
        $transformation5 = (new BoolTransformation(1, []))->apply([]);
        $transformation6 = (new BoolTransformation(1.1, []))->apply([]);
        $transformation7 = (new BoolTransformation('foo', []))->apply([]);
        $transformation8 = (new BoolTransformation('{"foo":1}', []))->apply([]);

        $this->assertFalse($transformation1);
        $this->assertTrue($transformation2);
        $this->assertFalse($transformation3);
        $this->assertFalse($transformation4);
        $this->assertTrue($transformation5);
        $this->assertTrue($transformation6);
        $this->assertTrue($transformation7);
        $this->assertTrue($transformation8);
    }

    /**
     * @test
     */
    public function transformsIntoDate()
    {
        $transformation1 = (new DateTransformation(null, []))->apply([]);
        $transformation2 = (new DateTransformation(false, []))->apply([]);
        $transformation3 = (new DateTransformation('12/22/78', []))->apply([]);
        $transformation4 = (new DateTransformation('12/22/78', []))->apply(['Y-m-d']);
        $transformation5 = (new DateTransformation('12/22/78 00:00:00', []))->apply(['Y-m-d', 'Australia/Melbourne']);
        $transformation6 = (new DateTransformation('2008-6-30', []))->apply([]);
        $transformation7 = (new DateTransformation('2008-6-30', []))->apply(['d/m/Y']);
        $transformation8 = (new DateTransformation('2008-6-30 00:00:00', []))
            ->apply(['d/m/Y H.i.s', 'Europe/Rome', 'Australia/Sydney']);
        $transformation9 = (new DateTransformation('July 1st, 2008', []))->apply([]);
        $transformation10 = (new DateTransformation('July 1st, 2008', []))->apply(['d.m.y']);
        $transformation11 = (new DateTransformation('July 1st, 2008 00:00:00', []))
            ->apply(['d.m.y h:i:s', 'UTC', 'Australia/Brisbane']);

        $this->assertInstanceOf(DateTime::class, $transformation1);
        $this->assertInstanceOf(DateTime::class, $transformation2);
        $this->assertInstanceOf(DateTime::class, $transformation3);
        $this->assertSame('1978-12-22', $transformation4);
        $this->assertSame('1978-12-21', $transformation5);
        $this->assertInstanceOf(DateTime::class, $transformation6);
        $this->assertSame('30/06/2008', $transformation7);
        $this->assertSame('30/06/2008 08.00.00', $transformation8);
        $this->assertInstanceOf(DateTime::class, $transformation9);
        $this->assertSame('01.07.08', $transformation10);
        $this->assertSame('01.07.08 10:00:00', $transformation11);
    }

    /**
     * @test
     */
    public function transformsIntoDefault()
    {
        $transformation1 = (new DefaultTransformation(null, []))->apply(['foo']);
        $transformation2 = (new DefaultTransformation(true, []))->apply(['foo']);
        $transformation3 = (new DefaultTransformation(false, []))->apply(['foo']);
        $transformation4 = (new DefaultTransformation(0, []))->apply(['foo']);
        $transformation5 = (new DefaultTransformation(1, []))->apply(['foo']);
        $transformation6 = (new DefaultTransformation(1.1, []))->apply(['foo']);
        $transformation7 = (new DefaultTransformation('foo', []))->apply(['bar']);
        $transformation8 = (new DefaultTransformation('{"foo":1}', []))->apply(['bar']);

        $this->assertSame('foo', $transformation1);
        $this->assertSame(true, $transformation2);
        $this->assertSame(false, $transformation3);
        $this->assertSame(0, $transformation4);
        $this->assertSame(1, $transformation5);
        $this->assertSame(1.1, $transformation6);
        $this->assertSame('foo', $transformation7);
        $this->assertSame('{"foo":1}', $transformation8);
    }

    /**
     * @test
     */
    public function transformsIntoEnum()
    {
        $transformation1 = (new EnumTransformation(null, []))->apply(['nope=0', 'yay=1', 'maybe=2']);
        $transformation2 = (new EnumTransformation(true, []))->apply(['nope=0', 'yay=1', 'maybe=2']);
        $transformation3 = (new EnumTransformation(false, []))->apply(['nope=0', 'yay=1', 'maybe=2']);
        $transformation4 = (new EnumTransformation(0, []))->apply(['nope=0', 'yay=1', 'maybe=2']);
        $transformation5 = (new EnumTransformation(1, []))->apply(['nope=0', 'yay=1', 'maybe=2']);
        $transformation6 = (new EnumTransformation(1.1, []))->apply(['nope=0', 'yay=1', 'maybe=2']);
        $transformation7 = (new EnumTransformation('foo', []))->apply(['nope=0', 'yay=1', 'maybe=2']);
        $transformation8 = (new EnumTransformation('{"foo":1}', []))->apply(['nope=0', 'yay=1', 'maybe=2']);
        $transformation9 = (new EnumTransformation('nope', []))->apply(['nope=0', 'yay=1', 'maybe=2']);
        $transformation10 = (new EnumTransformation('yay', []))->apply(['nope=0', 'yay=1', 'maybe=2']);
        $transformation11 = (new EnumTransformation('maybe', []))->apply(['nope=0', 'yay=1', 'maybe=2']);

        $this->assertSame(null, $transformation1);
        $this->assertSame(0, $transformation2);
        $this->assertSame(null, $transformation3);
        $this->assertSame(PHP_MAJOR_VERSION < 8 ? 0 : null, $transformation4);
        $this->assertSame(null, $transformation5);
        $this->assertSame(null, $transformation6);
        $this->assertSame(null, $transformation7);
        $this->assertSame(null, $transformation8);
        $this->assertSame(0, $transformation9);
        $this->assertSame(1, $transformation10);
        $this->assertSame(2, $transformation11);
    }

    /**
     * @test
     */
    public function transformsIntoFloat()
    {
        $transformation1 = (new FloatTransformation(null, []))->apply([]);
        $transformation2 = (new FloatTransformation(true, []))->apply([]);
        $transformation3 = (new FloatTransformation(false, []))->apply([]);
        $transformation4 = (new FloatTransformation(0, []))->apply([]);
        $transformation5 = (new FloatTransformation(1, []))->apply([]);
        $transformation6 = (new FloatTransformation(1.1, []))->apply([]);
        $transformation7 = (new FloatTransformation('foo', []))->apply([]);
        $transformation8 = (new FloatTransformation('{"foo":1}', []))->apply([]);
        $transformation9 = (new FloatTransformation('0.9', []))->apply([]);
        $transformation10 = (new FloatTransformation(0.9999, []))->apply([2]);
        $transformation11 = (new FloatTransformation('0.87559', []))->apply([3]);

        $this->assertSame(0.0, $transformation1);
        $this->assertSame(1.0, $transformation2);
        $this->assertSame(0.0, $transformation3);
        $this->assertSame(0.0, $transformation4);
        $this->assertSame(1.0, $transformation5);
        $this->assertSame(1.1, $transformation6);
        $this->assertSame(0.0, $transformation7);
        $this->assertSame(0.0, $transformation8);
        $this->assertSame(0.9, $transformation9);
        $this->assertSame(0.99, $transformation10);
        $this->assertSame(0.875, $transformation11);
    }

    /**
     * @test
     */
    public function transformsIntoInteger()
    {
        $transformation1 = (new IntTransformation(null, []))->apply([]);
        $transformation2 = (new IntTransformation(true, []))->apply([]);
        $transformation3 = (new IntTransformation(false, []))->apply([]);
        $transformation4 = (new IntTransformation(0, []))->apply([]);
        $transformation5 = (new IntTransformation(1, []))->apply([]);
        $transformation6 = (new IntTransformation(1.1, []))->apply([]);
        $transformation7 = (new IntTransformation('foo', []))->apply([]);
        $transformation8 = (new IntTransformation('{"foo":1}', []))->apply([]);
        $transformation9 = (new IntTransformation('0.9', []))->apply([]);
        $transformation10 = (new IntTransformation(0.9999, []))->apply([2]);
        $transformation11 = (new IntTransformation('1.87559', []))->apply([3]);

        $this->assertSame(0, $transformation1);
        $this->assertSame(1, $transformation2);
        $this->assertSame(0, $transformation3);
        $this->assertSame(0, $transformation4);
        $this->assertSame(1, $transformation5);
        $this->assertSame(1, $transformation6);
        $this->assertSame(0, $transformation7);
        $this->assertSame(0, $transformation8);
        $this->assertSame(0, $transformation9);
        $this->assertSame(0, $transformation10);
        $this->assertSame(1, $transformation11);
    }

    /**
     * @test
     */
    public function transformsIntoObject()
    {
        $transformation1 = (new ObjectTransformation(null, []))->apply([]);
        $transformation2 = (new ObjectTransformation(true, []))->apply([]);
        $transformation3 = (new ObjectTransformation(false, []))->apply([]);
        $transformation4 = (new ObjectTransformation(0, []))->apply([]);
        $transformation5 = (new ObjectTransformation(1, []))->apply([]);
        $transformation6 = (new ObjectTransformation(1.1, []))->apply([]);
        $transformation7 = (new ObjectTransformation('foo', []))->apply([]);
        $transformation8 = (new ObjectTransformation('{"foo":1}', []))->apply([]);

        $this->assertEquals((object)[], $transformation1);
        $this->assertEquals((object)['scalar' => true], $transformation2);
        $this->assertEquals((object)['scalar' => false], $transformation3);
        $this->assertEquals((object)['scalar' => 0], $transformation4);
        $this->assertEquals((object)['scalar' => 1], $transformation5);
        $this->assertEquals((object)['scalar' => 1.1], $transformation6);
        $this->assertEquals((object)['scalar' => 'foo'], $transformation7);
        $this->assertEquals((object)['foo' => 1], $transformation8);
    }

    /**
     * @test
     */
    public function transformsIntoString()
    {
        $transformation1 = (new StringTransformation(null, []))->apply([]);
        $transformation2 = (new StringTransformation(true, []))->apply([]);
        $transformation3 = (new StringTransformation(false, []))->apply([]);
        $transformation4 = (new StringTransformation(0, []))->apply([]);
        $transformation5 = (new StringTransformation(1, []))->apply([]);
        $transformation6 = (new StringTransformation(1.1, []))->apply([]);
        $transformation7 = (new StringTransformation('foo', []))->apply([]);
        $transformation8 = (new StringTransformation('{"foo":1}', []))->apply([]);

        $this->assertSame('null', $transformation1);
        $this->assertSame('true', $transformation2);
        $this->assertSame('false', $transformation3);
        $this->assertSame('0', $transformation4);
        $this->assertSame('1', $transformation5);
        $this->assertSame('1.1', $transformation6);
        $this->assertSame('"foo"', $transformation7);
        $this->assertSame('"{\"foo\":1}"', $transformation8);
    }
}
