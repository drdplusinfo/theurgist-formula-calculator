<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Tests\Properties\Base;

use DrdPlus\Properties\Base\BaseProperty;
use DrdPlus\Properties\Property;
use Granam\Tests\ExceptionsHierarchy\Exceptions\AbstractExceptionsHierarchyTest;

class BasePropertiesExceptionsHierarchyTest extends AbstractExceptionsHierarchyTest
{
    /**
     * @return string
     * @throws \ReflectionException: string
     */
    protected function getTestedNamespace(): string
    {
        $reflection = new \ReflectionClass(BaseProperty::class);

        return $reflection->getNamespaceName();
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    protected function getRootNamespace(): string
    {
        $reflection = new \ReflectionClass(Property::class);

        return $reflection->getNamespaceName();
    }

}