<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

use Granam\ExceptionsHierarchy\Exceptions\InvalidExceptionHierarchy;

class WithSameNamedExternalNonParentExceptionHierarchyTest extends AbstractExceptionsHierarchyTest
{
    /**
     * @return string
     */
    protected function getTestedNamespace()
    {
        return $this->getRootNamespace();
    }

    /**
     * @return string
     */
    protected function getRootNamespace()
    {
        return __NAMESPACE__ . '\\DummyExceptionsHierarchy\\WithSameNamedExternalNonParent';
    }

    /**
     * @return false
     */
    protected function getExceptionsSubDir()
    {
        return false; // exceptions are directly in the tested namespace
    }

    protected function getExternalRootNamespaces()
    {
        return [
            __NAMESPACE__ . '\\DummyExceptionsHierarchy\\WithSameNamedParent',
        ];
    }

    /**
     * @return false
     */
    protected function getExternalRootExceptionsSubDir()
    {
        return false; // exceptions are directly in the external root namespace
    }

    /**
     * @test
     */
    public function My_exceptions_are_in_family_tree()
    {
        $this->expectException(InvalidExceptionHierarchy::class);
        $this->expectExceptionMessageRegExp('~Exception .+\\\WithSameNamedExternalNonParent\\\IAmSameNamed .+ parent .+\\\WithSameNamedParent\\\IAmSameNamed~');
        parent::My_exceptions_are_in_family_tree();
    }

    protected function getExceptionClassesSkippedFromUsageTest()
    {
        return [
            DummyExceptionsHierarchy\WithSameNamedExternalNonParent\IAmSameNamed::class,
        ];
    }

}