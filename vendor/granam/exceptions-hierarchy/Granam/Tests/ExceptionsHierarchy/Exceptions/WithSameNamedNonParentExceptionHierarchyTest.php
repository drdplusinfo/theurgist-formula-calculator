<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

use Granam\ExceptionsHierarchy\Exceptions\InvalidExceptionHierarchy;

class WithSameNamedNonParentExceptionHierarchyTest extends AbstractExceptionsHierarchyTest
{
    /**
     * @return string
     */
    protected function getTestedNamespace()
    {
        return $this->getRootNamespace() . '\\WithSameNamedNonParent\\Children';
    }

    /**
     * @return string
     */
    protected function getRootNamespace()
    {
        return __NAMESPACE__ . '\\DummyExceptionsHierarchy';
    }

    /**
     * @return false
     */
    protected function getExceptionsSubDir()
    {
        return false; // exceptions are directly in the tested namespace
    }

    /**
     * @test
     */
    public function My_exceptions_are_in_family_tree()
    {
        $this->expectException(InvalidExceptionHierarchy::class);
        $this->expectExceptionMessageRegExp('~^Exception .+\\\WithSameNamedNonParent\\\Children\\\IAmSameNamedButNotFromFamily should extends parent .+\\\WithSameNamedNonParent\\\IAmSameNamedButNotFromFamily~');
        parent::My_exceptions_are_in_family_tree();
    }

    protected function getExceptionClassesSkippedFromUsageTest()
    {
        return [
            DummyExceptionsHierarchy\WithSameNamedNonParent\Children\IAmSameNamedButNotFromFamily::class,
            DummyExceptionsHierarchy\WithSameNamedNonParent\IAmSameNamedButNotFromFamily::class,
            DummyExceptionsHierarchy\IAmLogicException::class,
            DummyExceptionsHierarchy\IAmRuntimeException::class,
        ];
    }

}