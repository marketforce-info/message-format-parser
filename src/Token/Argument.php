<?php

declare(strict_types=1);

namespace MarketforceInfo\MessageFormatParser\Token;

use MarketforceInfo\MessageFormatParser\Argument\Format;
use MarketforceInfo\MessageFormatParser\Exceptions\SyntaxException;

class Argument
{
    public function __construct(
        public readonly string $name,
        public readonly Format $format = Format::none,
        public readonly array $options = []
    ) {
        if (!preg_match('/^\w+$/', $name)) {
            throw new SyntaxException("Invalid argument name: {$name}");
        }
    }
}
