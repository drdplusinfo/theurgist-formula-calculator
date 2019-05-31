<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton\Partials;

use DrdPlus\CalculatorSkeleton\CalculatorConfiguration;
use DrdPlus\CalculatorSkeleton\CalculatorServicesContainer;
use DrdPlus\RulesSkeleton\Configuration;
use DrdPlus\RulesSkeleton\Dirs;
use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\RulesSkeleton\ServicesContainer;
use DrdPlus\Tests\CalculatorSkeleton\TestsConfiguration;

/**
 * @method CalculatorConfiguration getConfiguration(Dirs $dirs = null)
 * @method static assertTrue($value, $message = '')
 * @method static assertFalse($value, $message = '')
 * @method static assertSame($expected, $actual, $message = '')
 * @method static assertNotSame($expected, $actual, $message = '')
 * @method static assertNotEmpty($value, $message = '')
 * @method static fail($message)
 */
trait CalculatorContentTestTrait
{

    /**
     * @param Configuration|CalculatorConfiguration|null $configuration
     * @param HtmlHelper|null $htmlHelper
     * @return ServicesContainer
     */
    protected function createServicesContainer(
        Configuration $configuration = null,
        HtmlHelper $htmlHelper = null
    ): ServicesContainer
    {
        $servicesContainerClass = $this->getServicesContainerClass();
        return new $servicesContainerClass(
            $configuration ?? $this->getConfiguration(),
            $htmlHelper ?? $this->createHtmlHelper($this->getDirs())
        );
    }

    protected function getServicesContainerClass(): string
    {
        return CalculatorServicesContainer::class;
    }

    /**
     * @return string|CalculatorConfiguration
     */
    protected function getConfigurationClass(): string
    {
        return CalculatorConfiguration::class;
    }

    /**
     * @return TestsConfiguration|\DrdPlus\Tests\RulesSkeleton\TestsConfiguration
     */
    protected function getTestsConfiguration(): \DrdPlus\Tests\RulesSkeleton\TestsConfiguration
    {
        static $testsConfiguration;
        if ($testsConfiguration === null) {
            $testsConfiguration = TestsConfiguration::createFromYaml(\DRD_PLUS_TESTS_ROOT . '/tests_configuration.yml');
        }

        return $testsConfiguration;
    }

    protected function isSkeletonChecked(string $skeletonDocumentRoot = null): bool
    {
        $documentRootRealPath = \realpath($this->getProjectRoot());
        self::assertNotEmpty($documentRootRealPath, 'Can not find out real path of document root ' . \var_export($this->getProjectRoot(), true));
        $skeletonRootRealPath = \realpath($skeletonDocumentRoot ?? __DIR__ . '/../../../..');
        self::assertNotEmpty($skeletonRootRealPath, 'Can not find out real path of skeleton root ' . \var_export($skeletonRootRealPath, true));

        return $documentRootRealPath === $skeletonRootRealPath;
    }

    /**
     * @param Dirs|null $dirs
     * @param bool $inDevMode
     * @param bool $inForcedProductionMode
     * @param bool $shouldHideCovered
     * @return HtmlHelper|\Mockery\MockInterface
     */
    protected function createHtmlHelper(
        Dirs $dirs = null,
        bool $inForcedProductionMode = false,
        bool $inDevMode = false,
        bool $shouldHideCovered = false
    ): HtmlHelper
    {
        return new HtmlHelper(
            $dirs ?? $this->getDirs(),
            $this->getEnvironment(),
            $inDevMode,
            $inForcedProductionMode,
            $shouldHideCovered
        );
    }

    protected function isCalculatorSkeletonChecked(): bool
    {
        return $this->isSkeletonChecked($this->getCalculatorSkeletonProjectRoot());
    }

    private function getCalculatorSkeletonProjectRoot(): string
    {
        return __DIR__ . '/../../../..';
    }
}