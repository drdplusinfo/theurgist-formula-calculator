<?php
namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

use Granam\Tests\ExceptionsHierarchy\Exceptions\DummyExceptionsHierarchy\LogicExceptionAsRuntimeException\IThoughIAmRuntimeException;

class LogicExceptionAsRuntimeExceptionTest extends AbstractExceptionsHierarchyTest
{
    /** @noinspection SenselessProxyMethodInspection */
    /**
     * @test
     * @expectedException \Granam\ExceptionsHierarchy\Exceptions\InvalidExceptionHierarchy
     * @expectedExceptionMessageRegExp ~should be child of \\RuntimeException$~
     */
    public function My_exceptions_are_in_family_tree()
    {
        parent::My_exceptions_are_in_family_tree();
    }

    protected function getTestedNamespace()
    {
        return __NAMESPACE__ . '\DummyExceptionsHierarchy\LogicExceptionAsRuntimeException';
    }

    protected function getExceptionsSubDir()
    {
        return false;
    }

    protected function getRootNamespace()
    {
        return $this->getTestedNamespace();
    }

    protected function getExceptionClassesSkippedFromUsageTest()
    {
        return [
            IThoughIAmRuntimeException::class,
        ];
    }

}