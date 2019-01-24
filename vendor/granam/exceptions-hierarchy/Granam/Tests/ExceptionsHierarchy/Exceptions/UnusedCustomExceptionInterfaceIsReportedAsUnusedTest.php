<?php
namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

class UnusedCustomExceptionInterfaceIsReportedAsUnusedTest extends AbstractExceptionsHierarchyTest
{
    /**
     * @return string
     */
    protected function getTestedNamespace()
    {
        return __NAMESPACE__ . '\\DummyExceptionsHierarchy\\UnusedCustomExceptionInterface';
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
    }/** @noinspection SenselessProxyMethodInspection */

    /**
     * @test
     * @expectedException \Granam\ExceptionsHierarchy\Exceptions\UnusedException
     * @expectedExceptionMessageRegExp ~PleaseUseMeIFeelAlone~
     */
    public function My_exceptions_are_used()
    {
        // overloaded parent annotation
        parent::My_exceptions_are_used();
    }

}