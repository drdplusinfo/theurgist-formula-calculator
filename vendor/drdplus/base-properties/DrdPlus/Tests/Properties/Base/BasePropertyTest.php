<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Tests\Properties\Base;

use DrdPlus\Tests\Properties\Base\Partials\AbstractIntegerPropertyTest;

abstract class BasePropertyTest extends AbstractIntegerPropertyTest
{
    use ItHasProperlyAnnotatedCodeGetter;
}