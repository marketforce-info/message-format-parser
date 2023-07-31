# MarketforceInfo/Message-Format-Parser

[![Code Checks](https://img.shields.io/github/actions/workflow/status/marketforce-info/message-format-parser/code-checks.yml?branch=main&logo=github)](https://github.com/marketforce-info/message-format-parser/actions/workflows/code-checks.yml)
[![Latest Stable Version](https://img.shields.io/github/v/release/marketforce-info/message-format-parser?logo=packagist)](https://github.com/marketforce-info/message-format-parser/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/marketforce-info/message-format-parser?logo=packagist)](https://packagist.org/packages/marketforce-info/message-format-parser)
![Licence](https://img.shields.io/github/license/marketforce-info/message-format-parser.svg)

## Description
Parses ICU message format syntax into an abstract syntax tree. Can be used for transformation or validation.

---

## Installation

```bash
$ composer require marketforce-info/message-format-parser
```

## Usage

### Basic

```php
$message = "Welcome {name} to the club."
$ast = (new MarketforceInfo\MessageFormatParser\Parser())->parse($message)
```

## Naming convention

### Pattern
```text
This is my {main_message} to everyone.
\_________/\____________/\___________/
  literal     argument      literal
```

### Argument
```text
{name[, format[, options]]}
```

### Formats
#### Number

## Syntax Support

Contributions gratefully accepted in the form issues or PRs.

## Security

If you discover any security related issues, please email appsupport_uk@marketforce.com instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
