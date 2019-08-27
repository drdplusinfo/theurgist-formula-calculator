<?php declare(strict_types=1);

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
* @expectExceptionMessageRegExp ~PleaseUseMeIFeelAlone~
     */
    public function My_exceptions_are_used()
    {
        $this->expectException(\Granam\ExceptionsHierarchy\Exceptions\UnusedException::class);
        // overloaded parent annotation
        parent::My_exceptions_are_used();
    }

}