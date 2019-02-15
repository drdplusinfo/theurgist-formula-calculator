# Enumeration with scalar values

## <span id="usage">Usage</span>
1. [Use enum](#use-enum)
2. [NULL is NULL, not Enum](#null-is-null-enum-can-not-hold-it)
3. [Installation](#installation)

## Use enum
```php
<?php
$enum = \Granam\ScalarEnum\ScalarEnum::getEnum('foo bar');
echo $enum->getValue(); // foo bar
var_dump($enum->is('foo bar')); // true
```

## NULL is NULL, enum can not hold it
You **can not** create ScalarEnum with NULL value. Just use NULL directly for such value.

```php
<?php
try {
    \Granam\ScalarEnum\ScalarEnum::getEnum(null);
} catch(\Granam\ScalarEnum\Exceptions\UnexpectedValueToEnum $unexpectedValueToEnum) {
    echo $unexpectedValueToEnum->getMessage(); // Expected scalar or object with __toString method on strict mode, got NULL
}
```

## Installation

```bash
composer.phar require granam/scalar-enum
```

or manually edit composer.json at your project and `"require":` block (extend existing)

```json
"require": {
    "granam/scalar-enum": "dev-master"
}
```