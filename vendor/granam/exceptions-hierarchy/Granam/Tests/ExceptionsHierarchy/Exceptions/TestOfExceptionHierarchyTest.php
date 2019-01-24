<?php
namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

class TestOfExceptionHierarchyTest extends AbstractExceptionsHierarchyTest
{
    /**
     * @return string
     */
    protected function getTestedNamespace()
    {
        return 'Granam\ExceptionsHierarchy';
    }

    /**
     * @return string
     */
    protected function getRootNamespace()
    {
        return $this->getTestedNamespace();
    }

}