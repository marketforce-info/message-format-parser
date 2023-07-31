<?php
declare(strict_types=1);

namespace MarketforceInfo\MessageFormatParser\Token;

class Pattern
{
    public function __construct(
        /** @var Literal|Argument[] */
        public readonly array $children = [],
    )
    {}
}
