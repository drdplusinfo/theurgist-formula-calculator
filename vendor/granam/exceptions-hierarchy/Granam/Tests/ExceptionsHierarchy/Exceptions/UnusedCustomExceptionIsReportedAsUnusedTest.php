<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

class UnusedCustomExceptionIsReportedAsUnusedTest extends AbstractExceptionsHierarchyTest
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
    }/** @noinspection SenselessProxyMethodInspection */

    /**
     * @test
* @expectExceptionMessageRegExp ~DoesAnybodyWantMe~
     */
    public function My_exceptions_are_used()
    {
        $this->expectException(\Granam\ExceptionsHierarchy\Exceptions\UnusedException::class);
        // overloaded parent annotation
        parent::My_exceptions_are_used();
    }

}