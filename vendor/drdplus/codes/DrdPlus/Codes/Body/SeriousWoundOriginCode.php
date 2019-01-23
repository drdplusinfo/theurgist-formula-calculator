<?php
declare(strict_types=1); 

namespace DrdPlus\Codes\Body;

/**
 * @method static SeriousWoundOriginCode getIt($codeValue)
 * @method static SeriousWoundOriginCode findIt($codeValue)
 */
class SeriousWoundOriginCode extends WoundOriginCode
{
    public const MECHANICAL_STAB = 'mechanical_stab';
    public const MECHANICAL_CUT = 'mechanical_cut';
    public const MECHANICAL_CRUSH = 'mechanical_crush';
    public const ELEMENTAL = 'elemental';
    public const PSYCHICAL = 'psychical';

    /**
     * @return SeriousWoundOriginCode
     */
    public static function getMechanicalStabWoundOrigin(): SeriousWoundOriginCode
    {
        return static::getIt(self::MECHANICAL_STAB);
    }

    /**
     * @return SeriousWoundOriginCode
     */
    public static function getMechanicalCutWoundOrigin(): SeriousWoundOriginCode
    {
        return static::getIt(self::MECHANICAL_CUT);
    }

    /**
     * @return SeriousWoundOriginCode
     */
    public static function getMechanicalCrushWoundOrigin(): SeriousWoundOriginCode
    {
        return static::getIt(self::MECHANICAL_CRUSH);
    }

    /**
     * @return SeriousWoundOriginCode
     */
    public static function getElementalWoundOrigin(): SeriousWoundOriginCode
    {
        return static::getIt(self::ELEMENTAL);
    }

    /**
     * @return SeriousWoundOriginCode
     */
    public static function getPsychicalWoundOrigin(): SeriousWoundOriginCode
    {
        return static::getIt(self::PSYCHICAL);
    }

    /**
     * @return bool
     */
    public function isSeriousWoundOrigin(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isOrdinaryWoundOrigin(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isPsychical(): bool
    {
        return $this->getValue() === self::PSYCHICAL;
    }

    /**
     * @return bool
     */
    public function isElemental(): bool
    {
        return $this->getValue() === self::ELEMENTAL;
    }

    /**
     * @return bool
     */
    public function isMechanical(): bool
    {
        return \in_array($this->getValue(), [self::MECHANICAL_STAB, self::MECHANICAL_CUT, self::MECHANICAL_CRUSH], true);
    }

    /**
     * @return bool
     */
    public function isMechanicalStabWoundOrigin(): bool
    {
        return $this->getValue() === self::MECHANICAL_STAB;
    }

    /**
     * @return bool
     */
    public function isMechanicalCutWoundOrigin(): bool
    {
        return $this->getValue() === self::MECHANICAL_CUT;
    }

    /**
     * @return bool
     */
    public function isMechanicalCrushWoundOrigin(): bool
    {
        return $this->getValue() === self::MECHANICAL_CRUSH;
    }

    /**
     * @return bool
     */
    public function isElementalWoundOrigin(): bool
    {
        return $this->getValue() === self::ELEMENTAL;
    }

    /**
     * @return bool
     */
    public function isPsychicalWoundOrigin(): bool
    {
        return $this->getValue() === self::PSYCHICAL;
    }

    protected function fetchTranslations(): array
    {
        return [
            'cs' => [
                self::ELEMENTAL => [self::$ONE => 'elementální'],
                self::PSYCHICAL => [self::$ONE => 'psychické'],
                self::MECHANICAL_CRUSH => [self::$ONE => 'fyzické drtivé'],
                self::MECHANICAL_CUT => [self::$ONE => 'fyzické řezné'],
                self::MECHANICAL_STAB => [self::$ONE => 'fyzické bodné'],
            ],
            'en' => [
                self::ELEMENTAL => [self::$ONE => 'elemental'],
                self::PSYCHICAL => [self::$ONE => 'psychical'],
                self::MECHANICAL_CRUSH => [self::$ONE => 'mechanical crush'],
                self::MECHANICAL_CUT => [self::$ONE => 'mechanical cut'],
                self::MECHANICAL_STAB => [self::$ONE => 'mechanical stab'],
            ],
        ];
    }

}