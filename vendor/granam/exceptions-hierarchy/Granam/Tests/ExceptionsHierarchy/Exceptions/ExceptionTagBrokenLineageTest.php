<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

use Granam\ExceptionsHierarchy\Exceptions\InvalidExceptionHierarchy;

class ExceptionTagBrokenLineageTest extends AbstractExceptionsHierarchyTest
{
    /**
     * @test
     */
    public function My_exceptions_are_in_family_tree()
    {
        $this->expectException(InvalidExceptionHierarchy::class);
        $this->expectExceptionMessageRegExp('~^Tag .+\\\BrokenLineage\\\ExceptionTagWithoutParent\\\Exception should be child of .+\\\BrokenLineage\\\Exception~');
        parent::My_exceptions_are_in_family_tree();
    }

    /**
     * @return string
     */
    protected function getTestedNamespace()
    {
        return $this->getRootNamespace() . '\\ExceptionTagWithoutParent';
    }

    /**
     * @return string
     */
    protected function getRootNamespace()
    {
        return __NAMESPACE__ . '\\DummyExceptionsHierarchy\\BrokenLineage';
    }

    /**
     * @return false
     */
    protected function getExceptionsSubDir()
    {
        return false; // exceptions are directly in the tested namespace
    }

}