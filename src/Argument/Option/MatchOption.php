<?php

namespace MarketforceInfo\MessageFormatParser\Argument\Option;

use MarketforceInfo\MessageFormatParser\Parser;
use MarketforceInfo\MessageFormatParser\Token\Pattern;

class MatchOption implements Option
{
    public readonly Pattern $expression;
    public function __construct(public readonly string $match, string $expression)
    {
        $this->expression = (new Parser())->parse($expression);
    }
}
