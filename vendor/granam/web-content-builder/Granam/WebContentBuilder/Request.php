<?php
declare(strict_types=1);

namespace Granam\WebContentBuilder;

use DeviceDetector\Parser\Bot;
use Granam\Strict\Object\StrictObject;

class Request extends StrictObject
{
    public const VERSION = 'version';
    /** @var Bot */
    private $botParser;

    public function __construct(Bot $botParser)
    {
        $this->botParser = $botParser;
    }

    public function getServerUrl(): string
    {
        $protocol = 'http';
        if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            $protocol = $_SERVER['HTTP_X_FORWARDED_PROTO'];
        } elseif (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            $protocol = 'https';
        } elseif (!empty($_SERVER['REQUEST_SCHEME'])) {
            $protocol = $_SERVER['REQUEST_SCHEME'];
        }
        if (empty($_SERVER['SERVER_NAME'])) {
            return '';
        }
        $port = 80;
        if (!empty($_SERVER['SERVER_PORT']) && \is_numeric($_SERVER['SERVER_PORT'])) {
            $port = (int)$_SERVER['SERVER_PORT'];
        }
        $portString = $port === 80 || $port === 443
            ? ''
            : (':' . $port);

        return "{$protocol}://{$_SERVER['SERVER_NAME']}{$portString}";
    }

    public function isVisitorBot(string $userAgent = null): bool
    {
        $this->botParser->setUserAgent($userAgent ?? $_SERVER['HTTP_USER_AGENT'] ?? '');
        $this->botParser->discardDetails();

        return (bool)$this->botParser->parse();
    }

    public function getCurrentUrl(array $parameters = []): string
    {
        if ($parameters === []) {
            return ($_SERVER['QUERY_STRING'] ?? '') !== ''
                ? '?' . $_SERVER['QUERY_STRING']
                : '';
        }
        $queryParameters = \array_merge($_GET ?? [], $parameters);

        return '?' . \http_build_query($queryParameters);
    }

    /**
     * @param string $name
     * @return null|string|array
     */
    public function getValue(string $name)
    {
        return $_POST[$name] ?? $_GET[$name] ?? $_COOKIE[$name] ?? null;
    }

    public function isCliRequest(): bool
    {
        return \PHP_SAPI === 'cli';
    }

    public function getValuesFromGet(): array
    {
        return $_GET ?? [];
    }

    public function getValueFromPost(string $name)
    {
        return $_POST[$name] ?? null;
    }

    public function getValueFromGet(string $name)
    {
        return $_GET[$name] ?? null;
    }

    public function getValueFromCookie(string $name)
    {
        return $_COOKIE[$name] ?? null;
    }

    public function getPathInfo(): string
    {
        return $_SERVER['PATH_INFO'] ?? '';
    }

    public function getQueryString(): string
    {
        return $_SERVER['QUERY_STRING'] ?? '';
    }

    public function getPhpSapi(): string
    {
        return \PHP_SAPI;
    }
}