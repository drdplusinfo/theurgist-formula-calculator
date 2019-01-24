<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\CookiesService;
use Granam\Strict\Object\StrictObject;

class History extends StrictObject
{
    private const CONFIGURATOR_HISTORY = 'configurator_history';
    private const CONFIGURATOR_HISTORY_TOKEN = 'configurator_history_token';
    private const FORGOT_HISTORY = 'forgot_configurator_history';

    /** @var CookiesService */
    private $cookiesService;
    /** @var string */
    private $cookiesPostfix;
    /** @var array */
    private $historyValues = [];

    public function __construct(
        CookiesService $cookiesService,
        bool $deletePreviousHistory,
        array $valuesToRemember,
        bool $rememberHistory,
        string $cookiesPostfix,
        int $cookiesTtl = null
    )
    {
        $this->cookiesService = $cookiesService;
        $this->cookiesPostfix = $cookiesPostfix;
        if ($deletePreviousHistory) {
            $this->deleteHistory();
        }
        if (\count($valuesToRemember) > 0) {
            if (!$rememberHistory) {
                $this->deleteHistory();
                $cookiesService->setCookie(self::FORGOT_HISTORY . '-' . $cookiesPostfix, '1', false, $this->createCookiesTtlDate($cookiesTtl));
            }
        } elseif (!$this->cookieHistoryIsValid()) {
            $this->deleteHistory();
        }
        if (!empty($_COOKIE[self::CONFIGURATOR_HISTORY . '-' . $cookiesPostfix])) {
            $historyValues = \unserialize($_COOKIE[self::CONFIGURATOR_HISTORY . '-' . $cookiesPostfix], ['allowed_classes' => []]);
            if (\is_array($historyValues)) {
                $this->historyValues = $historyValues;
            }
        }
        if ($rememberHistory && \count($valuesToRemember) > 0) {
            $this->remember($valuesToRemember, $this->createCookiesTtlDate($cookiesTtl));
        }
    }

    protected function createCookiesTtlDate(?int $cookiesTtl): \DateTime
    {
        return $cookiesTtl !== null
            ? new \DateTime('@' . (\time() + $cookiesTtl))
            : new \DateTime('+ 1 year');
    }

    protected function remember(array $valuesToRemember, \DateTime $cookiesTtlDate): void
    {
        $this->cookiesService->deleteCookie(self::FORGOT_HISTORY . '-' . $this->cookiesPostfix);
        $this->cookiesService->setCookie(self::CONFIGURATOR_HISTORY . '-' . $this->cookiesPostfix, \serialize($valuesToRemember), false, $cookiesTtlDate);
        $this->cookiesService->setCookie(self::CONFIGURATOR_HISTORY_TOKEN . '-' . $this->cookiesPostfix, \md5_file(__FILE__), false, $cookiesTtlDate);
    }

    protected function deleteHistory(): void
    {
        $this->cookiesService->deleteCookie(self::CONFIGURATOR_HISTORY_TOKEN . '-' . $this->cookiesPostfix);
        $this->cookiesService->deleteCookie(self::CONFIGURATOR_HISTORY . '-' . $this->cookiesPostfix);
    }

    private function cookieHistoryIsValid(): bool
    {
        return !empty($_COOKIE[self::CONFIGURATOR_HISTORY_TOKEN . '-' . $this->cookiesPostfix])
            && $_COOKIE[self::CONFIGURATOR_HISTORY_TOKEN . '-' . $this->cookiesPostfix] === \md5_file(__FILE__);
    }

    public function shouldForgotHistory(): bool
    {
        return !empty($_COOKIE[self::FORGOT_HISTORY . '-' . $this->cookiesPostfix]);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getValue(string $name)
    {
        if (\array_key_exists($name, $this->historyValues) && $this->cookieHistoryIsValid()) {
            return $this->historyValues[$name];
        }

        return null;
    }
}