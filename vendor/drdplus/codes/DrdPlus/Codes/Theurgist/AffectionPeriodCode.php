<?php
declare(strict_types = 1);

namespace DrdPlus\Codes\Theurgist;

/**
 * @method static AffectionPeriodCode getIt($codeValue)
 * @method static AffectionPeriodCode findIt($codeValue)
 */
class AffectionPeriodCode extends AbstractTheurgistCode
{
    public const DAILY = 'daily';
    public const MONTHLY = 'monthly';
    public const YEARLY = 'yearly';
    public const LIFE = 'life';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::DAILY,
            self::MONTHLY,
            self::YEARLY,
            self::LIFE,
        ];
    }

    private static $translations = [
        'cs' => [
            self::DAILY => 'denní',
            self::MONTHLY => 'měsíční',
            self::YEARLY => 'roční',
            self::LIFE => 'životní',
        ],
    ];

    /**
     * @param string $languageCode
     * @return array
     */
    protected function getTranslations(string $languageCode): array
    {
        return self::$translations[$languageCode] ?? [];
    }

}