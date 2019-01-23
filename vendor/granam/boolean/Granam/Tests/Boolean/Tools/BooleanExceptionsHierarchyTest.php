<?php
namespace Granam\Tests\Boolean\Tools;

use Granam\Boolean\Boolean;
use Granam\Scalar\Scalar;
use Granam\Tests\ExceptionsHierarchy\Exceptions\AbstractExceptionsHierarchyTest;

class BooleanExceptionsHierarchyTest extends AbstractExceptionsHierarchyTest
{
    /**
     * @return string
     */
    protected function getTestedNamespace()
    {
        return str_replace('\Tests', '', __NAMESPACE__);
    }

    /**
     * @return string
     */
    protected function getRootNamespace()
    {
        $rootReflection = new \ReflectionClass(Boolean::class);

        return $rootReflection->getNamespaceName();
    }

    /**
     * @return string
     */
    protected function getExternalRootNamespaces()
    {
        $scalarReflection = new \ReflectionClass(Scalar::class);

        return $scalarReflection->getNamespaceName();
    }

}