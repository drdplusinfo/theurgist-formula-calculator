<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\History;

use DrdPlus\Codes\History\FateCode;
use DrdPlus\Tables\Partials\AbstractFileTable;
use DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound;

/**
 * See PPH page 37 right column, @link https://pph.drdplus.info/#tabulka_bodu_zazemi
 */
class BackgroundPointsTable extends AbstractFileTable
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/background_points.csv';
    }

    public const BACKGROUND_POINTS = 'background_points';

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            self::BACKGROUND_POINTS => self::POSITIVE_INTEGER,
        ];
    }

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [
            PlayerDecisionsTable::FATE,
        ];
    }

    /**
     * @param FateCode $fateCode
     * @return int
     * @throws \DrdPlus\Tables\History\Exceptions\UnknownFate
     */
    public function getBackgroundPointsByPlayerDecision(FateCode $fateCode): int
    {
        try {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getValue($fateCode, self::BACKGROUND_POINTS);
        } catch (RequiredRowNotFound $requiredRowNotFound) {
            throw new Exceptions\UnknownFate('Unknown fate ' . $fateCode->getValue());
        }
    }

}