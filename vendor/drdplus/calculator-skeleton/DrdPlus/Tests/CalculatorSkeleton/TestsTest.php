<?php
declare(strict_types = 1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\Tests\RulesSkeleton\RulesControllerTest;

class TestsTest extends \DrdPlus\Tests\RulesSkeleton\TestsTest
{

	use Partials\AbstractContentTestTrait;

	/**
	 * @test
	 * @throws \ReflectionException
	 */
	public function All_rules_skeleton_tests_are_used(): void
	{
		$reflectionClass = new \ReflectionClass(static::class);
		$rulesSkeletonDir = \dirname($reflectionClass->getFileName());
		foreach ($this->getClassesFromDir($rulesSkeletonDir) as $rulesSkeletonTestClass) {
			if (\is_a($rulesSkeletonTestClass, \Throwable::class, true)
				|| \is_a($rulesSkeletonTestClass, RulesControllerTest::class, true) // it is solved via CalculatorController
			) {
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

	private function getClassesFromDir(string $dir): array
	{
		$classes = [];
		foreach (\scandir($dir, SCANDIR_SORT_NONE) as $folder) {
			if ($folder === '.' || $folder === '..') {
				continue;
			}
			if (!\preg_match('~\.php$~', $folder)) {
				if (\is_dir($dir . '/' . $folder)) {
					foreach ($this->getClassesFromDir($dir . '/' . $folder) as $class) {
						$classes[] = $class;
					}
				}
				continue;
			}
			self::assertNotEmpty(
				\preg_match('~(?<className>DrdPlus/[^/].+)\.php~', $dir . '/' . $folder, $matches),
				"DrdPlus class name has not been determined from $dir/$folder"
			);
			$classes[] = \str_replace('/', '\\', $matches['className']);
		}

		return $classes;
	}
}