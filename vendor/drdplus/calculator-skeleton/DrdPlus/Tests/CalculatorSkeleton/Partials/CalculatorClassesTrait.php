<?php declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton\Partials;

use DrdPlus\AttackSkeleton\AttackServicesContainer;
use DrdPlus\CalculatorSkeleton\CalculatorApplication;
use DrdPlus\CalculatorSkeleton\CalculatorConfiguration;
use DrdPlus\CalculatorSkeleton\CalculatorServicesContainer;
use DrdPlus\Tests\CalculatorSkeleton\TestsConfiguration;

trait CalculatorClassesTrait
{
    /**
     * @return string|CalculatorApplication
     */
    protected function getRulesApplicationClass(): string
    {
        return defined('DRD_PLUS_APPLICATION_CLASS')
            ? DRD_PLUS_APPLICATION_CLASS
            : CalculatorApplication::class;
    }

    /**
     * @return string|CalculatorServicesContainer
     */
    protected function getServicesContainerClass(): string
    {
        return defined('DRD_PLUS_SERVICES_CONTAINER_CLASS')
            ? DRD_PLUS_SERVICES_CONTAINER_CLASS
            : CalculatorServicesContainer::class;
    }

    /**
     * @return string|CalculatorConfiguration
     */
    protected function getConfigurationClass(): string
    {
        return defined('DRD_PLUS_CONFIGURATION_CLASS')
            ? DRD_PLUS_CONFIGURATION_CLASS
            : CalculatorConfiguration::class;
    }

    /**
     * @return string|TestsConfiguration
     */
    protected function getTestsConfigurationClass(): string
    {
        return defined('DRD_PLUS_TEST_CONFIGURATION_CLASS')
            ? DRD_PLUS_TEST_CONFIGURATION_CLASS
            : TestsConfiguration::class;
    }
}