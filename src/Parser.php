<?php
declare(strict_types=1);

namespace MarketforceInfo\MessageFormatParser;

use MarketforceInfo\MessageFormatParser\Argument\Format;
use MarketforceInfo\MessageFormatParser\Argument\OptionsParser;
use MarketforceInfo\MessageFormatParser\Exceptions\SyntaxException;
use MarketforceInfo\MessageFormatParser\Token\{Argument, Literal, Pattern};

class Parser
{
    public function parse(string $message): Pattern
    {
        $children = [];
        $pattern = new PatternIterator($message);
        while ($character = $pattern->current()) {
            if ($character === '{') {
                $children[] = $this->parseArgument($pattern);
            } else {
                $children[] = $this->parseLiteral($pattern);
            }
        }
        return new Pattern($children);
    }

    private function parseArgument(PatternIterator $pattern): Argument
    {
        $pattern->next();
        $parameters = [''];
        $parameterIndex = 0;
        $depth = 0;
        while (($character = $pattern->current()) && ($character !== '}' || $depth > 0)) {
            if ($character === ',' && $parameterIndex < 2) {
                // maximum of 3 parameters for an argument
                $parameterIndex++;
                $parameters[$parameterIndex] = '';
            } else {
                $parameters[$parameterIndex] .= $character;
            }
            if ($character === '{') {
                $depth++;
            } elseif ($character === '}') {
                $depth--;
            }
            $pattern->next();
        }

        if ($character !== '}') {
            throw new SyntaxException("Missing closing brace from argument '$parameters[0]'");
        }

        if (isset($parameters[1])) {
            $parameters[1] = $this->resolveArgumentFormat($parameters[1]);
            $parameters[2] = (new OptionsParser($parameters[1]))->parse($parameters[2] ?? '');
        }

        $pattern->next();
        return new Argument(...$parameters);
    }

    private function parseLiteral(PatternIterator $pattern): Literal
    {
        $value = '';
        $isQuoted = false;
        while (($character = $pattern->current()) && ($character !== '{' || $isQuoted)) {
            if ($character === "'" && $pattern->lookAhead() !== "'") {
                $isQuoted = !$isQuoted;
                $pattern->next();
                continue;
            }

            $value .= $character;
            $pattern->next();
        }

        return new Literal($value);
    }

    private function resolveArgumentFormat(string $format): Format
    {
        $instance = Format::tryFrom(trim($format));
        if (!$instance instanceof Format) {
            throw new SyntaxException("Invalid argument format '$format'");
        }
        return $instance;
    }
}
