<?php declare(strict_types=1);

namespace Granam\Tests\IntegerEnum;

use Granam\ScalarEnum\ScalarEnumInterface;
use Granam\Tests\ExceptionsHierarchy\Exceptions\AbstractExceptionsHierarchyTest;

class GranamIntegerExceptionsHierarchyTest extends AbstractExceptionsHierarchyTest
{
    /**
     * @return string
     */
    protected function getTestedNamespace(): string
    {
        return $this->getRootNamespace();
    }

    /**
     * @return string
     */
    protected function getRootNamespace(): string
    {
        return str_replace('\Tests', '', __NAMESPACE__);
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    protected function getExternalRootNamespaces(): string
    {
        $externalRootReflection = new \ReflectionClass(ScalarEnumInterface::class);

        return $externalRootReflection->getNamespaceName();
    }

}