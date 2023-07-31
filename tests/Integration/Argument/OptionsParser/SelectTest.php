<?php
declare(strict_types=1);

namespace MarketforceInfo\MessageFormatParser\Tests\Integration\Argument\OptionsParser;

use MarketforceInfo\MessageFormatParser\Argument\Format;
use MarketforceInfo\MessageFormatParser\Argument\OptionsParser;
use MarketforceInfo\MessageFormatParser\Exceptions\SyntaxException;
use MarketforceInfo\MessageFormatParser\Token\Pattern;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MarketforceInfo\MessageFormatParser\Argument\OptionsParser
 */
class SelectTest extends TestCase
{
    private OptionsParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new OptionsParser(Format::select);
    }

    public function testFormat()
    {
        $options = $this->parser->parse('
            male   {he}
            female {she}
            other  {they}
        ');

        $this->assertCount(3, $options);
        $this->assertEquals('male', $options[0]->match);
        $this->assertInstanceOf(Pattern::class, $options[0]->expression);
        $this->assertCount(1, $options[0]->expression->children);
        $this->assertEquals('he', $options[0]->expression->children[0]->value);
    }

    public function testRequiresOtherMatch()
    {
        $this->expectException(SyntaxException::class);
        $this->parser->parse('
            male   {he}
            female {she}
        ');
        $this->assertStringContainsString('other', $this->getExpectedExceptionMessage());
    }

    public function testDuplicateMatch()
    {
        $this->expectException(SyntaxException::class);
        $this->parser->parse('
            male  {he}
            male  {she}
            other {they}
        ');
        $this->assertStringContainsString('duplicate match', $this->getExpectedExceptionMessage());
    }
}
