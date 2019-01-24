<?php
namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

class NotTaggedExceptionTest extends AbstractExceptionsHierarchyTest
{
    /** @noinspection SenselessProxyMethodInspection */

    /**
     * @test
     * @expectedException \Granam\ExceptionsHierarchy\Exceptions\ExceptionIsNotTaggedProperly
     * @expectedExceptionMessageRegExp ~^Class .+\\NotTaggedExceptionWithout\\IToughIAmTagged has to be tagged by Exception interface$~
     */
    public function My_exceptions_are_in_family_tree()
    {
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