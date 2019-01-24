<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\CookiesService;
use Granam\Strict\Object\StrictObject;

class Memory extends StrictObject implements \IteratorAggregate
{
    private const CONFIGURATOR_MEMORY = 'configurator_memory';
    private const CONFIGURATOR_MEMORY_TOKEN = 'configurator_memory_token';
    private const FORGOT_MEMORY = 'forgot_configurator_memory';


    /** @var CookiesService */
    private $cookiesService;
    /** @var string */
    private $cookiesPostfix;
    /** @var array */
    private $memoryValues = [];
    /** @var \DateTime */
    private $cookiesTtlDate;

    public function __construct(
        CookiesService $cookiesService,
        bool $deletePreviousMemory,
        array $valuesToRemember,
        bool $rememberCurrent,
        string $cookiesPostfix,
        int $cookiesTtl = null
    )
    {
        $this->cookiesService = $cookiesService;
        $this->cookiesPostfix = $cookiesPostfix;
        if ($deletePreviousMemory) {
            $this->deleteMemory();
        }
        if (\count($valuesToRemember) > 0) {
            if ($rememberCurrent) {
                $this->remember($valuesToRemember, $this->createCookiesTtlDate($cookiesTtl));
            } else {
                $this->deleteMemory();
                $cookiesService->setCookie(self::FORGOT_MEMORY . '-' . $cookiesPostfix, '1', false, $this->createCookiesTtlDate($cookiesTtl));
            }
        } elseif (!$this->cookieMemoryIsValid()) {
            $this->deleteMemory();
        }
        if (!empty($_COOKIE[self::CONFIGURATOR_MEMORY . '-' . $cookiesPostfix])) {
            $memoryValues = \unserialize($_COOKIE[self::CONFIGURATOR_MEMORY . '-' . $cookiesPostfix], ['allowed_classes' => []]);
            if (\is_array($memoryValues)) {
                $this->memoryValues = $memoryValues;
            }
        }
        $this->cookiesTtlDate = $this->createCookiesTtlDate($cookiesTtl);
    }

    protected function createCookiesTtlDate(?int $cookiesTtl): ?\DateTime
    {
        return $cookiesTtl !== null
            ? new \DateTime('@' . (\time() + $cookiesTtl))
            : null;
    }

    protected function remember(array $valuesToRemember, ?\DateTime $cookiesTtlDate): void
    {
        $this->cookiesService->deleteCookie(self::FORGOT_MEMORY . '-' . $this->cookiesPostfix);
        $this->cookiesService->setCookie(self::CONFIGURATOR_MEMORY . '-' . $this->cookiesPostfix, \serialize($valuesToRemember), false, $cookiesTtlDate);
        $this->cookiesService->setCookie(self::CONFIGURATOR_MEMORY_TOKEN . '-' . $this->cookiesPostfix, \md5_file(__FILE__), false, $cookiesTtlDate);
    }

    protected function deleteMemory(): void
    {
        $this->cookiesService->deleteCookie(self::CONFIGURATOR_MEMORY_TOKEN . '-' . $this->cookiesPostfix);
        $this->cookiesService->deleteCookie(self::CONFIGURATOR_MEMORY . '-' . $this->cookiesPostfix);
    }

    private function cookieMemoryIsValid(): bool
    {
        return !empty($_COOKIE[self::CONFIGURATOR_MEMORY_TOKEN . '-' . $this->cookiesPostfix])
            && $_COOKIE[self::CONFIGURATOR_MEMORY_TOKEN . '-' . $this->cookiesPostfix] === \md5_file(__FILE__);
    }

    public function shouldForgotMemory(): bool
    {
        return !empty($_COOKIE[self::FORGOT_MEMORY . '-' . $this->cookiesPostfix]);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getValue(string $name)
    {
        if (\array_key_exists($name, $this->memoryValues) && $this->cookieMemoryIsValid()) {
            return $this->memoryValues[$name];
        }

        return null;
    }

    /**
     * @param string $name
     * @param $values
     */
    public function rewrite(string $name, $values): void
    {
        $this->memoryValues[$name] = $values;
        $this->remember($this->memoryValues, $this->cookiesTtlDate);
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->memoryValues);
    }

}