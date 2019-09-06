<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton\Web;

use DrdPlus\RulesSkeleton\Configuration;
use DrdPlus\RulesSkeleton\HomepageDetector;
use DrdPlus\RulesSkeleton\PathProvider;
use DrdPlus\RulesSkeleton\Web\Menu;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;
use Granam\WebContentBuilder\HtmlDocument;
use Gt\Dom\Element;
use Mockery\MockInterface;

class MenuTest extends AbstractContentTest
{
    /**
     * @test
     * @dataProvider provideConfigurationToShowHomeButton
     * @param bool $showHomeButton
     * @param bool $showHomeButtonOnHomepage
     * @param bool $showHomeButtonOnRoutes
     */
    public function I_can_show_home_button(bool $showHomeButton, bool $showHomeButtonOnHomepage, bool $showHomeButtonOnRoutes): void
    {
        $configuration = $this->createCustomConfiguration(
            [Configuration::WEB => [
                Configuration::SHOW_HOME_BUTTON => $showHomeButton,
                Configuration::SHOW_HOME_BUTTON_ON_HOMEPAGE => $showHomeButtonOnHomepage,
                Configuration::SHOW_HOME_BUTTON_ON_ROUTES => $showHomeButtonOnRoutes,
            ]]
        );
        self::assertTrue($configuration->isShowHomeButton(), 'Expected configuration with shown home button');
        foreach ([true, false] as $isHomepageRequested) {
            $menuOnHomepage = $this->createMenu($configuration, $isHomepageRequested);
            if ($this->isSkeletonChecked()) {
                $htmlDocument = new HtmlDocument(<<<HTML
<html lang="cs">
<body>
{$menuOnHomepage->getValue()}
</body>
</html>
HTML
                );
                /** @var Element $homeButton */
                $homeButton = $htmlDocument->getElementById('homeButton');
                self::assertNotEmpty($homeButton, 'Home button is missing');
                self::assertSame(
                    'https://www.drdplus.info',
                    $homeButton->getAttribute('href'), 'Link of home button should lead to home'
                );
            }
        }
    }

    public function provideConfigurationToShowHomeButton(): array
    {
        return [
            'show home button global config' => [true, false, false],
            'show home button local configs' => [false, true, true],
        ];
    }

    /**
     * @test
     * @dataProvider provideConfigurationToShowHomeButtonOnHomepage
     * @param bool $showHomeButton
     * @param bool $showHomeButtonOnHomepage
     * @param bool $showHomeButtonOnRoutes
     */
    public function I_can_show_home_button_on_homepage(bool $showHomeButton, bool $showHomeButtonOnHomepage, bool $showHomeButtonOnRoutes): void
    {
        $configuration = $this->createCustomConfiguration(
            [Configuration::WEB => [
                Configuration::SHOW_HOME_BUTTON => $showHomeButton,
                Configuration::SHOW_HOME_BUTTON_ON_HOMEPAGE => $showHomeButtonOnHomepage,
                Configuration::SHOW_HOME_BUTTON_ON_ROUTES => $showHomeButtonOnRoutes,
            ]]
        );
        self::assertTrue($configuration->isShowHomeButtonOnHomepage(), 'Expected configuration with shown home button on homepage');
        $menu = $this->createMenu($configuration, true);
        if ($this->isSkeletonChecked()) {
            $htmlDocument = new HtmlDocument(<<<HTML
<html lang="cs">
<body>
{$menu->getValue()}
</body>
</html>
HTML
            );
            /** @var Element $homeButton */
            $homeButton = $htmlDocument->getElementById('homeButton');
            self::assertNotEmpty($homeButton, 'Home button is missing');
            self::assertSame(
                'https://www.drdplus.info',
                $homeButton->getAttribute('href'), 'Link of home button should lead to home'
            );
        }
    }

    public function provideConfigurationToShowHomeButtonOnHomepage(): array
    {
        return [
            'show home button global config' => [true, false, false],
            'show home button on homepage only' => [false, true, false],
        ];
    }

