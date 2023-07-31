<?php

namespace Integration\Argument\OptionsParser;

use MarketforceInfo\MessageFormatParser\Argument\Format;
use MarketforceInfo\MessageFormatParser\Argument\OptionsParser;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MarketforceInfo\MessageFormatParser\Argument\OptionsParser
 */
class NoOptionsTest extends TestCase
{
    public function testNoneFormat()
    {
        $parser = new OptionsParser(Format::none);
        $options = $parser->parse('');
        $this->assertSame([], $options);
    }
}
