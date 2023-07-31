<?php

namespace MarketforceInfo\MessageFormatParser\Tests\Integration;

use MarketforceInfo\MessageFormatParser\Argument\Format;
use MarketforceInfo\MessageFormatParser\Exceptions\SyntaxException;
use MarketforceInfo\MessageFormatParser\Parser;
use MarketforceInfo\MessageFormatParser\Token\Argument;
use MarketforceInfo\MessageFormatParser\Token\Literal;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MarketforceInfo\MessageFormatParser\Parser
 */
class ParserTest extends TestCase
{
    public function testParsesPlainText()
    {
        $parser = new Parser();
        $ast = $parser->parse('Hello World');

        $this->assertCount(1, $ast->children);
        $this->assertInstanceOf(Literal::class, $ast->children[0]);
        $this->assertEquals('Hello World', $ast->children[0]->value);
    }

    public function testParsesArgument()
    {
        $parser = new Parser();
        $ast = $parser->parse('Hello {name}');

        $this->assertCount(2, $ast->children);
        $this->assertInstanceOf(Literal::class, $ast->children[0]);
        $this->assertEquals('Hello ', $ast->children[0]->value);
        $this->assertInstanceOf(Argument::class, $ast->children[1]);
        $this->assertEquals('name', $ast->children[1]->name);
    }

    public function testParsesMultipleArguments()
    {
        $parser = new Parser();
        $ast = $parser->parse('Hello {first} {last}');

        $this->assertCount(4, $ast->children);
        $this->assertEquals('first', $ast->children[1]->name);
        $this->assertEquals('last', $ast->children[3]->name);
    }

    public function testParsesArgumentFormat()
    {
        $parser = new Parser();
        $ast = $parser->parse('You have {count, number} unread messages');

        $this->assertEquals(Format::number, $ast->children[1]->format);
    }

    public function testParsesLiteral()
    {
        $parser = new Parser();
        $ast = $parser->parse("Your name is '{first} {last}'");

        $this->assertCount(1, $ast->children);
        $this->assertEquals("Your name is {first} {last}", $ast->children[0]->value);
    }

    public function testParsesLiteralWithDoubleApostrophe()
    {
        $parser = new Parser();
        $ast = $parser->parse("This '{isn''t}' obvious");

        $this->assertCount(1, $ast->children);
        $this->assertEquals("This {isn't} obvious", $ast->children[0]->value);
    }

    public function testInvalidArgumentNameThrowsException()
    {
        $this->expectException(SyntaxException::class);

        $parser = new Parser();
        $parser->parse('Hello {full name}');
    }

    public function testUnclosedArgumentThrowsException()
    {
        $this->expectException(SyntaxException::class);

        $parser = new Parser();
        $parser->parse('Hello {name');
    }

    public function testInvalidFormatThrowsException()
    {
        $this->expectException(SyntaxException::class);

        $parser = new Parser();
        $parser->parse('You have {count, numberz}');
    }
}
