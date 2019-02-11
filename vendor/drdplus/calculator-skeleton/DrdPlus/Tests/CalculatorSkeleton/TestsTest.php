<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

class TestsTest extends \DrdPlus\Tests\RulesSkeleton\TestsTest
{
    /**
     * @test
     * @throws \ReflectionException
     */
    public function All_rules_skeleton_tests_are_used(): void
    {
        $reflectionClass = new \ReflectionClass(static::class);
        $rulesSkeletonDir = \dirname($reflectionClass->getFileName());
        foreach ($this->getClassesFromDir($rulesSkeletonDir) as $rulesSkeletonTestClass) {
            if (\is_a($rulesSkeletonTestClass, \Throwable::class, true)) {
                continue;
            }
            $rulesSkeletonTestClassReflection = new \ReflectionClass($rulesSkeletonTestClass);
            if ($rulesSkeletonTestClassReflection->isAbstract()
                || $rulesSkeletonTestClassReflection->isInterface()
                || $rulesSkeletonTestClassReflection->isTrait()
            ) {
                continue;
            }
            $expectedCalculatorTestClass = \str_replace('\\RulesSkeleton', '\\CalculatorSkeleton', $rulesSkeletonTestClass);
            self::assertTrue(
                \class_exists($expectedCalculatorTestClass),
                "Missing test class {$expectedCalculatorTestClass} adopted from rules skeleton test class {$rulesSkeletonTestClass}"
            );
            self::assertTrue(
                \is_a($expectedCalculatorTestClass, $rulesSkeletonTestClass, true),
                "$expectedCalculatorTestClass should be a child of $rulesSkeletonTestClass"
            );
        }
    }
}