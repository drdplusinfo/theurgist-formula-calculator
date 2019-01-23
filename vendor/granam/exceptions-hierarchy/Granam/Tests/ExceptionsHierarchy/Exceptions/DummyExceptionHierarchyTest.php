<?php
namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

use Granam\Tests\ExceptionsHierarchy\Exceptions\DummyExceptionsHierarchy\IAmLogicException;
use Granam\Tests\ExceptionsHierarchy\Exceptions\DummyExceptionsHierarchy\IAmRuntimeException;

class DummyExceptionHierarchyTest extends AbstractExceptionsHierarchyTest
{
    /**
     * @return string
     */
    protected function getTestedNamespace()
    {
        return __NAMESPACE__ . '\\DummyExceptionsHierarchy';
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
    }

    /**
     * @return array|string[]
     */
    protected function getExceptionClassesSkippedFromUsageTest()
    {
        return [
            IAmLogicException::class,
            IAmRuntimeException::class,
        ];
    }
}