<?php
declare(strict_types=1);

namespace MarketforceInfo\MessageFormatParser\Token;

class Literal
{
    public function __construct(
        public readonly string $value,
    )
    {}
}
