<?php

namespace MarketforceInfo\MessageFormatParser\Argument\Option;

use MarketforceInfo\MessageFormatParser\Exceptions\RuntimeException;
use Traversable;

abstract class AbstractMatches implements \Countable, \IteratorAggregate, \ArrayAccess
{
    public function __construct(
        public readonly array $options
    ) {
        $this->validate();
    }

    public function count(): int
    {
        return count($this->options);
    }

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->options);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->options[$offset]);
    }

    public function offsetGet(mixed $offset): MatchOption
    {
        return $this->options[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new RuntimeException('PluralOptions are immutable');
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new RuntimeException('PluralOptions are immutable');
    }

    abstract protected function validate(): void;
}
