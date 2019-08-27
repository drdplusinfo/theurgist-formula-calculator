<?php declare(strict_types=1);

namespace DrdPlus\Tests\BaseProperties;

use DrdPlus\BaseProperties\BaseProperty;
use DrdPlus\BaseProperties\Property;
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