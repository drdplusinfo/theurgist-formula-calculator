# Enumeration with integers

## <span id="usage">Usage</span>
1. [Use enum](#use-enum)
2. [NULL is NULL, not Enum](#null-is-null-enum-can-not-hold-it)
3. [Installation](#installation)

## Use enum
```php
<?php
use \Granam\IntegerEnum\IntegerEnum;

$enum = IntegerEnum::getEnum(12345);
echo $enum->getValue(); // 12345
var_dump($enum->is('12345')); // false
var_dump($enum->is(12345)); // true
var_dump($enum->is($enum)); // true
var_dump($enum->is(IntegerEnum::getEnum(12345))); // true
var_dump($enum->is(IntegerEnum::getEnum(99999))); // false
```

## NULL is NULL, enum can not hold it
You **can not** create IntegerEnum with NULL value. Just use NULL directly for such value.

```php
<?php
try {
    \Granam\IntegerEnum\IntegerEnum::getEnum(null);
} catch(\Granam\IntegerEnum\Exceptions\UnexpectedValueToConvert $unexpectedValueToEnum) {
    echo $unexpectedValueToEnum->getMessage(); // Expected scalar or object with __toString method on strict mode, got NULL
}
```

## Installation

```bash
composer.phar require granam/integer-enum
```

or manually edit composer.json at your project and `"require":` block (extend existing)

```json
"require": {
    "granam/integer-enum": "dev-master"
}
```