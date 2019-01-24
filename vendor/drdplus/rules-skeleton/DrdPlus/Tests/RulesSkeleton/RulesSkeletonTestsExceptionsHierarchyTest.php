<?php
declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use Granam\Tests\ExceptionsHierarchy\Exceptions\AbstractExceptionsHierarchyTest;

class RulesSkeletonTestsExceptionsHierarchyTest extends AbstractExceptionsHierarchyTest
{
    protected function getTestedNamespace(): string
    {
        return $this->getRootNamespace();
    }

    protected function getRootNamespace(): string
    {
        return __NAMESPACE__;
    }

}