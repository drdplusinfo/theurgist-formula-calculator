<?php
declare(strict_types=1);

namespace DrdPlus\Codes\History;

use DrdPlus\Codes\Partials\AbstractCode;

/**
 * @method static ChoiceCode getIt($codeValue)
 * @method static ChoiceCode findIt($codeValue)
 */
class ChoiceCode extends AbstractCode
{
    public const FORTUNE = 'fortune';
    public const PLAYER_DECISION = 'player_decision';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::FORTUNE,
            self::PLAYER_DECISION,
        ];
    }
}