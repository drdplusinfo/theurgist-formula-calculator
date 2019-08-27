<?php declare(strict_types=1);

namespace DrdPlus\RulesSkeleton;

use Granam\Strict\Object\StrictObject;

class CookiesService extends StrictObject
{
    public const VERSION = 'version';

    /** @var Request */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function setMinorVersionCookie(string $version): bool
    {
        return $this->setCookie(static::VERSION, $version, true /* not accessible from JS */, new \DateTime('+ 1 year'));
    }

    /**
     * @param string $cookieName
     * @param $value
     * @param bool $httpOnly forbidden for JS ?
     * @param \DateTimeInterface|null $expiresAt null for at end of browser sessions
     * @return bool
     * @throws \DrdPlus\RulesSkeleton\Exceptions\CookieCanNotBeSet
     */
    public function setCookie(string $cookieName, string $value, bool $httpOnly = true, \DateTimeInterface $expiresAt = null): bool
    {
        if (!$this->request->isCliRequest()) {
            $cookieSet = \setcookie(
                $cookieName,
                $value,
                $expiresAt ? $expiresAt->getTimestamp() : 0 /* ends with browser session */,
                '/', // path
                $this->request->getServerName(), // domain
                $this->request->isHttpsUsed(), // secure if possible
                $httpOnly // not HTTP only allows JS to read it
            );
            if (!$cookieSet) {
                throw new Exceptions\CookieCanNotBeSet('Could not set cookie ' . $cookieName);
            }
        }

        $this->request->overwriteCookie($cookieName, $value);

        return true;
    }

    /**
     * @param string $cookieName
     * @return mixed|null
     */
    public function getCookie(string $cookieName)
    {
        return $this->request->getValueFromCookie($cookieName);
    }

    public function deleteCookie(string $cookieName): bool
    {
        $set = $this->setCookie($cookieName, '');
        if ($set) {
            $this->request->deleteCookie($cookieName);
        }

        return $set;
    }
}