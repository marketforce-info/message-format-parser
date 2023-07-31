<?php

declare(strict_types=1);

namespace MarketforceInfo\MessageFormatParser\Argument;

use MarketforceInfo\MessageFormatParser\Argument\Option\{
    GenericOption,
    MatchOption,
    PluralMatches,
    SelectMatches,
    SelectOrdinalMatches
};
use MarketforceInfo\MessageFormatParser\Exceptions\SyntaxException;
use MarketforceInfo\MessageFormatParser\PatternIterator;

class OptionsParser
{
    public function __construct(
        private readonly Format $format
    ) {
    }

    public function parse(string $options): array|\IteratorAggregate
    {
        $pattern = new PatternIterator(trim($options));

        return match ($this->format) {
            Format::plural => $this->parseMatchExpression($pattern, PluralMatches::class),
            Format::select => $this->parseMatchExpression($pattern, SelectMatches::class),
            Format::selectordinal => $this->parseMatchExpression($pattern, SelectOrdinalMatches::class),
            Format::number => $this->parseNumber($pattern),
            Format::date => $this->parseDate($pattern),
            Format::time => $this->parseTime($pattern),
            Format::none => [],
        };
    }

    private function parseMatchExpression(PatternIterator $pattern, string $class): \IteratorAggregate
    {
        $options = [];
        while (($character = $pattern->current()) !== null && $character !== '}') {
            $options[] = new MatchOption(
                trim($pattern->fetchUntil('{')),
                $pattern->fetchBlock()
            );
            $pattern->next();
        }
        return new $class($options);
    }

    /**
     * @see https://unicode-org.github.io/icu/userguide/format_parse/numbers/skeletons.html
     */
    private function parseNumber(PatternIterator $pattern): array
    {
        return $this->parseGeneric($pattern, 'number', ['integer', 'percent', 'currency']);
    }

    /**
     * @see https://unicode-org.github.io/icu/userguide/format_parse/datetime/#datetime-format-syntax
     */
    private function parseDate(PatternIterator $pattern): array
    {
        return $this->parseGeneric($pattern, 'date', ['short', 'medium', 'long', 'full']);
    }

    private function parseTime(PatternIterator $pattern): array
    {
        return $this->parseGeneric($pattern, 'time', ['short', 'long', 'full']);
    }

    private function parseGeneric(PatternIterator $pattern, string $type, array $validValues): array
    {
        $style = mb_strtolower(trim($pattern->fetchRemaining()));
        if (empty($style)) {
            return [];
        }

        if (!in_array($style, $validValues, true)) {
            throw new SyntaxException("Invalid {$type} style '{$style}'");
        }

        return [new GenericOption($style)];
    }
}
