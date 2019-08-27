<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\CookiesService;
use DrdPlus\RulesSkeleton\Request;
use DrdPlus\RulesSkeleton\UsagePolicy;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;

class UsagePolicyTest extends AbstractContentTest
{
    /**
     * @test
     */
    public function I_can_not_create_it_without_article_name(): void
    {
        $this->expectException(\DrdPlus\RulesSkeleton\Exceptions\ArticleNameCanNotBeEmptyForUsagePolicy::class);
        new UsagePolicy('', Request::createFromGlobals($this->getBot(), $this->getEnvironment()), $this->getCookiesService());
    }

    /**
     * @test
     * @backupGlobals enabled
     */
    public function I_can_confirm_ownership_of_visitor(): void
    {
        $request = $this->createRequest();
        self::assertSame([], $request->getValuesFromCookies());
        $usagePolicy = new UsagePolicy(
            'foo',
            $request,
            $cookiesService = $this->createCookiesService($request)
        );
        self::assertSame('confirmedOwnershipOfFoo', $request->getValueFromCookie(UsagePolicy::OWNERSHIP_COOKIE_NAME));
        self::assertSame('trialOfFoo', $request->getValueFromCookie(UsagePolicy::TRIAL_COOKIE_NAME));
        self::assertSame(Request::TRIAL_EXPIRED_AT, $request->getValueFromCookie(UsagePolicy::TRIAL_EXPIRED_AT_COOKIE_NAME));
        self::assertNull($request->getValueFromCookie('confirmedOwnershipOfFoo'));

        $usagePolicy->confirmOwnershipOfVisitor($expiresAt = new \DateTime());
        self::assertSame((string)$expiresAt->getTimestamp(), $request->getValueFromCookie('confirmedOwnershipOfFoo'));
    }

    /**
     * @test
     */
    public function I_can_find_out_if_trial_expired(): void
    {
        $usagePolicy = new UsagePolicy(
            'foo',
            new Request($this->getBot(), $this->getEnvironment(), [], [], [], []),
            $this->getCookiesService()
        );
        self::assertFalse($usagePolicy->trialJustExpired(), 'Did not expects trial expiration yet');

        $usagePolicy = new UsagePolicy(
            'foo',
            new Request($this->getBot(), $this->getEnvironment(), [Request::TRIAL_EXPIRED_AT => time()], [], [], []),
            $this->getCookiesService()
        );
        self::assertTrue($usagePolicy->trialJustExpired(), 'Expected trial expiration');

        $usagePolicy = new UsagePolicy(
            'foo',
            new Request($this->getBot(), $this->getEnvironment(), [Request::TRIAL_EXPIRED_AT => time() + 2], [], [], []),
            $this->getCookiesService()
        );
        self::assertFalse($usagePolicy->trialJustExpired(), 'Did not expects trial expiration as its time is in future');

        $usagePolicy = new UsagePolicy(
            'foo',
            new Request($this->getBot(), $this->getEnvironment(), [Request::TRIAL_EXPIRED_AT => 0], [], [], []),
            $this->getCookiesService()
        );
        self::assertFalse($usagePolicy->trialJustExpired(), 'Did not expects trial expiration now');
    }

    /**
     * @test
     * @backupGlobals enabled
     */
    public function I_can_find_out_if_visitor_is_using_valid_trial(): void
    {
        $usagePolicy = new UsagePolicy(
            'foo',
            $request = new Request($this->getBot(), $this->getEnvironment(), [], [], [], []),
            new CookiesService($request)
        );
        self::assertFalse($usagePolicy->isVisitorUsingValidTrial(), 'Did not expects valid trial yet');
        self::assertFalse($usagePolicy->trialJustExpired(), 'Did not expects trial expiration yet');

        $usagePolicy = new UsagePolicy(
            'foo',
            $request = new Request($this->getBot(), $this->getEnvironment(), [], [], [$usagePolicy->getTrialName() => true], []),
            new CookiesService($request)
        );
        self::assertTrue($usagePolicy->isVisitorUsingValidTrial(), 'Expects valid trial');
        self::assertFalse($usagePolicy->trialJustExpired(), 'Did not expects trial expiration yet');

        $usagePolicy = new UsagePolicy(
            'foo',
            $request = new Request($this->getBot(), $this->getEnvironment(), [Request::TRIAL_EXPIRED_AT => PHP_INT_MAX], [], [$usagePolicy->getTrialName() => true], []),
            new CookiesService($request)
        );
        self::assertTrue($usagePolicy->isVisitorUsingValidTrial(), 'Expects valid trial');
        self::assertFalse($usagePolicy->trialJustExpired(), 'Did not expects trial expiration yet');

        $usagePolicy = new UsagePolicy(
            'foo',
            $request = new Request($this->getBot(), $this->getEnvironment(), [Request::TRIAL_EXPIRED_AT => time() - 1], [], [$usagePolicy->getTrialName() => true], []),
            new CookiesService($request)
        );
        self::assertFalse($usagePolicy->isVisitorUsingValidTrial(), 'Expects trial to be valid no more');
        self::assertTrue($usagePolicy->trialJustExpired(), 'Expects trial to be expired');
    }
}