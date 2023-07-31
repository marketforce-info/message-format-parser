<?php

namespace MarketforceInfo\MessageFormatParser\Tests\Integration\Argument\OptionsParser;

use MarketforceInfo\MessageFormatParser\Argument\Format;
use MarketforceInfo\MessageFormatParser\Argument\OptionsParser;
use MarketforceInfo\MessageFormatParser\Exceptions\SyntaxException;
use MarketforceInfo\MessageFormatParser\Token\Pattern;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MarketforceInfo\MessageFormatParser\Argument\OptionsParser
 */
class PluralTest extends TestCase
{
    private OptionsParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new OptionsParser(Format::plural);
    }

    public function testFormat()
    {
        $options = $this->parser->parse('
            =0    {There are no apples}
            =1    {There is one apple...}
            other {There are # apples!}
        ');

        $this->assertCount(3, $options);
        $this->assertEquals('=0', $options[0]->match);
        $this->assertInstanceOf(Pattern::class, $options[0]->expression);
        $this->assertCount(1, $options[0]->expression->children);
        $this->assertEquals('There are no apples', $options[0]->expression->children[0]->value);
    }

    public function testRequiresOtherMatch()
    {
        $this->expectException(SyntaxException::class);
        $this->parser->parse('
            =0    {There are no apples}
            =1    {There is one apple...}
        ');
        $this->assertStringContainsString('other', $this->getExpectedExceptionMessage());
    }

    public function testInvalidMatchForEquals()
    {
        $this->expectException(SyntaxException::class);
        $this->parser->parse('
            =X {There are no apples}
            other {There are # apples!}
        ');
    }

    public function testUnknownMatch()
    {
        $this->expectException(SyntaxException::class);
        $this->parser->parse('
            something {There are no apples}
            other {There are # apples!}
        ');
        $this->assertStringContainsString('valid match values', $this->getExpectedExceptionMessage());
    }

    public function testDuplicateMatch()
    {
        $this->expectException(SyntaxException::class);
        $this->parser->parse('
            =0 {There are no apples}
            =0 {There is one apple...}
            other {There are # apples!}
        ');
        $this->assertStringContainsString('duplicate match', $this->getExpectedExceptionMessage());
    }

    public function testMissingExpression()
    {
        $this->expectException(SyntaxException::class);
        $this->parser->parse('
            =0
            other {There are # apples!}
        ');
        $this->assertStringContainsString('missing expression', $this->getExpectedExceptionMessage());
    }

    /**
     * @dataProvider validMatches
     */
    public function testValidMatches(string $match)
    {
        $options = $this->parser->parse("
            $match {There are no apples}
            other {There are # apples!}
        ");
        $this->assertCount(2, $options);
        $this->assertEquals($match, $options[0]->match);
    }

    public function validMatches(): array
    {
        return [
            ['one'],
            ['two'],
            ['few'],
            ['many'],
        ];
    }
}