    /**
     * @test
     * @dataProvider provideConfigurationToHideHomeButtonOnHomepage
     * @param bool $showHomeButton
     * @param bool $showHomeButtonOnHomepage
     * @param bool $showHomeButtonOnRoutes
     */
    public function I_can_hide_home_button_on_homepage(bool $showHomeButton, bool $showHomeButtonOnHomepage, bool $showHomeButtonOnRoutes): void
    {
        $configuration = $this->createCustomConfiguration(
            [Configuration::WEB => [
                Configuration::SHOW_HOME_BUTTON => $showHomeButton,
                Configuration::SHOW_HOME_BUTTON_ON_HOMEPAGE => $showHomeButtonOnHomepage,
                Configuration::SHOW_HOME_BUTTON_ON_ROUTES => $showHomeButtonOnRoutes,
            ]]
        );
        self::assertFalse($configuration->isShowHomeButtonOnHomepage(), 'Expected configuration with hidden home button');
        $menu = $this->createMenu($configuration, true);
        if ($this->isSkeletonChecked()) {
            $htmlDocument = new HtmlDocument(<<<HTML
<html lang="cs">
<body>
{$menu->getValue()}
</body>
</html>
HTML
            );
            $homeButton = $htmlDocument->getElementById('homeButton');
            self::assertEmpty($homeButton, 'Home button should not be used at all');
        }
    }

    public function provideConfigurationToHideHomeButtonOnHomepage(): array
    {
        return [
            'hide home button everywhere' => [false, false, false],
            'show home button only on route' => [false, false, true],
        ];
    }

    private function createMenu(Configuration $configuration, bool $isHomepageRequested): Menu
    {
        return new Menu($configuration, $this->createHomepageDetector($isHomepageRequested));
    }

    /**
     * @param bool $isHomepageRequested
     * @return HomepageDetector|MockInterface
     */
    private function createHomepageDetector(bool $isHomepageRequested): HomepageDetector
    {
        $homepageDetector = $this->mockery(HomepageDetector::class);
        $homepageDetector->shouldReceive('isHomepageRequested')
            ->andReturn($isHomepageRequested);
        return $homepageDetector;
    }

    /**
     * @test
     * @dataProvider provideConfigurationToShowHomeButtonOnRoutes
     * @param bool $showHomeButton
     * @param bool $showHomeButtonOnHomepage
     * @param bool $showHomeButtonOnRoutes
     */
    public function I_can_show_home_button_on_routes(bool $showHomeButton, bool $showHomeButtonOnHomepage, bool $showHomeButtonOnRoutes): void
    {
        $configuration = $this->createCustomConfiguration(
            [Configuration::WEB => [
                Configuration::SHOW_HOME_BUTTON => $showHomeButton,
                Configuration::SHOW_HOME_BUTTON_ON_HOMEPAGE => $showHomeButtonOnHomepage,
                Configuration::SHOW_HOME_BUTTON_ON_ROUTES => $showHomeButtonOnRoutes,
            ]]
        );
        self::assertTrue($configuration->isShowHomeButtonOnRoutes(), 'Expected configuration with shown home button on routes');
        $menu = $this->createMenu($configuration, false);
        if ($this->isSkeletonChecked()) {
            $htmlDocument = new HtmlDocument(<<<HTML
<html lang="cs">
<body>
{$menu->getValue()}
</body>
</html>
HTML
            );
            /** @var Element $homeButton */
            $homeButton = $htmlDocument->getElementById('homeButton');
            self::assertNotEmpty($homeButton, 'Home button is missing');
            self::assertSame(
                'https://www.drdplus.info',
                $homeButton->getAttribute('href'), 'Link of home button should lead to home'
            );
        }
    }

    public function provideConfigurationToShowHomeButtonOnRoutes(): array
    {
        return [
            'show home button global config' => [true, false, false],
            'show home button on routes only' => [false, false, true],
        ];
    }

