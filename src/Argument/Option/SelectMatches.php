<?php

namespace MarketforceInfo\MessageFormatParser\Argument\Option;

use MarketforceInfo\MessageFormatParser\Exceptions\SyntaxException;

class SelectMatches extends AbstractMatches
{
    protected function validate(): void
    {
        $matches = [];
        $hasOther = false;
        foreach ($this->options as $option) {
            if ($option->match === 'other') {
                $hasOther = true;
            }
            if (empty($option->expression->children)) {
                throw new SyntaxException('Select options must have an expression');
            }

            if (isset($matches[$option->match])) {
                throw new SyntaxException("Duplicate select match value '{$option->match}'");
            }
            $matches[$option->match] = true;
        }

        if (!$hasOther) {
            throw new SyntaxException('Select options must contain an "other" option');
        }
    }
}
