<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\Environment;
use DrdPlus\RulesSkeleton\Request;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;
use Mockery\MockInterface;

class RequestTest extends AbstractContentTest
{

    /**
     * @test
     * @backupGlobals enabled
     */
    public function I_can_create_it_from_globals(): void
    {
        $_GET = ['foo' => 'from get'];
        $_POST = ['bar' => 'from post'];
        $_COOKIE = ['baz' => 'from cookie'];
        $_SERVER['REQUEST_URI'] = '/qux/foobar/foobaz?fooqux=123';
        $request = Request::createFromGlobals($this->getBot(), $this->getEnvironment());

        self::assertSame('from get', $request->getValue('foo'));
        self::assertSame('from post', $request->getValue('bar'));
        self::assertSame('from cookie', $request->getValue('baz'));
        self::assertSame('/qux/foobar/foobaz', $request->getPath());
    }

    public static function getCrawlerUserAgents(): array
    {
        return [
            'Mozilla/5.0 (compatible; SeznamBot/3.2; +http://napoveda.seznam.cz/en/seznambot-intro/)',
            'User-Agent: Mozilla/5.0 (compatible; SeznamBot/3.2-test4; +http://napoveda.seznam.cz/en/seznambot-intro/)',
            'Googlebot',
        ];
    }

    public static function getNonCrawlerUserAgents(): array
    {
        return [
            'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0', // Firefox
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.62 Safari/537.36' // Chrome
        ];
    }

    /**
     * @test
     */
    public function I_can_detect_czech_seznam_bot(): void
    {
        foreach (static::getCrawlerUserAgents() as $crawlerUserAgent) {
            $request = new Request($this->getBot(), $this->getEnvironment(), [], [], [], ['HTTP_USER_AGENT' => $crawlerUserAgent]);
            self::assertTrue(
                $request->isVisitorBot($crawlerUserAgent),
                'Directly passed crawler has not been recognized: ' . $crawlerUserAgent
            );
            self::assertTrue(
                $request->isVisitorBot(),
                'Crawler has not been recognized from HTTP_USER_AGENT: ' . $crawlerUserAgent
            );
        }
    }

    /**
     * @test
     */
    public function I_do_not_get_non_bot_browsers_marked_as_bots(): void
    {
        foreach (static::getNonCrawlerUserAgents() as $nonCrawlerUserAgent) {
            $request = new Request($this->getBot(), $this->getEnvironment(), [], [], [], ['HTTP_USER_AGENT' => $nonCrawlerUserAgent]);
            self::assertFalse(
                $request->isVisitorBot($nonCrawlerUserAgent),
                'Directly passed browser has been wrongly marked as a bot: ' . $nonCrawlerUserAgent
            );
            self::assertFalse(
                $request->isVisitorBot(),
                'Browser has been wrongly marked as a bot from HTTP_USER_AGENT: ' . $nonCrawlerUserAgent
            );
        }
    }

    /**
     * @test
     */
    public function I_can_get_current_url_even_if_query_string_is_not_set(): void
    {
        $request = new Request($this->getBot(), $this->getEnvironment(), [], [], [], []);
        self::assertSame('/', $request->getCurrentUrl());
    }

    /**
     * @test
     */
    public function I_can_get_current_url_with_updated_query_parameters(): void
    {
        $request = new Request($this->getBot(), $this->getEnvironment(), ['foo' => 123, 'bar' => 456], [], [], []);
        self::assertSame('/?foo=0&bar=456&baz=OK', $request->getCurrentUrl(['foo' => false, 'baz' => 'OK']));
    }

    /**
     * @test
     * @backupGlobals enabled
     */
    public function I_can_create_it_from_globals_even_if_no_globals_are_set()
    {
        $get = $_GET;
        $post = $_POST;
        $cookies = $_COOKIE;
        $server = $_SERVER;
        unset($_GET, $_POST, $_COOKIE, $_SERVER);
        $request = Request::createFromGlobals($this->getBot(), $this->getEnvironment());
        global $_GET, $_POST, $_COOKIE, $_SERVER; // because backup globals do not works for unset global
        $_GET = $get;
        $_POST = $post;
        $_COOKIE = $cookies;
        $_SERVER = $server;
        self::assertSame('/', $request->getCurrentUrl());
    }

