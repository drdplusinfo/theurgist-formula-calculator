<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

class WithoutRuntimeTagTest extends AbstractExceptionsHierarchyTest
{
    /** @noinspection SenselessProxyMethodInspection */

    /**
     * I_am_stopped_if_runtime_tag_is_missing
     *
     * @test

     * @expectExceptionMessageRegExp ~^Runtime tag interface .+\\Runtime not found$~
     */
    public function My_exceptions_are_in_family_tree()
    {
        $this->expectException(\Granam\ExceptionsHierarchy\Exceptions\TagInterfaceNotFound::class);
        parent::My_exceptions_are_in_family_tree();
    }

    protected function getTestedNamespace()
    {
        return __NAMESPACE__ . '\DummyExceptionsHierarchy\WithoutRuntimeTag';
    }

    protected function getExceptionsSubDir()
    {
        return false;
    }

    protected function getRootNamespace()
    {
        return $this->getTestedNamespace();
    }

}