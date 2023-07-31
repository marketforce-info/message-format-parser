<?php

declare(strict_types=1);

namespace MarketforceInfo\MessageFormatParser\Tests\Integration\Argument\OptionsParser;

use MarketforceInfo\MessageFormatParser\Argument\Format;
use MarketforceInfo\MessageFormatParser\Argument\OptionsParser;
use MarketforceInfo\MessageFormatParser\Token\Pattern;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MarketforceInfo\MessageFormatParser\Argument\OptionsParser
 */
class SelectOrdinalTest extends TestCase
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
            one {#st}
            two {#nd}
            few {#rd}
            other {#th}
        ');

        $this->assertCount(4, $options);
        $this->assertEquals('one', $options[0]->match);
        $this->assertInstanceOf(Pattern::class, $options[0]->expression);
        $this->assertCount(1, $options[0]->expression->children);
        $this->assertEquals('#st', $options[0]->expression->children[0]->value);
    }
}
