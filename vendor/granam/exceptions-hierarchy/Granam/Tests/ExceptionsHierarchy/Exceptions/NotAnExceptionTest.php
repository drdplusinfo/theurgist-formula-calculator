<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

class NotAnExceptionTest extends AbstractExceptionsHierarchyTest
{
    /** @noinspection SenselessProxyMethodInspection */
    /**
     * @test
* @expectExceptionMessageRegExp ~.+ should be child of \\Exception$~
     */
    public function My_exceptions_are_in_family_tree()
    {
        $this->expectException(\Granam\ExceptionsHierarchy\Exceptions\InvalidExceptionHierarchy::class);
        parent::My_exceptions_are_in_family_tree();
    }

    protected function getTestedNamespace()
    {
        return __NAMESPACE__ . '\DummyExceptionsHierarchy\NotAnException';
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
            DummyExceptionsHierarchy\NotAnException\IThoughtIAmException::class,
        ];
    }

}