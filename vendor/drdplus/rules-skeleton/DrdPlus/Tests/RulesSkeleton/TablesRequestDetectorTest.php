<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\Request;
use DrdPlus\RulesSkeleton\RouteMatch;
use DrdPlus\RulesSkeleton\RulesUrlMatcher;
use DrdPlus\RulesSkeleton\TablesRequestDetector;
use Granam\Tests\Tools\TestWithMockery;
use Mockery\MockInterface;

class TablesRequestDetectorTest extends TestWithMockery
{

    /**
     * @test
     * @dataProvider provideRequestDetails
     * @param $url
     * @param string $routeName
     * @param bool $areTablesRequestedByOldWay
     * @param bool $shouldBeTablesExpected
     */
    public function I_can_find_out_if_are_tables_requested(
        $url,
        string $routeName,
        bool $areTablesRequestedByOldWay,
        bool $shouldBeTablesExpected
    )
    {
        $tablesRequestDetector = new TablesRequestDetector(
            $this->createRulesUrlsMatcher($url, $routeName),
            $this->createRequest($url, $areTablesRequestedByOldWay)
        );
        self::assertSame($shouldBeTablesExpected, $tablesRequestDetector->areTablesRequested());
    }

    public function provideRequestDetails(): array
    {
        return [
            'tables are not requested at all' => ['foo', 'root', false, false],
            'tables are requested by a new way' => ['foo', 'tables', false, true],
            'tables are requested by an old way' => ['foo', 'root', true, true],
        ];
    }

    /**
     * @param $matches
     * @param string $routeName
     * @return RulesUrlMatcher|MockInterface
     */
    private function createRulesUrlsMatcher($matches, string $routeName): RulesUrlMatcher
    {
        $urlMatcher = $this->mockery(RulesUrlMatcher::class);
        $urlMatcher->shouldReceive('match')
            ->with($matches)
            ->andReturn($routeMatch = $this->mockery(RouteMatch::class));
        $routeMatch->shouldReceive('getRouteName')
            ->andReturn($routeName);
        return $urlMatcher;
    }

    /**
     * @param $currentUrl
     * @param bool $areTablesRequested
     * @return Request|MockInterface
     */
    private function createRequest($currentUrl, bool $areTablesRequested): Request
    {
        $request = $this->mockery(Request::class);
        $request->shouldReceive('getCurrentUrl')
            ->andReturn($currentUrl);
        $request->shouldReceive('areTablesRequested')
            ->andReturn($areTablesRequested);
        return $request;
    }
}
