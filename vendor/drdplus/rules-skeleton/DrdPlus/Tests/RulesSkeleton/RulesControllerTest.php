<?php
namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\Configuration;
use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\RulesSkeleton\Redirect;
use DrdPlus\RulesSkeleton\Request;
use DrdPlus\RulesSkeleton\RulesController;
use DrdPlus\RulesSkeleton\ServicesContainer;
use DrdPlus\RulesSkeleton\UsagePolicy;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;
use Granam\WebContentBuilder\HtmlDocument;
use Gt\Dom\Element;
use Gt\Dom\TokenList;
use Mockery\MockInterface;

class RulesControllerTest extends AbstractContentTest
{
    /**
     * @test
     */
    public function I_can_add_body_class(): void
    {
        $controller = $this->createController();
        self::assertSame([], $controller->getBodyClasses());
        $controller->addBodyClass('rumbling');
        $controller->addBodyClass('cracking');
        self::assertSame(['rumbling', 'cracking'], $controller->getBodyClasses());
    }

    /**
     * @test
     */
    public function I_can_ask_if_menu_is_fixed(): void
    {
        $configurationWithoutFixedMenu = $this->createCustomConfiguration([Configuration::WEB => [Configuration::MENU_POSITION_FIXED => false]]);
        self::assertFalse($configurationWithoutFixedMenu->isMenuPositionFixed(), 'Expected configuration with menu position not fixed');
        $controller = $this->createController($configurationWithoutFixedMenu);
        self::assertFalse($controller->isMenuPositionFixed(), 'Contacts are expected to be simply on top by default');
        if ($this->isSkeletonChecked()) {
            /** @var Element $menu */
            $menu = $this->getHtmlDocument()->getElementById(HtmlHelper::ID_MENU);
            self::assertNotEmpty($menu, 'Contacts are missing');
            self::assertTrue($menu->classList->contains('top'), 'Contacts should be positioned on top');
            self::assertFalse($menu->classList->contains('fixed'), 'Contacts should not be fixed as controller does not say so');
        }
        $configurationWithFixedMenu = $this->createCustomConfiguration([Configuration::WEB => [Configuration::MENU_POSITION_FIXED => true]]);
        self::assertTrue($configurationWithFixedMenu->isMenuPositionFixed(), 'Expected configuration with menu position fixed');
        $controller = $this->createController($configurationWithFixedMenu);
        self::assertTrue($controller->isMenuPositionFixed(), 'Menu should be fixed');
        if ($this->isSkeletonChecked()) {
            $content = $this->fetchNonCachedContent($controller);
            $htmlDocument = new HtmlDocument($content);
            $menu = $htmlDocument->getElementById(HtmlHelper::ID_MENU);
            self::assertNotEmpty($menu, 'Contacts are missing');
            self::assertTrue($menu->classList->contains('top'), 'Contacts should be positioned on top');
            self::assertTrue(
                $menu->classList->contains('fixed'),
                'Contacts should be fixed as controller says so;'
                . ' current classes are ' . \implode(',', $this->tokenListToArray($menu->classList))
            );
        }
    }

