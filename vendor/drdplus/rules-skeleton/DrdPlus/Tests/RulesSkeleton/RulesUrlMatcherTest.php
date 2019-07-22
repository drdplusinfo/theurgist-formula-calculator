<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\RouteMatch;
use DrdPlus\RulesSkeleton\RulesUrlMatcher;
use Granam\Tests\Tools\TestWithMockery;
use Mockery\MockInterface;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

class RulesUrlMatcherTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_match_an_url()
    {
        $match = ['path' => '/foo'];
        $rulesUrlMatcher = new RulesUrlMatcher($this->createUrlMatcher('/bubble/gun', $match));
        $routeMatch = $rulesUrlMatcher->match('/bubble/gun');
        self::assertEquals(new RouteMatch(['path' => '/foo']), $routeMatch);
    }

    /**
     * @param string $expectedPathInfo
     * @param array $match
     * @return UrlMatcherInterface|MockInterface
     */
    private function createUrlMatcher(string $expectedPathInfo, array $match): UrlMatcherInterface
    {
        $urlMatcher = $this->mockery(UrlMatcherInterface::class);
        $urlMatcher->shouldReceive('match')
            ->with($expectedPathInfo)
            ->andReturn($match);
        return $urlMatcher;
    }
}
