Doctrine Integer enum
=====================

[![Build Status](https://travis-ci.org/jaroslavtyc/doctrineum-integer.svg?branch=master)](https://travis-ci.org/jaroslavtyc/doctrineum-integer)
[![Test Coverage](https://codeclimate.com/github/jaroslavtyc/doctrineum-integer/badges/coverage.svg)](https://codeclimate.com/github/jaroslavtyc/doctrineum-integer/coverage)
[![License](https://poser.pugx.org/doctrineum/integer/license)](https://packagist.org/packages/doctrineum/integer)

## About
Adds [Enum](http://en.wikipedia.org/wiki/Enumerated_type) to [Doctrine ORM](http://www.doctrine-project.org/)
(can be used as a `@Column(type="integer_enum")`).

##Usage

```php
<?php

use Doctrine\ORM\Mapping as ORM;
use Doctrineum\Integer\IntegerEnum;

/**
 * @ORM\Entity()
 */
class Journey
{
    /**
     * @var int
     * @ORM\Id() @ORM\GeneratedValue(strategy="AUTO") @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var IntegerEnum
     * @ORM\Column(type="integer_enum")
     */
    private $distanceInKm;
    
    public function __construct(IntegerEnum $distanceInKm)
    {
        $this->distanceInKm = $distanceInKm;
    }

    /**
     * @return IntegerEnum
     */
    public function getDistanceInKm()
    {
        return $this->distanceInKm;
    }
}

$toSun = new Journey(IntegerEnum::getEnum(152100000));

/** @var \Doctrine\ORM\EntityManager $entityManager */
$entityManager->persist($toSun);
$entityManager->flush();
$entityManager->clear();

/** @var Journey[] $StarTracks */
$StarTracks = $entityManager->createQuery(
    "SELECT j FROM Journey j WHERE j.distanceInKm >= 1000000"
)->getResult();

var_dump($StarTracks[0]->getDistanceInKm()->getValue()); // 152100000;
```

##Installation

Add it to your list of Composer dependencies (or by manual edit your composer.json, the `require` section)

```sh
composer require jaroslavtyc/doctrineum-integer
```

## Doctrine integration

Register new DBAL type:

```php
<?php

use Doctrineum\Integer\IntegerEnumType;

IntegerEnumType::registerSelf();
```

When using Symfony with Doctrine you can do the same as above by configuration:

```yaml
# app/config/config.yml

# Doctrine Configuration
doctrine:
    dbal:
        # ...
        mapping_types:
            integer_enum: integer_enum
        types:
            integer_enum: Doctrineum\Integer\IntegerEnumType
```
