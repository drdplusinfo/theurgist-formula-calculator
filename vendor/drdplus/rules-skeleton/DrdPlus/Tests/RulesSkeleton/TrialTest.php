<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\RulesSkeleton\Redirect;
use DrdPlus\RulesSkeleton\RulesApplication;
use DrdPlus\RulesSkeleton\Web\RulesContent;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;
use Granam\WebContentBuilder\HtmlDocument;
use Gt\Dom\Element;

class TrialTest extends AbstractContentTest
{
    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_will_get_cached_content_with_injected_trial_timeout(): void
    {
        if (!$this->getTestsConfiguration()->hasProtectedAccess()) {
            self::assertFalse(false, 'Nothing to test here');

            return;
        }
        $rulesApplication = $this->createRulesApplication();
        $cacheStamp = $this->There_is_no_meta_redirect_if_licence_owning_has_been_confirmed($rulesApplication);
        $this->There_is_meta_redirect_in_passing_by_trial($rulesApplication, $cacheStamp);
    }

    private function There_is_no_meta_redirect_if_licence_owning_has_been_confirmed(RulesApplication $rulesApplication): string
    {
        $content = $this->getRulesContent($rulesApplication);
        $firstWithoutRedirect = new HtmlDocument($content);
        self::assertNull($firstWithoutRedirect->getElementById(HtmlHelper::ID_META_REDIRECT));
        $cacheStamp = $firstWithoutRedirect->documentElement->getAttribute(HtmlHelper::DATA_CACHE_STAMP);
        self::assertNotEmpty($cacheStamp);

        return $cacheStamp;
    }

    private function getRulesContent(RulesApplication $rulesApplication): RulesContent
    {
        $applicationReflection = new \ReflectionClass($rulesApplication);
        $getRulesContent = $applicationReflection->getMethod('getContent');
        $getRulesContent->setAccessible(true);

        return $getRulesContent->invoke($rulesApplication);
    }

    /**
     * @param RulesApplication $rulesApplication
     * @param string $previousCacheStamp
     * @throws \ReflectionException
     */
    private function There_is_meta_redirect_in_passing_by_trial(RulesApplication $rulesApplication, string $previousCacheStamp): void
    {
        $applicationReflection = new \ReflectionClass($rulesApplication);
        $setRedirect = $applicationReflection->getMethod('setRedirect');
        $setRedirect->setAccessible(true);
        $setRedirect->invoke($rulesApplication, new Redirect('/foo', 12345));
        $content = $this->getRulesContent($rulesApplication);
        $firstWithRedirect = new HtmlDocument($content);
        self::assertSame(
            $previousCacheStamp,
            $firstWithRedirect->documentElement->getAttribute(HtmlHelper::DATA_CACHE_STAMP),
            'Expected content from same cache'
        );
        /** @var Element $redirectElement */
        $redirectElement = $firstWithRedirect->getElementById(HtmlHelper::ID_META_REDIRECT);
        self::assertNotNull($redirectElement, 'Missing expected element with ID "' . HtmlHelper::ID_META_REDIRECT . '"');
        self::assertSame('Refresh', $redirectElement->getAttribute('http-equiv'));
        self::assertSame('12345; url=/foo', $redirectElement->getAttribute('content'));
    }
}