    /**
     * @test
     * @dataProvider provideConfigurationToHideHomeButtonOnRoutes
     * @param bool $showHomeButton
     * @param bool $showHomeButtonOnHomepage
     * @param bool $showHomeButtonOnRoutes
     */
    public function I_can_hide_home_button_on_routes(bool $showHomeButton, bool $showHomeButtonOnHomepage, bool $showHomeButtonOnRoutes): void
    {
        $configuration = $this->createCustomConfiguration(
            [Configuration::WEB => [
                Configuration::SHOW_HOME_BUTTON => $showHomeButton,
                Configuration::SHOW_HOME_BUTTON_ON_HOMEPAGE => $showHomeButtonOnHomepage,
                Configuration::SHOW_HOME_BUTTON_ON_ROUTES => $showHomeButtonOnRoutes,
            ]]
        );
        self::assertFalse($configuration->isShowHomeButtonOnRoutes(), 'Expected configuration with hidden home button on routes');
        $menu = $this->createMenu($configuration, false);
        if ($this->isSkeletonChecked()) {
            $htmlDocument = new HtmlDocument(<<<HTML
<html lang="cs">
<body>
{$menu->getValue()}
</body>
</html>
HTML
            );
            $homeButton = $htmlDocument->getElementById('homeButton');
            self::assertEmpty($homeButton, 'Home button should not be used at all');
        }
    }

    public function provideConfigurationToHideHomeButtonOnRoutes(): array
    {
        return [
            'hide home button everywhere' => [false, false, false],
            'show home button only on homepage' => [false, true, false],
        ];
    }

    /**
     * @test
     */
    public function Home_button_target_leads_to_expected_link(): void
    {
        foreach ([true, false] as $showHomeButtonOnHomepage) {
            $configuration = $this->createCustomConfiguration(
                [Configuration::WEB => [
                    Configuration::SHOW_HOME_BUTTON_ON_HOMEPAGE => $showHomeButtonOnHomepage,
                    Configuration::SHOW_HOME_BUTTON_ON_ROUTES => !$showHomeButtonOnHomepage,
                    Configuration::HOME_BUTTON_TARGET => $expectedTarget = '/foo/bar?baz=qux',
                ]]
            );
            self::assertSame($configuration->getHomeButtonTarget(), $expectedTarget);
            $menu = $this->createMenu($configuration, $showHomeButtonOnHomepage);
            if ($this->isSkeletonChecked()) {
                $htmlDocument = new HtmlDocument(<<<HTML
<html lang="cs">
<body>
{$menu->getValue()}
</body>
</html>
HTML
                );
                /** @var Element $homeButton */
                $homeButton = $htmlDocument->getElementById('homeButton');
                self::assertNotEmpty($homeButton, 'Home button expected');
                self::assertSame($expectedTarget, $homeButton->getAttribute('href'));
            }
        }
    }

    /**
     * @test
     */
    public function I_can_get_menu_even_on_not_found_route(): void
    {
        $configuration = $this->createCustomConfiguration(
            [Configuration::WEB => [
                Configuration::SHOW_HOME_BUTTON => false,
                Configuration::SHOW_HOME_BUTTON_ON_HOMEPAGE => false,
                Configuration::SHOW_HOME_BUTTON_ON_ROUTES => true,
                Configuration::HOME_BUTTON_TARGET => $expectedTarget = '/foo/bar?baz=qux',
            ]]
        );
        self::assertTrue($configuration->isShowHomeButtonOnRoutes(), 'Expected configuration with shown home button on routes');
        $menu = new Menu(
            $configuration,
            new HomepageDetector(
                new PathProvider(
                    $this->getServicesContainer()->getRulesUrlMatcher(),
                    uniqid('/non/existing/path', true)
                )
            )
        );
        $htmlDocument = new HtmlDocument(<<<HTML
<html lang="cs">
<body>
{$menu->getValue()}
</body>
</html>
HTML
        );
        /** @var Element $homeButton */
        $homeButton = $htmlDocument->getElementById('homeButton');
        self::assertNotEmpty($homeButton, 'Home button is missing');
        self::assertSame(
            $expectedTarget,
            $homeButton->getAttribute('href'), 'Link of home button should lead to home'
        );
    }
}