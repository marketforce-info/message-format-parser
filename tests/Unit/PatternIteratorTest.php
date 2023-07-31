<?php

use MarketforceInfo\MessageFormatParser\PatternIterator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MarketforceInfo\MessageFormatParser\PatternIterator
 */
class PatternIteratorTest extends TestCase
{
    public function testFetchFirstCharacter()
    {
        $iterator = new PatternIterator('Hello {name}!');
        $this->assertEquals('H', $iterator->current());
    }

    public function testFetchSecondCharacter()
    {
        $iterator = new PatternIterator('Hello {name}!');
        $iterator->next();
        $this->assertEquals('e', $iterator->current());
    }

    public function testFetchUntilCharacter()
    {
        $iterator = new PatternIterator('Hello {name}! {foo}');
        $this->assertEquals('Hello ', $iterator->fetchUntil('{'));
    }

    public function testFetchBlock()
    {
        $iterator = new PatternIterator('Hello {name}! {foo}');
        $iterator->fetchUntil('{');
        $this->assertEquals('name', $iterator->fetchBlock());
        $this->assertNotEquals('}', $iterator->current());
    }

    public function testFetchBlockWithNested()
    {
        $iterator = new PatternIterator('Hello {name, select, other {other selected}}! {foo}');
        $iterator->fetchUntil('{');
        $this->assertEquals('name, select, other {other selected}', $iterator->fetchBlock());
    }

    public function testFetchBlockWithFormat()
    {
        $iterator = new PatternIterator('Hello {count, number}! {foo}');
        $iterator->fetchUntil('{');
        $this->assertEquals('count, number', $iterator->fetchBlock());
    }

    public function testLoopUntilEnd()
    {
        $iterator = new PatternIterator('Hello {name}! {foo}');
        foreach ($iterator as $character) {
            // Do nothing
        }
        $this->assertNull($iterator->current());
        $this->assertFalse($iterator->valid());
    }

    public function testFetchRemaining()
    {
        $iterator = new PatternIterator('Hello {name}! {foo}');
        $iterator->fetchUntil('{');
        $iterator->fetchBlock();
        $this->assertEquals('! {foo}', $iterator->fetchRemaining());
    }
}
