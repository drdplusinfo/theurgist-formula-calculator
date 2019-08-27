<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

use Granam\ExceptionsHierarchy\Exceptions\ExceptionIsNotTaggedProperly;

class NotTaggedExceptionTest extends AbstractExceptionsHierarchyTest
{
    /**
     * @test
     */
    public function My_exceptions_are_in_family_tree()
    {
        $this->expectException(ExceptionIsNotTaggedProperly::class);
        $this->expectExceptionMessageRegExp('~^Class .+\\\NotTaggedExceptionWithout\\\IToughIAmTagged has to be tagged by Exception interface$~');
        parent::My_exceptions_are_in_family_tree();
    }

    protected function getTestedNamespace()
    {
        return __NAMESPACE__ . '\DummyExceptionsHierarchy\NotTaggedExceptionWithout';
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
            DummyExceptionsHierarchy\NotTaggedExceptionWithout\IToughIAmTagged::class,
        ];
    }

}