    /**
     * @test
     */
    public function I_can_get_current_url_with_updated_query_parameters_and_removed_unwanted(): void
    {
        $request = new Request(
            $this->getBot(),
            $this->getEnvironment(),
            ['foo' => 123, 'bar' => 456, 'baz' => 789],
            [],
            [],
            []
        );
        self::assertSame(
            '/?foo=0&baz=789',
            $request->getCurrentUrl(['foo' => false], ['bar'])
        );
    }

    /**
     * @test
     */
    public function I_can_get_current_url_without_automatic_parameters(): void
    {
        $request = new Request(
            $this->getBot(),
            $this->getEnvironment(),
            ['foo' => 'ok', 'bar' => 'not ok', Request::TRIAL_EXPIRED_AT => time(), Request::TRIAL => '1', Request::CACHE => '1'],
            [],
            [],
            []
        );
        self::assertSame(
            sprintf('/?foo=nice&%s=1&%s=1', Request::TRIAL, Request::CACHE),
            $request->getCurrentUrlWithoutAutomaticValues(['foo' => 'nice'], ['bar'])
        );
    }

    /**
     * @test
     */
    public function I_can_get_value_from_get(): void
    {
        $request = new Request($this->getBot(), $this->getEnvironment(), [], [], [], []);
        self::assertNull($request->getValueFromGet('foo'));
        $request = new Request($this->getBot(), $this->getEnvironment(), ['foo' => 'bar'], [], [], []);
        self::assertSame('bar', $request->getValueFromGet('foo'));

    }

    /**
     * @test
     */
    public function I_can_get_value_from_post(): void
    {
        $request = new Request($this->getBot(), $this->getEnvironment(), [], [], [], []);
        self::assertNull($request->getValueFromPost('foo'));

        $request = new Request($this->getBot(), $this->getEnvironment(), [], ['foo' => 'bar'], [], []);
        self::assertSame('bar', $request->getValueFromPost('foo'));
        self::assertNull($request->getValueFromGet('foo'));
        self::assertNull($request->getValueFromCookie('foo'));
    }

    /**
     * @test
     */
    public function I_can_get_value_from_cookie(): void
    {
        $request = new Request($this->getBot(), $this->getEnvironment(), [], [], [], []);
        self::assertNull($request->getValueFromCookie('foo'));

        $request = new Request($this->getBot(), $this->getEnvironment(), [], [], ['foo' => 'bar'], []);
        self::assertSame('bar', $request->getValueFromCookie('foo'));
        self::assertNull($request->getValueFromGet('foo'));
        self::assertNull($request->getValueFromPost('foo'));
    }

    /**
     * @test
     */
    public function I_can_get_value_from_request_with_priority_post_get_cookie(): void
    {
        $request = new Request($this->getBot(), $this->getEnvironment(), [], [], [], []);
        self::assertNull($request->getValue('foo'));

        $request = new Request($this->getBot(), $this->getEnvironment(), ['foo' => 'from get'], ['foo' => 'from post'], ['foo' => 'from cookie'], []);
        self::assertSame('from post', $request->getValue('foo'));

        $request = new Request($this->getBot(), $this->getEnvironment(), ['foo' => 'from get'], [], ['foo' => 'from cookie'], []);
        self::assertSame('from get', $request->getValue('foo'));

        $request = new Request($this->getBot(), $this->getEnvironment(), [], [], ['foo' => 'from cookie'], []);
        self::assertSame('from cookie', $request->getValue('foo'));
    }

    /**
     * @test
     * @dataProvider provideTablesIdsParameterName
     * @param string $parameterName
     */
    public function I_can_get_requested_tables_ids(string $parameterName): void
    {
        $request = new Request($this->getBot(), $this->getEnvironment(), [], [], [], []);
        self::assertSame([], $request->getRequestedTablesIds());

        $request = new Request($this->getBot(), $this->getEnvironment(), [$parameterName => '    '], [], [], []);
        self::assertSame([], $request->getRequestedTablesIds());

        $request = new Request($this->getBot(), $this->getEnvironment(), [$parameterName => 'foo'], [], [], []);
        self::assertSame(['foo'], $request->getRequestedTablesIds());

        $request = new Request($this->getBot(), $this->getEnvironment(), [$parameterName => 'foo,bar,baz'], [], [], []);
        self::assertSame(['foo', 'bar', 'baz'], $request->getRequestedTablesIds());
    }

    public function provideTablesIdsParameterName(): array
    {
        return [
            [Request::TABLES],
            [Request::TABULKY],
        ];
    }

