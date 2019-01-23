<?php
declare(strict_types=1); 

namespace DrdPlus\Codes;

use DrdPlus\Codes\Partials\TranslatableCode;

/**
 * @method static GenderCode getIt($codeValue)
 * @method static GenderCode findIt($codeValue)
 */
class GenderCode extends TranslatableCode
{
    public const MALE = 'male';
    public const FEMALE = 'female';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [self::MALE, self::FEMALE];
    }

    /**
     * @return bool
     */
    public function isMale(): bool
    {
        return $this->getValue() === self::MALE;
    }

    /**
     * @return bool
     */
    public function isFemale(): bool
    {
        return $this->getValue() === self::FEMALE;
    }

    protected function fetchTranslations(): array
    {
        return [
            self::$CS => [
                self::MALE => [self::$ONE => 'muž'],
                self::FEMALE => [self::$ONE => 'žena']
            ],
            self::$EN => [
                self::MALE => [self::$ONE => 'male'],
                self::FEMALE => [self::$ONE => 'female']
            ]
        ];
    }

}