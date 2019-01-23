<?php
namespace Granam\Tests\ExceptionsHierarchy\Exceptions;

class ExternalSingleParentRootNamespaceTest extends ExternalParentRootNamespaceTest
{
    protected function getExternalRootNamespaces()
    {
        return '\Granam\ExceptionsHierarchy'; // string instead of array
    }

}
