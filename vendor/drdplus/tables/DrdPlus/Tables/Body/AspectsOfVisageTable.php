<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Body;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Tables\Partials\AbstractTable;

/**
 * See PPH page 41 right column, @link https://pph.drdplus.info/#tabulka_aspektu_vzhledu
 */
class AspectsOfVisageTable extends AbstractTable
{
    public const ASPECT_OF_VISAGE = 'aspect_of_visage';

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [self::ASPECT_OF_VISAGE];
    }

    public const FIRST_PROPERTY = 'first_property';
    public const SECOND_PROPERTY = 'second_property';
    public const SUM_OF_FIRST_AND_SECOND_PROPERTY_DIVISOR = 'sum_of_first_and_second_property_divisor';
    public const THIRD_PROPERTY = 'third_property';
    public const THIRD_PROPERTY_DIVISOR = 'third_property_divisor';

    /**
     * @return array|string[]
     */
    protected function getColumnsHeader(): array
    {
        return [
            self::FIRST_PROPERTY,
            self::SECOND_PROPERTY,
            self::SUM_OF_FIRST_AND_SECOND_PROPERTY_DIVISOR,
            self::THIRD_PROPERTY,
            self::THIRD_PROPERTY_DIVISOR,
        ];
    }

    /**
     * @return array|string[]
     */
    public function getIndexedValues(): array
    {
        return [
            PropertyCode::BEAUTY => [
                self::FIRST_PROPERTY => PropertyCode::AGILITY,
                self::SECOND_PROPERTY => PropertyCode::KNACK,
                self::SUM_OF_FIRST_AND_SECOND_PROPERTY_DIVISOR => 2,
                self::THIRD_PROPERTY => PropertyCode::CHARISMA,
                self::THIRD_PROPERTY_DIVISOR => 2,
            ],
            PropertyCode::DANGEROUSNESS => [
                self::FIRST_PROPERTY => PropertyCode::STRENGTH,
                self::SECOND_PROPERTY => PropertyCode::WILL,
                self::SUM_OF_FIRST_AND_SECOND_PROPERTY_DIVISOR => 2,
                self::THIRD_PROPERTY => PropertyCode::CHARISMA,
                self::THIRD_PROPERTY_DIVISOR => 2,
            ],
            PropertyCode::DIGNITY => [
                self::FIRST_PROPERTY => PropertyCode::INTELLIGENCE,
                self::SECOND_PROPERTY => PropertyCode::WILL,
                self::SUM_OF_FIRST_AND_SECOND_PROPERTY_DIVISOR => 2,
                self::THIRD_PROPERTY => PropertyCode::CHARISMA,
                self::THIRD_PROPERTY_DIVISOR => 2,
            ],
        ];
    }
}