<?php
declare(strict_types=1);

namespace MarketforceInfo\MessageFormatParser;

class PatternIterator implements \Iterator
{
    private readonly string $pattern;
    private readonly int $length;
    private int $index = 0;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
        $this->length = mb_strlen($pattern);
    }

    public function current(): ?string
    {
        if ($this->index >= $this->length) {
            return null;
        }
        return mb_substr($this->pattern, $this->index, 1);
    }

    public function next(): void
    {
        $this->index++;
    }

    public function key(): int
    {
        return $this->index;
    }

    public function valid(): bool
    {
        return $this->index < mb_strlen($this->pattern);
    }

    public function rewind(): void
    {
        $this->index = 0;
    }

    public function lookAhead(): ?string
    {
        if ($this->index + 1 >= $this->length) {
            return null;
        }
        return mb_substr($this->pattern, $this->index + 1, 1);
    }

    public function fetchBlock(): string
    {
        if ($this->current() === '{') {
            $this->next();
        }
        return $this->fetchUntil('}');
    }

    public function fetchUntil(string $stopCharacter): string
    {
        $isBlockSyntax = ($stopCharacter === '}');
        $depth = 0;
        $fetched = '';
        while (($character = $this->current()) !== null && ($character !== $stopCharacter || $depth > 0)) {
            if ($isBlockSyntax && $character === '{') {
                $depth++;
            } elseif ($isBlockSyntax && $character === '}') {
                $depth--;
            }
            $fetched .= $character;
            $this->next();
        }
        return $fetched;
    }

    public function fetchRemaining(): string
    {
        $remaining = mb_substr($this->pattern, $this->index);
        $this->index = $this->length;
        return $remaining;
    }
}
