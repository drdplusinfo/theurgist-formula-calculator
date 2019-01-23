<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Body;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Tables\Partials\AbstractTable;

/**
 * See PPH page 41 right column, @link https://pph.drdplus.info/#tabulka_meze_zraneni_a_unavy
 */
class WoundAndFatigueBoundariesTable extends AbstractTable
{
    public const BOUNDARY = 'boundary';

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [self::BOUNDARY];
    }

    public const PROPERTY = 'property';

    /**
     * @return array|string[]
     */
    protected function getColumnsHeader(): array
    {
        return [self::PROPERTY];
    }

    /**
     * @return array
     */
    public function getIndexedValues(): array
    {
        return [
            PropertyCode::WOUND_BOUNDARY => [self::PROPERTY => PropertyCode::TOUGHNESS],
            PropertyCode::FATIGUE_BOUNDARY => [self::PROPERTY => PropertyCode::ENDURANCE],
        ];
    }
}