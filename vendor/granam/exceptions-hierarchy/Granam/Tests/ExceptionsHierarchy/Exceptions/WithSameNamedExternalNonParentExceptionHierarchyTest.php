<?php
namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

class WithSameNamedExternalNonParentExceptionHierarchyTest extends AbstractExceptionsHierarchyTest
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
        return __NAMESPACE__ . '\\DummyExceptionsHierarchy\\WithSameNamedExternalNonParent';
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
            __NAMESPACE__ . '\\DummyExceptionsHierarchy\\WithSameNamedParent',
        ];
    }

    /**
     * @return false
     */
    protected function getExternalRootExceptionsSubDir()
    {
        return false; // exceptions are directly in the external root namespace
    }/** @noinspection SenselessProxyMethodInspection */

    /**
     * @test
     * @expectedException \Granam\ExceptionsHierarchy\Exceptions\InvalidExceptionHierarchy
     * @expectedExceptionMessageRegExp ~Exception .+\\WithSameNamedExternalNonParent\\IAmSameNamed .+ parent .+\\WithSameNamedParent\\IAmSameNamed~
     */
    public function My_exceptions_are_in_family_tree()
    {
        parent::My_exceptions_are_in_family_tree();
    }

    protected function getExceptionClassesSkippedFromUsageTest()
    {
        return [
            DummyExceptionsHierarchy\WithSameNamedExternalNonParent\IAmSameNamed::class,
        ];
    }

}