    /**
     * @test
     */
    public function I_can_get_path_info(): void
    {
        $request = new Request($this->getBot(), $this->getEnvironment(), [], [], [], ['PATH_INFO' => null]);
        self::assertSame('', $request->getPathInfo());

        $request = new Request($this->getBot(), $this->getEnvironment(), [], [], [], ['PATH_INFO' => 'foo/bar']);
        self::assertSame('foo/bar', $request->getPathInfo());
    }

    /**
     * @test
     */
    public function I_can_get_current_path(): void
    {
        $request = new Request($this->getBot(), $this->getEnvironment(), [], [], [], ['REQUEST_URI' => null]);
        self::assertSame('/', $request->getPath());

        $request = new Request($this->getBot(), $this->getEnvironment(), [], [], [], ['REQUEST_URI' => '/foo/bar']);
        self::assertSame('/foo/bar', $request->getPath());
    }

    /**
     * @test
     */
    public function I_can_get_query_string(): void
    {
        $request = new Request($this->getBot(), $this->getEnvironment(), [], [], [], ['QUERY_STRING' => null]);
        self::assertSame('', $request->getQueryString());

        $request = new Request($this->getBot(), $this->getEnvironment(), [], [], [], ['QUERY_STRING' => 'foo=bar']);
        self::assertSame('foo=bar', $request->getQueryString());
    }

    /**
     * @test
     */
    public function I_can_find_out_if_request_comes_from_cli(): void
    {
        $request = new Request($this->getBot(), $this->createEnvironment('paper'), [], [], [], []);
        self::assertFalse($request->isCliRequest());

        $request = new Request($this->getBot(), $this->createEnvironment('cli'), [], [], [], []);
        self::assertTrue($request->isCliRequest());
    }

    /**
     * @param string $phpSapi
     * @return Environment|MockInterface
     */
    private function createEnvironment(string $phpSapi): Environment
    {
        $environment = $this->mockery(Environment::class);
        $environment->shouldReceive('getPhpSapi')
            ->andReturn($phpSapi);
        $environment->makePartial();
        return $environment;
    }

    /**
     * @test
     */
    public function I_can_find_out_if_trial_just_expired(): void
    {
        $request = new Request($this->getBot(), $this->getEnvironment(), [], [], [], []);
        self::assertFalse($request->trialJustExpired());

        $request = new Request($this->getBot(), $this->getEnvironment(), [Request::TRIAL_EXPIRED_AT => time() + 10], [], [], []);
        self::assertFalse($request->trialJustExpired());

        $request = new Request($this->getBot(), $this->getEnvironment(), [Request::TRIAL_EXPIRED_AT => time() - 10], [], [], []);
        self::assertTrue($request->trialJustExpired());
    }

    /**
     * @test
     */
    public function I_will_get_homepage_on_multiple_slashes(): void
    {
        $request = new Request($this->getBot(), $this->getEnvironment(), [], [], [], ['REQUEST_URI' => '//']);
        self::assertSame('/', $request->getPath());
    }

    /**
     * @test
     */
    public function I_will_get_root_url_if_only_excluded_parameters_are_set()
    {
        $request = new Request($this->getBot(), $this->getEnvironment(), [], [], [], []);
        self::assertSame('/', $request->getCurrentUrl([], ['foo' => 123]));
    }

    /**
     * @test
     */
    public function I_can_get_server_name()
    {
        $request = new Request($this->getBot(), $this->getEnvironment(), [], [], [], []);
        self::assertSame('', $request->getServerName());

        $request = new Request($this->getBot(), $this->getEnvironment(), [], [], [], ['SERVER_NAME' => 'Hi!']);
        self::assertSame('Hi!', $request->getServerName());
    }

    /**
     * @test
     */
    public function I_can_get_all_values_from_get()
    {
        $request = new Request($this->getBot(), $this->getEnvironment(), $get = ['foo' => 'bar'], [], [], []);
        self::assertSame($get, $request->getValuesFromGet());
    }

    /**
     * @test
     */
    public function I_can_post_all_values_from_post()
    {
        $request = new Request($this->getBot(), $this->getEnvironment(), [], $post = ['baz' => 'qux'], [], []);
        self::assertSame($post, $request->getValuesFromPost());
    }

    /**
     * @test
     */
    public function I_can_get_all_values_from_cookies()
    {
        $request = new Request($this->getBot(), $this->getEnvironment(), [], [], $cookies = ['a' => 123, 'b' => 'hu'], []);
        self::assertSame($cookies, $request->getValuesFromCookies());
    }
}