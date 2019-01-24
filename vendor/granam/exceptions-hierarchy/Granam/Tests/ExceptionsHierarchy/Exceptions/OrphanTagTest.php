<?php
namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

class OrphanTagTest extends AbstractExceptionsHierarchyTest
{
    /** @noinspection SenselessProxyMethodInspection */

    /**
     * @test
     * @expectedException \Granam\ExceptionsHierarchy\Exceptions\TagInterfaceNotFound
     */
    public function My_exceptions_are_in_family_tree()
    {
        parent::My_exceptions_are_in_family_tree();
    }

    protected function getTestedNamespace()
    {
        return __NAMESPACE__ . '\DummyExceptionsHierarchy\OrphanTag';
    }

    protected function getExceptionsSubDir()
    {
        return false;
    }

    protected function getRootNamespace()
    {
        return $this->getTestedNamespace();
    }

    public function My_exceptions_are_used()
    {
        // disabling original test
        return false;
    }

}