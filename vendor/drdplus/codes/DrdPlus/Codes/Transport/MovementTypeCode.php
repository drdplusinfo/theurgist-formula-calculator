<?php declare(strict_types=1); 

namespace DrdPlus\Codes\Transport;

use DrdPlus\Codes\Partials\AbstractCode;

/**
 * @method static MovementTypeCode getIt($codeValue)
 * @method static MovementTypeCode findIt($codeValue)
 */
class MovementTypeCode extends AbstractCode
{
    public const WAITING = 'waiting';
    public const WALK = 'walk';
    public const RUSH = 'rush';
    public const RUN = 'run';
    public const SPRINT = 'sprint';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::WAITING,
            self::WALK,
            self::RUSH,
            self::RUN,
            self::SPRINT,
        ];
    }

}