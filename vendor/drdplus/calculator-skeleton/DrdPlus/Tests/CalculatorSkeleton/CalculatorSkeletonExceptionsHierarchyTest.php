<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

class CalculatorSkeletonExceptionsHierarchyTest extends RulesSkeletonExceptionsHierarchyTest
{
    /**
     * @return string
     */
    protected function getTestedNamespace(): string
    {
        return str_replace('\Tests', '', __NAMESPACE__);
    }

    /**
     * @return string
     */
    protected function getRootNamespace(): string
    {
        return $this->getTestedNamespace();
    }

}