<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\Configuration;
use DrdPlus\RulesSkeleton\Dirs;
use Granam\Integer\Tools\ToInteger;

/**
 * @method static CalculatorConfiguration createFromYml(Dirs $dirs)
 */
class CalculatorConfiguration extends Configuration
{
    public const COOKIES_POSTFIX = 'cookies_postfix';
    public const COOKIES_TTL = 'cookies_ttl';

    public function __construct(Dirs $dirs, array $settings)
    {
        $this->sanitizeCookiesTtl($settings);
        $this->guardValidCookiesPostfix($settings);
        parent::__construct($dirs, $settings);
    }

    /**
     * @param array $settings
     * @throws \DrdPlus\CalculatorSkeleton\Exceptions\CookiesPostfixIsMissing
     */
    protected function guardValidCookiesPostfix(array $settings): void
    {
        if (($settings[static::WEB][static::COOKIES_POSTFIX] ?? '') === '') {
            throw new Exceptions\CookiesPostfixIsMissing(
                sprintf(
                    'Given cookies postfix are empty in web: %s, got %s',
                    static::COOKIES_POSTFIX,
                    array_key_exists(static::COOKIES_POSTFIX, $settings)
                        ? "'{$settings[static::WEB][static::COOKIES_POSTFIX]}'"
                        : 'nothing'
                )
            );
        }
    }

    /**
     * @param array $settings
     * @throws \DrdPlus\CalculatorSkeleton\Exceptions\InvalidCookiesTtl
     */
    protected function sanitizeCookiesTtl(array &$settings): void
    {
        try {
            $settings[static::WEB][static::COOKIES_TTL] = ToInteger::toPositiveIntegerOrNull($settings[static::WEB][static::COOKIES_TTL] ?? null);
        } catch (\Granam\Integer\Tools\Exceptions\Runtime $runtime) {
            throw new Exceptions\InvalidCookiesTtl(
                'Expected positive integer or null, got ' . var_export($settings[static::WEB][static::COOKIES_TTL] ?? null, true)
            );
        }
    }

    public function getCookiesPostfix(): string
    {
        return $this->getSettings()[static::WEB][static::COOKIES_POSTFIX];
    }

    public function getCookiesTtl(): ?int
    {
        return $this->getSettings()[static::WEB][static::COOKIES_TTL];

    }
}