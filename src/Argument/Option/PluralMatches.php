<?php

namespace MarketforceInfo\MessageFormatParser\Argument\Option;

use MarketforceInfo\MessageFormatParser\Exceptions\SyntaxException;

class PluralMatches extends AbstractMatches
{
    private array $validOptions = [
        'zero',
        'one',
        'two',
        'few',
        'many',
    ];

    protected function validate(): void
    {
        $matches = [];
        $hasOther = false;
        foreach ($this->options as $option) {
            if ($option->match === 'other') {
                $hasOther = true;
            } elseif (str_starts_with($option->match, '=')) {
                if (!is_numeric(substr($option->match, 1))) {
                    throw new SyntaxException("Invalid numeric plural match value '$option->match'");
                }
            } elseif (!in_array($option->match, $this->validOptions, true)) {
                throw new SyntaxException("Invalid plural match value '$option->match'");
            }

            if (empty($option->expression->children)) {
                throw new SyntaxException('Plural options must have an expression');
            }

            if (isset($matches[$option->match])) {
                throw new SyntaxException("Duplicate plural match value '$option->match'");
            }
            $matches[$option->match] = true;
        }

        if (!$hasOther) {
            throw new SyntaxException('Plural options must contain an "other" option');
        }
    }
}
