<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Combat;

use DrdPlus\Codes\CombatCharacteristicCode;
use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Tables\Partials\AbstractTable;

/**
 * See PPH page 34 left column, @link https://pph.drdplus.info/#tabulka_bojovych_charakteristik
 */
class CombatCharacteristicsTable extends AbstractTable
{
    public const CHARACTERISTIC = 'characteristic';

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [self::CHARACTERISTIC];
    }

    public const PROPERTY = 'property';
    public const DIVIDE_BY = 'divide_by';
    public const ROUND_UP = 'round_up';
    public const ROUND_DOWN = 'round_down';

    /**
     * @return array|string[]
     */
    protected function getColumnsHeader(): array
    {
        return [
            self::PROPERTY,
            self::DIVIDE_BY,
            self::ROUND_UP,
            self::ROUND_DOWN,
        ];
    }

    /**
     * @return array|string[][]
     */
    public function getIndexedValues(): array
    {
        return [
            CombatCharacteristicCode::ATTACK => [self::PROPERTY => PropertyCode::AGILITY, self::DIVIDE_BY => 2, self::ROUND_UP => false, self::ROUND_DOWN => true],
            CombatCharacteristicCode::DEFENSE => [self::PROPERTY => PropertyCode::AGILITY, self::DIVIDE_BY => 2, self::ROUND_UP => true, self::ROUND_DOWN => false],
            CombatCharacteristicCode::SHOOTING => [self::PROPERTY => PropertyCode::KNACK, self::DIVIDE_BY => 2, self::ROUND_UP => false, self::ROUND_DOWN => true],
        ];
    }

}