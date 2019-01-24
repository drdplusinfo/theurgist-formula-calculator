<?php
declare(strict_types=1); 

namespace DrdPlus\Codes\Transport;

use DrdPlus\Codes\Partials\AbstractCode;
use DrdPlus\Codes\Properties\PropertyCode;

/**
 * @method static RidingAnimalPropertyCode getIt($codeValue)
 * @method static RidingAnimalPropertyCode findIt($codeValue)
 */
class RidingAnimalPropertyCode extends AbstractCode
{
    public const SPEED = PropertyCode::SPEED;
    public const ENDURANCE = PropertyCode::ENDURANCE;
    public const MAXIMAL_LOAD = PropertyCode::MAXIMAL_LOAD;
    public const MAXIMAL_LOAD_IN_KG = 'maximal_load_in_kg';
    public const DEFIANCE = 'defiance';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::SPEED,
            self::ENDURANCE,
            self::MAXIMAL_LOAD,
            self::MAXIMAL_LOAD_IN_KG,
            self::DEFIANCE,
        ];
    }
}