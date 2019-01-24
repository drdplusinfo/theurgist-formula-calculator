<?php
declare(strict_types=1); 

namespace DrdPlus\Codes;

use DrdPlus\Codes\Partials\AbstractCode;

/**
 * @method static JumpTypeCode getIt($codeValue)
 * @method static JumpTypeCode findIt($codeValue)
 */
class JumpTypeCode extends AbstractCode
{
    public const HIGH_JUMP = 'high_jump';
    public const BROAD_JUMP = 'broad_jump';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::HIGH_JUMP,
            self::BROAD_JUMP,
        ];
    }

}