<?php
namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

class InvalidExceptionsUsageRootDirCauseExceptionTest extends AbstractExceptionsHierarchyTest
{
    /**
     * @return string
     */
    protected function getTestedNamespace()
    {
        return __NAMESPACE__ . '\\DummyExceptionsHierarchy\\UnusedCustomException';
    }

    /**
     * @return string
     */
    protected function getRootNamespace()
    {
        return $this->getTestedNamespace();
    }

    /**
     * @return false
     */
    protected function getExceptionsSubDir()
    {
        return false; // exceptions are directly in the tested namespace
    }

    protected function getExceptionsUsageRootDir()
    {
        return __DIR__ . '/AnybodyAtHome';
    }

    public function My_exceptions_are_in_family_tree()
    {
        // disabled
        return false;
    }/** @noinspection SenselessProxyMethodInspection */

    /**
     * @test
     * @expectedException \Granam\ExceptionsHierarchy\Exceptions\FolderCanNotBeRead
     */
    public function My_exceptions_are_used()
    {
        parent::My_exceptions_are_used();
    }

}