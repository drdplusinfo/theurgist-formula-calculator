<?php declare(strict_types=1);

namespace DrdPlus\Codes\Properties;

use DrdPlus\Codes\Partials\AbstractCode;

/**
 * @method static RemarkableSenseCode getIt($codeValue)
 * @method static RemarkableSenseCode findIt($codeValue)
 */
class RemarkableSenseCode extends AbstractCode
{
    public const HEARING = 'hearing';
    public const SIGHT = 'sight';
    public const SMELL = 'smell';
    public const TASTE = 'taste';
    public const TOUCH = 'touch';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::HEARING,
            self::SIGHT,
            self::SMELL,
            self::TASTE,
            self::TOUCH,
        ];
    }

}