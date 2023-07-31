<?php

namespace MarketforceInfo\MessageFormatParser\Argument\Option;

class GenericOption implements Option
{
    public function __construct(public readonly string $value)
    {
    }
}
