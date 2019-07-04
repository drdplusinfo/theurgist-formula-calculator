<?php declare(strict_types=1);

namespace Granam\WebContentBuilder;

use Granam\Strict\Object\StrictObject;

class CookiesBag extends StrictObject
{
    public const VERSION = 'version';

    /**
     * @param string $cookieName
     * @param $value
     * @param bool $httpOnly forbidden for JS ?
     * @param \DateTime|null $expiresAt null for at end of browser sessions
     * @return bool
     * @throws \Granam\WebContentBuilder\Exceptions\CookieCanNotBeSet
     */
    public function setCookie(string $cookieName, string $value, bool $httpOnly = true, \DateTime $expiresAt = null): bool
    {
        if (PHP_SAPI !== 'cli') {
            $cookieSet = \setcookie(
                $cookieName,
                $value,
                $expiresAt ? $expiresAt->getTimestamp() : 0 /* ends with browser session */,
                '/', // path
                $_SERVER['SERVER_NAME'] ?? '', // domain
                !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off', // secure if possible
                $httpOnly // not HTTP only allows JS to read it
            );
            if (!$cookieSet) {
                throw new Exceptions\CookieCanNotBeSet('Could not set cookie ' . $cookieName);
            }
        }

        $_COOKIE[$cookieName] = $value;

        return true;
    }

    /**
     * @param string $cookieName
     * @return mixed|null
     */
    public function getCookie(string $cookieName)
    {
        return $_COOKIE[$cookieName] ?? null;
    }

    public function deleteCookie(string $cookieName): bool
    {
        $set = $this->setCookie($cookieName, '');
        if ($set) {
            unset($_COOKIE[$cookieName]);
        }

        return $set;
    }
}