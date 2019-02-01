<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\CalculatorSkeleton\CalculatorApplication;

class CalculatorSkeletonExceptionsHierarchyTest extends RulesSkeletonExceptionsHierarchyTest
{
    use Partials\AbstractContentTestTrait;

    /**
     * @return string
     * @throws \ReflectionException
     */
    protected function getTestedNamespace(): string
    {
        return (new \ReflectionClass(CalculatorApplication::class))->getNamespaceName();
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    protected function getRootNamespace(): string
    {
        return $this->getTestedNamespace();
    }

}