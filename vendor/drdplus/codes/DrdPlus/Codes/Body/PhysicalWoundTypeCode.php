<?php declare(strict_types=1);

namespace DrdPlus\Codes\Body;

use DrdPlus\Codes\Partials\TranslatableCode;

/**
 * @method static PhysicalWoundTypeCode getIt($codeValue)
 * @method static PhysicalWoundTypeCode findIt($codeValue)
 */
class PhysicalWoundTypeCode extends TranslatableCode
{
    public const CRUSH = 'crush';
    public const STAB = 'stab';
    public const CUT = 'cut';

    /**
     * @return array|string
     */
    public static function getPossibleValues(): array
    {
        return [
            self::CRUSH,
            self::STAB,
            self::CUT,
        ];
    }

    /**
     * @return bool
     */
    public function isCrush(): bool
    {
        return $this->getValue() === self::CRUSH;
    }

    /**
     * @return bool
     */
    public function isStab(): bool
    {
        return $this->getValue() === self::STAB;
    }

    /**
     * @return bool
     */
    public function isCut(): bool
    {
        return $this->getValue() === self::CUT;
    }

    protected function fetchTranslations(): array
    {
        return [
            'cs' => [
                self::STAB => [self::$ONE => 'bodné', self::$FEW => 'bodná', self::$MANY => 'bodných'],
                self::CRUSH => [self::$ONE => 'drtivé', self::$FEW => 'drtivá', self::$MANY => 'drtivých'],
                self::CUT => [self::$ONE => 'sečné', self::$FEW => 'sečná', self::$MANY => 'sečných'],
            ],
            'en' => [
                self::STAB => [self::$ONE => 'stab', self::$FEW => 'stabs', self::$MANY => 'stabs'],
                self::CRUSH => [self::$ONE => 'crush', self::$FEW => 'crushes', self::$MANY => 'crushes'],
                self::CUT => [self::$ONE => 'cut', self::$FEW => 'cuts', self::$MANY => 'cuts'],
            ],
        ];
    }
}