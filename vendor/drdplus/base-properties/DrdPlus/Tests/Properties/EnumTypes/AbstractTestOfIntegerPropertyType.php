<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Tests\Properties\EnumTypes;

abstract class AbstractTestOfIntegerPropertyType extends AbstractTestOfPropertyType
{
    protected function getValue()
    {
        return 12345;
    }

}
