<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\DummyUrlMatcher;
use DrdPlus\RulesSkeleton\RulesUrlMatcher;
use PHPUnit\Framework\TestCase;

class DummyUrlMatcherTest extends TestCase
{

    /**
     * @test
     */
    public function I_will_always_get_root_match()
    {
        $dummyUrlMatcher = new DummyUrlMatcher();
        self::assertSame(['path' => '/'], $dummyUrlMatcher->match(uniqid('/foo-bar', true)));
    }

    /**
     * @test
     */
    public function I_can_use_it_for_rules_url_matcher()
    {
        $dummyUrlMatcher = new DummyUrlMatcher();
        $rulesUrlMatcher = new RulesUrlMatcher($dummyUrlMatcher);
        $routeMatch = $rulesUrlMatcher->match('String from another universe');
        self::assertSame('/', $routeMatch->getPath());
    }
}
