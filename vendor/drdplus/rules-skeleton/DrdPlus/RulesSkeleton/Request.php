<?php declare(strict_types=1);

namespace DrdPlus\RulesSkeleton;

use DeviceDetector\Parser\Bot;
use Granam\Strict\Object\StrictObject;

class Request extends StrictObject
{
    public const VERSION = 'version';
    public const UPDATE = 'update';
    public const CACHE = 'cache';
    public const DISABLE = 'disable';
    public const TABLES = 'tables';
    public const TABULKY = 'tabulky';
    public const CONFIRM = 'confirm';
    public const PDF = 'pdf';
    // trial
    public const TRIAL = 'trial';
    public const TRIAL_EXPIRED_AT = 'trial_expired_at';

    /** @var Bot */
    private $botParser;
    /** @var Environment */
    private $environment;
    /** @var array */
    private $get;
    /** @var array */
    private $post;
    /** @var array */
    private $cookies;
    /** @var array */
    private $server;

    public static function createFromGlobals(Bot $botParser, Environment $environment): Request
    {
        return new static($botParser, $environment, $_GET ?? [], $_POST ?? [], $_COOKIE ?? [], $_SERVER ?? []);
    }

    public function __construct(Bot $botParser, Environment $environment, array $get, array $post, array $cookies, array $server)
    {
        $this->botParser = $botParser;
        $this->environment = $environment;
        $this->get = $get;
        $this->post = $post;
        $this->cookies = $cookies;
        $this->server = $server;
    }

    public function getServerUrl(): string
    {
        $protocol = 'http';
        if (!empty($this->server['HTTP_X_FORWARDED_PROTO'])) {
            $protocol = $this->server['HTTP_X_FORWARDED_PROTO'];
        } elseif (!empty($this->server['HTTPS']) && $this->server['HTTPS'] !== 'off') {
            $protocol = 'https';
        } elseif (!empty($this->server['REQUEST_SCHEME'])) {
            $protocol = $this->server['REQUEST_SCHEME'];
        }
        if (empty($this->server['SERVER_NAME'])) {
            return '';
        }
        $port = 80;
        if (!empty($this->server['SERVER_PORT']) && \is_numeric($this->server['SERVER_PORT'])) {
            $port = (int)$this->server['SERVER_PORT'];
        }
        $portString = $port === 80 || $port === 443
            ? ''
            : (':' . $port);

        return "{$protocol}://{$this->server['SERVER_NAME']}{$portString}";
    }

    public function isVisitorBot(string $userAgent = null): bool
    {
        $this->botParser->setUserAgent($userAgent ?? $this->server['HTTP_USER_AGENT'] ?? '');
        $this->botParser->discardDetails();

        return (bool)$this->botParser->parse();
    }

    public function getCurrentUrl(array $overwriteParameters = [], array $excludeParameters = []): string
    {
        $url = $this->server['REQUEST_URI'] ?? '/';
        if ($overwriteParameters === [] && ($excludeParameters === [] || $this->get === [] /* nothing to exclude here */)) {
            return $url;
        }
        $path = parse_url($url, PHP_URL_PATH);
        $queryParameters = $this->get ?? [];
        foreach ($excludeParameters as $excludeParameter) {
            unset($queryParameters[$excludeParameter]);
        }
        $queryParameters = \array_merge($queryParameters ?? [], $overwriteParameters);
        if (!$queryParameters) {
            return $path;
        }

        return $path . '?' . \http_build_query($queryParameters);
    }

    public function getCurrentUrlWithoutAutomaticValues(array $overwriteParameters = [], array $excludeParameters = []): string
    {
        $excludeParameters[] = self::TRIAL_EXPIRED_AT;
        return $this->getCurrentUrl($overwriteParameters, $excludeParameters);
    }

    public function getPath(): string
    {
        $requestUri = $this->server['REQUEST_URI'] ?? '/';
        if ($requestUri === ':') {
            $requestUri = '/';
        }
        $requestUri = preg_replace('~/{2,}~', '/', $requestUri);
        $path = parse_url($requestUri, PHP_URL_PATH);
        if (is_string($path)) {
            return $path;
        }
        trigger_error(
            sprintf(
                'Can not parse path from sanitized request URI %s, built from original request URI %s',
                var_export($requestUri, true),
                var_export($this->server['REQUEST_URI'], true)
            ),
            E_USER_WARNING
        );
        return '/';
    }

    /**
     * @param string $name
     * @return null|string|array
     */
    public function getValue(string $name)
    {
        return $this->post[$name] ?? $this->get[$name] ?? $this->cookies[$name] ?? null;
    }

    public function isCliRequest(): bool
    {
        return $this->environment->isCliRequest();
    }

    public function getValuesFromGet(): array
    {
        return $this->get ?? [];
    }

    public function getValuesFromPost(): array
    {
        return $this->post ?? [];
    }

    public function getValuesFromCookies(): array
    {
        return $this->cookies ?? [];
    }

    public function getValueFromPost(string $name)
    {
        return $this->post[$name] ?? null;
    }

    public function getValueFromGet(string $name)
    {
        return $this->get[$name] ?? null;
    }

    public function getValueFromCookie(string $name)
    {
        return $this->cookies[$name] ?? null;
    }

    /**
     * @return array|string[]
     */
    public function getRequestedTablesIds(): array
    {
        $wantedTableIds = \array_map(
            function (string $id) {
                return \trim($id);
            },
            \explode(',', $this->get[self::TABLES] ?? $this->get[self::TABULKY] ?? '')
        );

        return \array_filter(
            $wantedTableIds,
            function (string $id) {
                return $id !== '';
            }
        );
    }

    public function areTablesRequested(): bool
    {
        return $this->getValueFromGet(self::TABLES) !== null || $this->getValueFromGet(self::TABULKY) !== null;
    }

    public function getPathInfo(): string
    {
        return $this->server['PATH_INFO'] ?? '';
    }

    public function getQueryString(): string
    {
        return $this->server['QUERY_STRING'] ?? '';
    }

    public function isRequestedPdf(): bool
    {
        return $this->getQueryString() === self::PDF || $this->getValueFromGet(self::PDF) !== null;
    }

    public function trialJustExpired(): bool
    {
        return !empty($this->get[static::TRIAL_EXPIRED_AT]) && ((int)$this->get[static::TRIAL_EXPIRED_AT]) <= \time();
    }

    public function getServerName(): string
    {
        return $this->server['SERVER_NAME'] ?? '';
    }

    public function isHttpsUsed(): bool
    {
        return !empty($this->server['HTTPS']) && $this->server['HTTPS'] !== 'off';
    }

    public function overwriteCookie(string $name, $value)
    {
        $this->cookies[$name] = $value;
    }

    public function deleteCookie(string $name)
    {
        unset($this->cookies[$name]);
    }
}