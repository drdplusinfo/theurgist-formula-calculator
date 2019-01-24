<?php
declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\CookiesService;
use DrdPlus\RulesSkeleton\ServicesContainer;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;

class ServicesContainerTest extends AbstractContentTest
{
    /**
     * @test
     */
    public function I_can_get_web_versions(): void
    {
        $servicesContainerClass = static::getSutClass();
        /** @var ServicesContainer $servicesContainer */
        $servicesContainer = new $servicesContainerClass($this->getConfiguration(), $this->createHtmlHelper());
        self::assertNotEmpty($servicesContainer->getCurrentWebVersion());
    }

    /**
     * @test
     */
    public function I_can_get_web_files(): void
    {
        $servicesContainerClass = static::getSutClass();
        /** @var ServicesContainer $servicesContainer */
        $servicesContainer = new $servicesContainerClass($this->getConfiguration(), $this->createHtmlHelper());
        self::assertNotEmpty($servicesContainer->getWebFiles());
    }

    /**
     * @test
     */
    public function I_can_get_request(): void
    {
        $servicesContainerClass = static::getSutClass();
        /** @var ServicesContainer $servicesContainer */
        $servicesContainer = new $servicesContainerClass($this->getConfiguration(), $this->createHtmlHelper());
        self::assertNotEmpty($servicesContainer->getRequest());
    }

    /**
     * @test
     */
    public function I_can_get_page_cache_with_properly_set_production_mode(): void
    {
        $servicesContainerClass = static::getSutClass();
        /** @var ServicesContainer $servicesContainer */
        $servicesContainer = new $servicesContainerClass($this->getConfiguration(), $this->createHtmlHelper(null, true /* in production */));
        self::assertTrue($servicesContainer->getPassedWebCache()->isInProduction(), 'Expected page cache to be in production mode');
        $servicesContainerClass = static::getSutClass();
        /** @var ServicesContainer $servicesContainer */
        $servicesContainer = new $servicesContainerClass($this->getConfiguration(), $this->createHtmlHelper());
        self::assertFalse($servicesContainer->getPassedWebCache()->isInProduction(), 'Expected page cache to be not in production mode');
    }

    /**
     * @test
     */
    public function I_can_get_cookies_service(): void
    {
        $servicesContainerClass = static::getSutClass();
        /** @var ServicesContainer $servicesContainer */
        $servicesContainer = new $servicesContainerClass($this->getConfiguration(), $this->createHtmlHelper());
        self::assertEquals(new CookiesService(), $servicesContainer->getCookiesService());
    }
}