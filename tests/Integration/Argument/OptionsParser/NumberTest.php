<?php

namespace Integration\Argument\OptionsParser;

use MarketforceInfo\MessageFormatParser\Argument\Format;
use MarketforceInfo\MessageFormatParser\Argument\OptionsParser;
use MarketforceInfo\MessageFormatParser\Exceptions\SyntaxException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MarketforceInfo\MessageFormatParser\Argument\OptionsParser
 */
class NumberTest extends TestCase
{
    private OptionsParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new OptionsParser(Format::number);
    }

    public function testNoFormatting()
    {
        $options = $this->parser->parse('');
        $this->assertSame([], $options);
    }

    public function testInvalid()
    {
        $this->expectException(SyntaxException::class);
        $this->parser->parse('invalid');
    }

    public function testExcessSyntax()
    {
        $this->expectException(SyntaxException::class);
        $this->parser->parse('integer invalid');
    }

    public function testInteger()
    {
        $options = $this->parser->parse('integer');
        $this->assertCount(1, $options);
        $this->assertSame('integer', $options[0]->value);
    }

    public function testPercent()
    {
        $options = $this->parser->parse('percent');
        $this->assertCount(1, $options);
        $this->assertSame('percent', $options[0]->value);
    }

    public function testCurrency()
    {
        $options = $this->parser->parse('currency');
        $this->assertCount(1, $options);
        $this->assertSame('currency', $options[0]->value);
    }
}
