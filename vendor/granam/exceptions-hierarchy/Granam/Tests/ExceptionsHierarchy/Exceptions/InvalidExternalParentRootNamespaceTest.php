<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

use Granam\ExceptionsHierarchy\TestOfExceptionsHierarchy;

class InvalidExternalParentRootNamespaceTest extends AbstractExceptionsHierarchyTest
{
    protected function getTestedNamespace()
    {
        return __NAMESPACE__ . '\DummyExceptionsHierarchy\ExternalParentRootNamespace';
    }

    protected function getRootNamespace()
    {
        return $this->getTestedNamespace();
    }

    protected function getExceptionsSubDir()
    {
        return '';
    }

    protected function getExternalRootNamespaces()
    {
        // intentionally wrong external namespace
        return [$this->getRootNamespace()];
    }

    protected function getExternalRootExceptionsSubDir()
    {
        return '';
    }

    protected function setUp(): void
    {
        // disabling original set up
        return;
    }

    /**
     * @test
* @expectExceptionMessageRegExp ~^External root namespace .+ should differ to local root namespace~
     */
    public function I_can_not_use_inner_namespace_as_external()
    {
        $this->expectException(\Granam\ExceptionsHierarchy\Exceptions\ExternalRootNamespaceHasToBeSuperior::class);
        new TestOfExceptionsHierarchy(
            $this->getTestedNamespace(),
            $this->getRootNamespace(),
            $this->getExceptionsSubDir(),
            $this->getExternalRootNamespaces(),
            $this->getExternalRootExceptionsSubDir()
        );
    }

    /**
     * @test
* @expectExceptionMessageRegExp ~^External root namespace .+ should not be subordinate to local root namespace~
     */
    public function I_can_not_use_external_namespace_subordinated_to_internal()
    {
        $this->expectException(\Granam\ExceptionsHierarchy\Exceptions\ExternalRootNamespaceHasToBeSuperior::class);
        new TestOfExceptionsHierarchy(
            $this->getTestedNamespace(),
            $this->getRootNamespace(),
            $this->getExceptionsSubDir(),
            [$this->getTestedNamespace() . '\\InvalidExternalParent'],
            ''
        );
    }

    public function My_exceptions_are_in_family_tree()
    {
        // disabling original test
        return false;
    }

    public function My_exceptions_are_used()
    {
        // disabling original test
        return false;
    }

}
