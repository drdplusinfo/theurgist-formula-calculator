<?php declare(strict_types=1);

namespace DrdPlus\Codes\Transport;

use DrdPlus\Codes\Partials\FileBasedTranslatableCode;

/**
 * @method static RidingAnimalMovementCode getIt($codeValue)
 * @method static RidingAnimalMovementCode findIt($codeValue)
 */
class RidingAnimalMovementCode extends FileBasedTranslatableCode
{
    public const STILL = 'still';
    public const GAIT = 'gait';
    public const TROT = 'trot';
    public const CANTER = 'canter';
    public const GALLOP = 'gallop';
    public const JUMPING = 'jumping';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::STILL,
            self::GAIT,
            self::TROT,
            self::CANTER,
            self::GALLOP,
            self::JUMPING,
        ];
    }

    /**
     * @return array|string[]
     */
    public static function getPossibleValuesWithoutJumping(): array
    {
        return array_values(array_diff(static::getPossibleValues(), [self::JUMPING]));
    }

    protected function getTranslationsFileName(): string
    {
        return __DIR__ . '/data/riding_animal_movement_code.csv';
    }
}