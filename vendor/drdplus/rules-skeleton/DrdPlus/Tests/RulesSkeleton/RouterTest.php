<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\Request;
use DrdPlus\RulesSkeleton\Router;
use Granam\Tests\Tools\TestWithMockery;

class RouterTest extends TestWithMockery
{

    /**
     * @test
     */
    public function I_can_get_required_block_name(): void
    {
        $routesToKnownBlockNames = ['pretty-FOO' => 'foo'];
        $router = new Router($this->getRequest(''), $routesToKnownBlockNames);
        self::assertSame('', $router->getRequiredBlockName());
        $router = new Router($this->getRequest('/bar'), $routesToKnownBlockNames);
        self::assertSame('', $router->getRequiredBlockName());
        $router = new Router($this->getRequest('/pretty-FOO'), $routesToKnownBlockNames);
        self::assertSame('foo', $router->getRequiredBlockName());
    }

    /**
     * @param string $path
     * @return Request|\Mockery\MockInterface
     */
    private function getRequest(string $path): Request
    {
        $request = $this->mockery(Request::class);
        $request->expects('getPathInfo')
            ->andReturn($path);

        return $request;
    }
}