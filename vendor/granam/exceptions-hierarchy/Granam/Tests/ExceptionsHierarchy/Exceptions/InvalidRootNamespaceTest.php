<?php
namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

use Granam\ExceptionsHierarchy\TestOfExceptionsHierarchy;

class InvalidRootNamespaceTest extends AbstractExceptionsHierarchyTest
{

    protected function setUp()
    {
        return false; // disabling parent setup
    }

    /**
     * @test
     * @expectedException \Granam\ExceptionsHierarchy\Exceptions\RootNamespaceHasToBeSuperior
     */
    public function I_am_stopped_on_invalid_root_namespace()
    {
        new TestOfExceptionsHierarchy(
            $this->getTestedNamespace(),
            $this->getRootNamespace(),
            $this->getExceptionsSubDir()
        );
    }

    protected function getTestedNamespace()
    {
        return __NAMESPACE__;
    }

    protected function getRootNamespace()
    {
        return 'invalid root namespace';
    }

    public function My_exceptions_are_in_family_tree()
    {
        return false; // disabled
    }

    public function My_exceptions_are_used()
    {
        return false; // disabled
    }
}