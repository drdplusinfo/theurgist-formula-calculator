<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton\Partials;

use DrdPlus\Tests\RulesSkeleton\TestsConfiguration;
use Granam\Tests\Tools\TestWithMockery;

class TestsConfigurationReaderTest extends TestWithMockery
{
    /**
     * @test
     * @throws \ReflectionException
     */
    public function Reader_interface_covers_all_tests_configuration_getters(): void
    {
        $testsConfigurationReaderReflection = new \ReflectionClass($this->getTestsConfigurationReaderClass());
        foreach ($this->getGettersFromTestsConfiguration() as $getter) {
            self::assertTrue(
                $testsConfigurationReaderReflection->hasMethod($getter),
                static::getSutClass() . " is missing $getter method"
            );
            self::assertTrue(
                $testsConfigurationReaderReflection->getMethod($getter)->isPublic(),
                static::getSutClass() . " should has $getter as public"
            );
        }
    }

    protected function getTestsConfigurationReaderClass(): string
    {
        return static::getSutClass(null, '~Test$~');
    }

    /**
     * @return array|string[]
     * @throws \ReflectionException
     */
    protected function getGettersFromTestsConfiguration(): array
    {
        $testsConfigurationClass = $this->getTestsConfigurationClass();
        $testsConfigurationReflection = new \ReflectionClass($testsConfigurationClass);
        $methods = $testsConfigurationReflection->getMethods(\ReflectionMethod::IS_PUBLIC ^ \ReflectionMethod::IS_ABSTRACT);
        $getters = [];
        $testsConfigurationClasses = \array_unique([ // descendants can inherit current configuration class
            TestsConfiguration::class,
            TestsConfigurationReader::class,
            $testsConfigurationClass,
            $this->getTestsConfigurationReaderClass(),
        ]);
        foreach ($methods as $method) {
            if ($method->getNumberOfParameters() > 0
                || !$method->hasReturnType()
                || \in_array($method->getReturnType()->getName(), $testsConfigurationClasses, true)
            ) {
                continue;
            }
            $getters[] = $method->getName();
        }

        return $getters;
    }

    protected function getTestsConfigurationClass(): string
    {
        return static::getSutClass(null, '~\\\Partials(\\\.+)ReaderTest$~');
    }

}