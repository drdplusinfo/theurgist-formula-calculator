<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

use Granam\ExceptionsHierarchy\TestOfExceptionsHierarchy;

class InvalidRootNamespaceTest extends AbstractExceptionsHierarchyTest
{

    protected function setUp(): void
    {
        return; // disabling parent setup
    }

    /**
     * @test
     */
    public function I_am_stopped_on_invalid_root_namespace()
    {
        $this->expectException(\Granam\ExceptionsHierarchy\Exceptions\RootNamespaceHasToBeSuperior::class);
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