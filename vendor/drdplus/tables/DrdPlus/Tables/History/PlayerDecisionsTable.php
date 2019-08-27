<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\History;

use DrdPlus\Codes\History\FateCode;
use DrdPlus\Tables\Partials\AbstractFileTable;

/**
 * See PPH page 30, @link https://pph.drdplus.info/#tabulka_rozhodnuti_hrace
 */
class PlayerDecisionsTable extends AbstractFileTable
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/player_decisions.csv';
    }

    public const POINTS_TO_PRIMARY_PROPERTIES = 'points_to_primary_properties';
    public const POINTS_TO_SECONDARY_PROPERTIES = 'points_to_secondary_properties';
    public const MAXIMUM_TO_SINGLE_PROPERTY = 'maximum_to_single_property';

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            self::POINTS_TO_PRIMARY_PROPERTIES => self::POSITIVE_INTEGER,
            self::POINTS_TO_SECONDARY_PROPERTIES => self::POSITIVE_INTEGER,
            self::MAXIMUM_TO_SINGLE_PROPERTY => self::POSITIVE_INTEGER,
        ];
    }

    public const FATE = 'fate';

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [self::FATE];
    }

    /**
     * @param FateCode $fateCode
     * @return int
     */
    public function getPointsToPrimaryProperties(FateCode $fateCode): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getValue($fateCode, self::POINTS_TO_PRIMARY_PROPERTIES);
    }

    /**
     * @param FateCode $fateCode
     * @return int
     */
    public function getPointsToSecondaryProperties(FateCode $fateCode): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getValue($fateCode, self::POINTS_TO_SECONDARY_PROPERTIES);
    }

    /**
     * @param FateCode $fateCode
     * @return int
     */
    public function getMaximumToSingleProperty(FateCode $fateCode): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getValue($fateCode, self::MAXIMUM_TO_SINGLE_PROPERTY);
    }
}