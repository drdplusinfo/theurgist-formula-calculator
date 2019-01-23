<?php
namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

use Granam\Tests\ExceptionsHierarchy\Exceptions\DummyExceptionsHierarchy\IAmLogicException;
use Granam\Tests\ExceptionsHierarchy\Exceptions\DummyExceptionsHierarchy\IAmRuntimeException;

class InheritedTagsTest extends AbstractExceptionsHierarchyTest
{
    protected function getTestedNamespace()
    {
        return $this->getRootNamespace() . '\InheritedTags';
    }

    protected function getExceptionsSubDir()
    {
        return false;
    }

    protected function getRootNamespace()
    {
        return __NAMESPACE__ . '\DummyExceptionsHierarchy';
    }

    protected function getExceptionClassesSkippedFromUsageTest()
    {
        return [
            IAmLogicException::class,
            IAmRuntimeException::class,
        ];
    }

}