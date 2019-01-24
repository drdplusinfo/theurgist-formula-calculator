[![Build Status](https://travis-ci.org/jaroslavtyc/doctrineum-scalar.svg?branch=master)](https://travis-ci.org/jaroslavtyc/doctrineum-scalar)
[![Test Coverage](https://codeclimate.com/github/jaroslavtyc/doctrineum-scalar/badges/coverage.svg)](https://codeclimate.com/github/jaroslavtyc/doctrineum-scalar/coverage)
[![Latest Stable Version](https://poser.pugx.org/doctrineum/scalar/v/stable.svg)](https://packagist.org/packages/doctrineum/scalar)
[![License](https://poser.pugx.org/doctrineum/scalar/license.svg)](http://en.wikipedia.org/wiki/MIT_License)

##### Customizable enumeration type for Doctrine 2.4+

About custom Doctrine types, see the [official documentation](http://doctrine-orm.readthedocs.org/en/latest/cookbook/custom-mapping-types.html).
For default types see the [official documentation as well](http://doctrine-dbal.readthedocs.org/en/latest/reference/types.html).

## <span id="usage">Usage</span>
1. [Installation](#installation)
2. [Custom type registration](#custom-type-registration)
3. [Map property as an enum](#map-property-as-an-enum)
4. [Create enum](#create-enum)
5. [Register subtype enum](#register-subtype-enum)
6. [NULL is NULL, not Enum](#null-is-null-not-enum)
7. [Understand the basics](#understand-the-basics)
8. [Exceptions philosophy](#exceptions-philosophy)

### <span id="installation">Installation</span>
```bash
composer.phar require doctrineum/scalar
```

or manually edit composer.json at your project and `"require":` block (extend existing)
```json
    "require": {
        "doctrineum/scalar": "dev-master"
    }
```

### Custom type registration

By helper method
```php
ScalarEnum::registerSelf(); // quick self-registration
```

Or manually using "magic" [class::class constant](http://php.net/manual/en/language.oop5.basic.php#language.oop5.basic.class.class)
```php
use Doctrineum\Scalar\ScalarEnumType;
// ...
Type::addType(ScalarEnumType::getTypeName(), ScalarEnumType::class);
Type::addType(BarScalarEnumType::getTypeName(), BarScalarEnumType::class);
```

Or manually by old fashion way

```php
<?php
// in bootstrapping code
// ...
use Doctrine\DBAL\Types\Type;
use Doctrineum\Scalar\ScalarEnumType;
// ...
// Register type
Type::addType(ScalarEnumType::getTypeName(), '\Doctrineum\ScalarEnumType');
Type::addType(BarScalarEnumType::getTypeName(), '\Foo\BarScalarEnumType');
```

Or if your project uses Symfony2
```yaml
# app/config/config.yml
doctrine:
    dbal:
        # ...
        types:
            scalar_enum: Doctrineum\Scalar\ScalarEnumType
            bar: Foo\BarScalarEnumType
            #...
```

### Map property as an enum
```php
<?php
class Foo
{
    /** @Column(type="scalar_enum") */
    protected $field;
}
```

### Create enum
```php
<?php
use Doctrineum\Scalar\ScalarEnum;
$enum = ScalarEnum::getEnum('foo bar');
```

### Register subtype enum
You can register infinite number of enums, which are built according to a regexp of your choice.
```php
<?php
use Doctrineum\Scalar\ScalarEnumType;
ScalarEnumType::addSubTypeEnum('\Foo\Bar\YourSubTypeEnum', '~get me different enum for this value~');
// ...
$enum = $ScalarEnumType->convertToPHPValue('foo');
get_class($enum) === '\Doctrineum\Scalar\ScalarEnum'; // true
get_class($enum) === '\Foo\Bar\YourSubTypeEnum'; // false
$byRegexpDeterminedEnum = $ScalarEnumType->convertToPHPValue('And now get me different enum for this value.');
get_class($byRegexpDeterminedEnum) === '\Foo\Bar\YourSubTypeEnum'; // true
```

### NULL is NULL, Enum can not hold it
You can not create ScalarEnum with NULL value. Just use NULL directly for such column value.

Beware on using subtypes only when main enum is an abstract class. You have to resolve from-database-NULL->to-PHP-value conversion,
or register subtype explicitly for NULL value (empty string respectively), otherwise fatal error by abstract class instance creation occurs.

#### Understand the basics
There are two roles - the factory and the value.

 - ScalarEnumType is the factory (as part of the Doctrine\DBAL\Types\Type family), building an ScalarEnum by following ScalarEnumType rules.
 - ScalarEnum is the value holder, de facto singleton, represented by a class. And class, as you know, can do a lot of things, which makes enum more sexy then whole scalar value.
 - Subtype is an ScalarEnumType, but ruled not just by type, but also by current value itself. One type can has any number of subtypes, in dependence on your imagination and used enum values.

##### Exceptions philosophy
Doctrineum adopts [Granam exception hierarchy ideas](https://github.com/jaroslavtyc/granam-exception-hierarchy).
That means every exceptionable state is probably by a **logic** mistake, rather than a runtime situation.
