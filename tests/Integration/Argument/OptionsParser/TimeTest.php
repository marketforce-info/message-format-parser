<?php
declare(strict_types=1);

namespace Integration\Argument\OptionsParser;

use MarketforceInfo\MessageFormatParser\Argument\Format;
use MarketforceInfo\MessageFormatParser\Argument\OptionsParser;
use MarketforceInfo\MessageFormatParser\Exceptions\SyntaxException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MarketforceInfo\MessageFormatParser\Argument\OptionsParser
 */
class TimeTest extends TestCase
{
    private OptionsParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new OptionsParser(Format::time);
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
        $this->parser->parse('small invalid');
    }

    /**
     * @dataProvider validStylesDataProvider
     */
    public function testValidStyles(string $style)
    {
        $options = $this->parser->parse($style);
        $this->assertCount(1, $options);
        $this->assertSame($style, $options[0]->value);
    }

    public function validStylesDataProvider(): array
    {
        return [
            ['short'],
            ['long'],
            ['full'],
        ];
    }
}
