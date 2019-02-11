<?php
declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use Composer\Plugin\PluginInterface;
use DrdPlus\RulesSkeleton\SkeletonInjectorComposerPlugin;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;
use DrdPlus\Tests\RulesSkeleton\Partials\TestsConfigurationReaderTest;

class TestsTest extends AbstractContentTest
{
    /**
     * @test
     * @throws \ReflectionException
     */
    public function Every_test_lives_in_drd_plus_tests_namespace(): void
    {
        $reflectionClass = new \ReflectionClass(static::class);
        $testsDir = \dirname($reflectionClass->getFileName());
        $testClasses = $this->getClassesFromDir($testsDir);
        self::assertNotEmpty($testClasses, "No test classes found in {$testsDir}");
        foreach ($testClasses as $testClass) {
            self::assertStringStartsWith(
                'DrdPlus\\Tests',
                (new \ReflectionClass($testClass))->getNamespaceName(),
                "Class {$testClass} should be in DrdPlus\\Test namespace"
            );
        }
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function Every_test_reflects_test_class_namespace(): void
    {
        $referenceTestClass = new \ReflectionClass($this->getRulesApplicationTestClass());
        $referenceTestDir = \dirname($referenceTestClass->getFileName());
        $testingClassesWithoutSut = $this->getTestingClassesWithoutSut();
        foreach ($this->getClassesFromDir($referenceTestDir) as $testClass) {
            $testClassReflection = new \ReflectionClass($testClass);
            if ($testClassReflection->isAbstract()
                || $testClassReflection->isInterface()
                || $testClassReflection->isTrait()
                || \in_array($testClass, $testingClassesWithoutSut, true)
            ) {
                continue;
            }
            if ($testClass === SkeletonInjectorComposerPlugin::class) {
                self::assertTrue(
                    \interface_exists(PluginInterface::class),
                    "Composer package is required for this test, include it by\ncomposer require --dev composer/composer"
                );
            }
            $testedClass = static::getSutClass($testClass);
            self::assertTrue(
                \class_exists($testedClass),
                "What is testing $testClass? Class $testedClass has not been found."
            );
        }
    }

    protected function getTestingClassesWithoutSut(): array
    {
        return [
            AnchorsTest::class,
            GraphicsTest::class,
            TestsConfigurationTest::class,
            RulesSkeletonExceptionsHierarchyTest::class,
            RulesSkeletonTestsExceptionsHierarchyTest::class,
            TablesTest::class,
            GoogleTest::class,
            WebContentVersionTest::class,
            ComposerConfigTest::class,
            TracyTest::class,
            TrialTest::class,
            PageTitleTest::class,
            StandardModeTest::class,
            self::class,
            static::class,
            CoveredPartsCanBeHiddenTest::class,
            PassingTest::class,
            ContactsContentTest::class,
            DevModeTest::class,
            CalculationsTest::class,
            SourceCodeLinksTest::class,
            TestsConfigurationReaderTest::class,
            TableOfContentsTest::class,
            GitTest::class,
        ];
    }

    private function getRulesApplicationTestClass(): string
    {
        $rulesApplicationTestClass = \str_replace('DrdPlus\\', 'DrdPlus\\Tests\\', $this->getRulesApplicationClass()) . 'Test';
        self::assertTrue(
            \class_exists($rulesApplicationTestClass),
            'Estimated rules application test class does not exist: ' . $rulesApplicationTestClass
        );

        return $rulesApplicationTestClass;
    }

    protected function getClassesFromDir(string $dir): array
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
            $class = \str_replace('/', '\\', $matches['className']);
            self::assertTrue(
                \class_exists($class) || \trait_exists($class) || \interface_exists($class),
                'Estimated test class does not exist: ' . $class
            );
            $classes[] = $class;
        }

        return $classes;
    }
}