<?php

namespace Cerbero\Transformer;

use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    /**
     * @test
     */
    public function parsesKey()
    {
        $parser1 = new Parser(null, null);
        $parser2 = new Parser('foo', null);
        $parser3 = new Parser(null, 'foo');
        $parser4 = new Parser('foo', 'bar');

        $this->assertSame('', $parser1->parseKey());
        $this->assertSame('foo', $parser2->parseKey());
        $this->assertSame('foo', $parser3->parseKey());
        $this->assertSame('bar', $parser4->parseKey());
    }

    /**
     * @test
     */
    public function parsesTransformations()
    {
        $parser1 = new Parser('foo');
        $parser2 = new Parser('foo', 'bar');
        $parser3 = new Parser('foo bar|baz:1|qux:1,2');

        $expected1 = [];
        $expected2 = [
            'foo' => [],
        ];
        $expected3 = [
            'bar' => [],
            'baz' => ['1'],
            'qux' => ['1', '2'],
        ];

        $this->assertSame($expected1, $parser1->parseTransformations());
        $this->assertSame($expected2, $parser2->parseTransformations());
        $this->assertSame($expected3, $parser3->parseTransformations());
    }

    /**
     * @test
     */
    public function detectsTransformations()
    {
        $parser1 = new Parser(null, null);
        $parser2 = new Parser('foo', null);
        $parser3 = new Parser(null, 'foo');
        $parser4 = new Parser('foo', 'bar');
        $parser5 = new Parser('foo', 'bar|baz');

        $this->assertFalse($parser1->hasTransformations());
        $this->assertFalse($parser2->hasTransformations());
        $this->assertFalse($parser3->hasTransformations());
        $this->assertTrue($parser4->hasTransformations());
        $this->assertTrue($parser5->hasTransformations());
    }

    /**
     * @test
     */
    public function parsesTransformationNames()
    {
        $parser = new Parser();

        $this->assertSame('foo', $parser->parseTransformationName('foo'));
        $this->assertSame('foo', $parser->parseTransformationName('foo:1'));
        $this->assertSame('foo::bar', $parser->parseTransformationName('foo::bar:1'));
    }

    /**
     * @test
     */
    public function parsesParameters()
    {
        $parser = new Parser();

        $this->assertSame([], $parser->parseParameters('foo'));
        $this->assertSame(['1'], $parser->parseParameters('foo:1'));
        $this->assertSame(['1', '2'], $parser->parseParameters('foo:1,2'));
    }

    /**
     * @test
     */
    public function detectsParameters()
    {
        $parser = new Parser();

        $this->assertFalse($parser->hasParameters('foo'));
        $this->assertTrue($parser->hasParameters('foo:1'));
        $this->assertTrue($parser->hasParameters('foo:1,2'));
    }
}
