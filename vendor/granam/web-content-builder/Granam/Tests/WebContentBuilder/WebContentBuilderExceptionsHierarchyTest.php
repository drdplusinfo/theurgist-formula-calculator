<?php
declare(strict_types=1);

namespace Granam\Tests\WebContentBuilder;

use Granam\Tests\ExceptionsHierarchy\Exceptions\AbstractExceptionsHierarchyTest;

class WebContentBuilderExceptionsHierarchyTest extends AbstractExceptionsHierarchyTest
{
    protected function getTestedNamespace(): string
    {
        return $this->getRootNamespace();
    }

    protected function getRootNamespace(): string
    {
        return \str_replace('Tests\\', '', __NAMESPACE__);
    }

}