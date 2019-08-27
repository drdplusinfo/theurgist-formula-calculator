<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

class WithSameNamedExternalParentExceptionHierarchyTest extends AbstractExceptionsHierarchyTest
{
    /**
     * @return string
     */
    protected function getTestedNamespace()
    {
        return $this->getRootNamespace();
    }

    /**
     * @return string
     */
    protected function getRootNamespace()
    {
        return __NAMESPACE__ . '\DummyExceptionsHierarchy\WithSameNamedExternalParent';
    }

    /**
     * @return false
     */
    protected function getExceptionsSubDir()
    {
        return false; // exceptions are directly in the tested namespace
    }

    protected function getExternalRootNamespaces()
    {
        return [
            __NAMESPACE__ . '\DummyExceptionsHierarchy\WithSameNamedParent\Children\IAmHereForWithSameNamedExternalParent',
        ];
    }

    /**
     * @return false
     */
    protected function getExternalRootExceptionsSubDir()
    {
        return false; // exceptions are directly in the external root namespace
    }

    protected function getExceptionClassesSkippedFromUsageTest()
    {
        return [
            'Granam\Tests\Exceptions\Tools\DummyExceptionsHierarchy\WithSameNamedExternalParent\IAmSameNamed',
        ];
    }

}
