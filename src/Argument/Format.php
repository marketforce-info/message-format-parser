<?php

declare(strict_types=1);

namespace MarketforceInfo\MessageFormatParser\Argument;

enum Format: string
{
    case none = 'none';
    case number = 'number';
    case date = 'date';
    case time = 'time';
    case select = 'select';
    case selectordinal = 'selectordinal';
    case plural = 'plural';
}
