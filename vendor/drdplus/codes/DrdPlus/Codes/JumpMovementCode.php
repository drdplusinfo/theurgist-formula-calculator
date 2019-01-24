<?php
declare(strict_types=1); 

namespace DrdPlus\Codes;

use DrdPlus\Codes\Partials\AbstractCode;

/**
 * @method static JumpMovementCode getIt($codeValue)
 * @method static JumpMovementCode findIt($codeValue)
 */
class JumpMovementCode extends AbstractCode
{
    public const STANDING_JUMP = 'standing_jump';
    public const FLYING_JUMP = 'flying_jump';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::STANDING_JUMP,
            self::FLYING_JUMP,
        ];
    }

}