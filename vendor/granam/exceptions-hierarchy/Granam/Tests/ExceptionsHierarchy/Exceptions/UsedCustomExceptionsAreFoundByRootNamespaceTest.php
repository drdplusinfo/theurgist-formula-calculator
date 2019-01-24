<?php
namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

use Granam\Tests\ExceptionsHierarchy\Exceptions\ExceptionsUsedInParentDirs\PerfectLife;
use Granam\Tests\ExceptionsHierarchy\Exceptions\ExceptionsUsedInParentDirs\PerfectSociety\PerfectFamily\PerfectMamaAtWork;

class UsedCustomExceptionsAreFoundByRootNamespaceTest extends AbstractExceptionsHierarchyTest
{
    /**
     * @return string
     */
    protected function getTestedNamespace()
    {
        $reflection = new \ReflectionClass(PerfectMamaAtWork::getClass());

        return $reflection->getNamespaceName();
    }

    /**
     * @return string
     */
    protected function getRootNamespace()
    {
        $reflection = new \ReflectionClass(PerfectLife::getClass());

        return $reflection->getNamespaceName();
    }
}