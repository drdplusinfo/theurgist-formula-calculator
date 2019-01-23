<?php
namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

class WithSameNamedNonParentExceptionHierarchyTest extends AbstractExceptionsHierarchyTest
{
    /**
     * @return string
     */
    protected function getTestedNamespace()
    {
        return $this->getRootNamespace() . '\\WithSameNamedNonParent\\Children';
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
    }/** @noinspection SenselessProxyMethodInspection */

    /**
     * @test
     * @expectedException \Granam\ExceptionsHierarchy\Exceptions\InvalidExceptionHierarchy
     * @expectedExceptionMessageRegExp ~^Exception .+\\WithSameNamedNonParent\\Children\\IAmSameNamedButNotFromFamily should extends parent .+\\WithSameNamedNonParent\\IAmSameNamedButNotFromFamily~
     */
    public function My_exceptions_are_in_family_tree()
    {
        // overloading parent method annotations
        parent::My_exceptions_are_in_family_tree();
    }

    protected function getExceptionClassesSkippedFromUsageTest()
    {
        return [
            DummyExceptionsHierarchy\WithSameNamedNonParent\Children\IAmSameNamedButNotFromFamily::class,
            DummyExceptionsHierarchy\WithSameNamedNonParent\IAmSameNamedButNotFromFamily::class,
            DummyExceptionsHierarchy\IAmLogicException::class,
            DummyExceptionsHierarchy\IAmRuntimeException::class,
        ];
    }

}