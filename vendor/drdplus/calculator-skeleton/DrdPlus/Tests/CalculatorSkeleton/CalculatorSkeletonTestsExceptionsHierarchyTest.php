<?php declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

class CalculatorSkeletonTestsExceptionsHierarchyTest extends \DrdPlus\Tests\RulesSkeleton\RulesSkeletonTestsExceptionsHierarchyTest
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