<?php
namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

class RuntimeTagBrokenLineageTest extends AbstractExceptionsHierarchyTest
{
    /** @noinspection SenselessProxyMethodInspection */

    /**
     * @test
     * @expectedException \Granam\ExceptionsHierarchy\Exceptions\InvalidExceptionHierarchy
     * @expectedExceptionMessageRegExp ~^Tag .+\\BrokenLineage\\RuntimeTagWithoutParent\\Runtime should be child of .+\\BrokenLineage\\Runtime~
     */
    public function My_exceptions_are_in_family_tree()
    {
        parent::My_exceptions_are_in_family_tree();
    }

    /**
     * @return string
     */
    protected function getTestedNamespace()
    {
        return $this->getRootNamespace() . '\\RuntimeTagWithoutParent';
    }

    /**
     * @return string
     */
    protected function getRootNamespace()
    {
        return __NAMESPACE__ . '\\DummyExceptionsHierarchy\\BrokenLineage';
    }

    /**
     * @return false
     */
    protected function getExceptionsSubDir()
    {
        return false; // exceptions are directly in the tested namespace
    }

}