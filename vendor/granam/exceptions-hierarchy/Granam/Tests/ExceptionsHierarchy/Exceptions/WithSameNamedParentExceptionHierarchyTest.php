<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

class WithSameNamedParentExceptionHierarchyTest extends AbstractExceptionsHierarchyTest
{
    /**
     * @return string
     */
    protected function getTestedNamespace()
    {
        return $this->getRootNamespace() . '\\WithSameNamedParent\\Children';
    }

    /**
     * @return string
     */
    protected function getRootNamespace()
    {
        return __NAMESPACE__ . '\\DummyExceptionsHierarchy';
    }

    /**
     * @return false
     */
    protected function getExceptionsSubDir()
    {
        return false; // exceptions are directly in the tested namespace
    }

    protected function getExceptionClassesSkippedFromUsageTest()
    {
        return [
            DummyExceptionsHierarchy\WithSameNamedParent\Children\IAmSameNamed::class,
            DummyExceptionsHierarchy\WithSameNamedParent\IAmSameNamed::class,
            DummyExceptionsHierarchy\IAmLogicException::class,
            DummyExceptionsHierarchy\IAmRuntimeException::class,
        ];
    }

}