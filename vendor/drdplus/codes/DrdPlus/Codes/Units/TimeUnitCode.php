<?php
declare(strict_types=1); 

namespace DrdPlus\Codes\Units;

use DrdPlus\Codes\Partials\TranslatableCode;

/**
 * @method static TimeUnitCode getIt($codeValue)
 * @method static TimeUnitCode findIt($codeValue)
 */
class TimeUnitCode extends TranslatableCode
{
    public const ROUND = 'round';
    public const MINUTE = 'minute';
    public const HOUR = 'hour';
    public const DAY = 'day';
    public const MONTH = 'month';
    public const YEAR = 'year';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::ROUND,
            self::MINUTE,
            self::HOUR,
            self::DAY,
            self::MONTH,
            self::YEAR,
        ];
    }

    /**
     * @return array|string[]
     */
    protected function fetchTranslations(): array
    {
        return [
            'en' => [
                self::ROUND => [self::$ONE => 'round', self::$FEW => 'rounds', self::$MANY => 'rounds'],
                self::MINUTE => [self::$ONE => 'minute', self::$FEW => 'minutes', self::$MANY => 'minutes'],
                self::HOUR => [self::$ONE => 'hour', self::$FEW => 'hours', self::$MANY => 'hours'],
                self::DAY => [self::$ONE => 'day', self::$FEW => 'days', self::$MANY => 'days'],
                self::MONTH => [self::$ONE => 'month', self::$FEW => 'months', self::$MANY => 'months'],
                self::YEAR => [self::$ONE => 'year', self::$FEW => 'years', self::$MANY => 'years'],
            ],
            'cs' => [
                self::ROUND => [self::$ONE => 'kolo', self::$FEW => 'kola', self::$MANY => 'kol'],
                self::MINUTE => [self::$ONE => 'minuta', self::$FEW => 'minuty', self::$MANY => 'minut'],
                self::HOUR => [self::$ONE => 'hodina', self::$FEW => 'hodiny', self::$MANY => 'hodin'],
                self::DAY => [self::$ONE => 'den', self::$FEW => 'dny', self::$MANY => 'dní'],
                self::MONTH => [self::$ONE => 'měsíc', self::$FEW => 'měsíce', self::$MANY => 'měsíců'],
                self::YEAR => [self::$ONE => 'rok', self::$FEW => 'roky', self::$MANY => 'let'],
            ],
        ];
    }
}