    private function tokenListToArray(TokenList $tokenList): array
    {
        $array = [];
        for ($index = 0; $index < $tokenList->length; $index++) {
            $array[] = $tokenList->item($index);
        }

        return $array;
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_can_activate_trial(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $now = new \DateTimeImmutable();
        $trialExpectedExpiration = $now->modify('+4 minutes');
        $usagePolicy = $this->createUsagePolicy($trialExpectedExpiration);
        $controller = new RulesController($this->createServicesContainerWithUsagePolicy($usagePolicy));
        $controllerReflection = new \ReflectionClass($controller);
        $activateTrial = $controllerReflection->getMethod('activateTrial');
        $activateTrial->setAccessible(true);
        self::assertTrue($activateTrial->invoke($controller, $now));
        $getRedirect = $controllerReflection->getMethod('getRedirect');
        $getRedirect->setAccessible(true);
        $redirect = $getRedirect->invoke($controller);
        self::assertNotNull($redirect);
        $trialExpectedExpirationTimestamp = $trialExpectedExpiration->getTimestamp() + 1; // one second "insurance" overlap
        self::assertSame('/?bar=' . $trialExpectedExpirationTimestamp, $redirect->getTarget());
        self::assertSame($trialExpectedExpirationTimestamp - $now->getTimestamp(), $redirect->getAfterSeconds());
    }

    /**
     * @param \DateTimeInterface $trialExpectedExpiration
     * @return UsagePolicy|MockInterface
     */
    private function createUsagePolicy(\DateTimeInterface $trialExpectedExpiration): UsagePolicy
    {
        $usagePolicy = $this->mockery(UsagePolicy::class);
        $usagePolicy->expects('getTrialExpiredAtName')
            ->atLeast()->once()
            ->andReturn('bar');
        $usagePolicy->expects('activateTrial')
            ->with($this->type(\DateTimeInterface::class))
            ->andReturnUsing(function (\DateTimeInterface $expiresAt) use ($trialExpectedExpiration) {
                self::assertEquals($trialExpectedExpiration, $expiresAt);

                return true;
            });

        return $usagePolicy;
    }

    private function createServicesContainerWithUsagePolicy(UsagePolicy $usagePolicy)
    {
        $configuration = $this->getConfiguration();
        $htmlHelper = $this->createHtmlHelper();

        return new class($usagePolicy, $configuration, $htmlHelper) extends ServicesContainer
        {
            /** @var UsagePolicy */
            private $usagePolicy;

            public function __construct(usagePolicy $usagePolicy, Configuration $configuration, HtmlHelper $htmlHelper)
            {
                $this->usagePolicy = $usagePolicy;
                parent::__construct($configuration, $htmlHelper);
            }

            public function getUsagePolicy(): UsagePolicy
            {
                return $this->usagePolicy;
            }

        };
    }

    /**
     * @test
     * @dataProvider provideRequestType
     * @backupGlobals enabled
     * @param string $requestType
     * @throws \ReflectionException
     */
    public function I_will_be_redirected_via_html_meta_on_trial(string $requestType): void
    {
        self::assertCount(0, $this->getMetaRefreshes($this->getHtmlDocument()), 'No meta tag with refresh meaning expected so far');
        $this->passOut();
        $controller = null;
        $now = \time();
        $trialExpiredAt = $now + 240 + 1;
        $trialExpiredAtSecondAfter = $trialExpiredAt++;
        if ($this->isSkeletonChecked() || $this->getTestsConfiguration()->hasProtectedAccess()) {
            self::assertNull(
                $_GET[Request::TRIAL] ?? $_POST[Request::TRIAL] ?? $_COOKIE[Request::TRIAL] ?? null,
                'Globals have not been reset'
            );
            if ($requestType === 'get') {
                $_GET[Request::TRIAL] = '1';
            } elseif ($requestType === 'post') {
                $_POST[Request::TRIAL] = '1';
            } else {
                $_COOKIE[Request::TRIAL] = '1';
            }
        } else { // just a little hack
            $controller = $this->createController();
            $controllerReflection = new \ReflectionClass($controller);
            $setRedirect = $controllerReflection->getMethod('setRedirect');
            $setRedirect->setAccessible(true);
            $setRedirect->invoke($controller, new Redirect('/?' . UsagePolicy::TRIAL_EXPIRED_AT . '=' . $trialExpiredAt, 241));
        }
        $trialContent = $this->fetchNonCachedContent($controller);
        $document = new HtmlDocument($trialContent);
        $metaRefreshes = $this->getMetaRefreshes($document);
        self::assertCount(1, $metaRefreshes, 'One meta tag with refresh meaning expected');
        $metaRefresh = \current($metaRefreshes);
        self::assertRegExp(
            '~241; url=/[?]' . UsagePolicy::TRIAL_EXPIRED_AT . "=($trialExpiredAt|$trialExpiredAtSecondAfter)~",
            $metaRefresh->getAttribute('content')
        );
    }

    public function provideRequestType(): array
    {
        return [
            ['get'],
            ['post'],
            ['cookie'],
        ];
    }

    /**
     * @test
     * @backupGlobals enabled
     */
    public function I_will_not_be_redirected_as_owner_via_html_meta_even_on_trial(): void
    {
        self::assertCount(0, $this->getMetaRefreshes($this->getHtmlDocument()), 'No meta tag with refresh meaning expected');
        $this->passOut();
        self::assertNull($_POST[Request::TRIAL] ?? null, 'Globals have not been reset');
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->createServicesContainer()->getUsagePolicy()->activateTrial(new \DateTimeImmutable('+1 year'));
        $_POST[Request::TRIAL] = '1';
        $content = $this->fetchNonCachedContent();
        $document = new HtmlDocument($content);
        $metaRefreshes = $this->getMetaRefreshes($document);
        self::assertCount(0, $metaRefreshes, 'No meta tag with refresh meaning expected as we are owners');
    }

}