<?php
declare(strict_types=1);

namespace DrdPlus\Tables\History;

use DrdPlus\Tables\Partials\AbstractFileTable;
use DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound;
use Granam\Integer\PositiveInteger;

/**
 * See PPH page 38 right column, @link https://pph.drdplus.info/#tabulka_majetku
 */
class PossessionTable extends AbstractFileTable
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/possession.csv';
    }

    public const GOLD_COINS = 'gold_coins';

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            self::GOLD_COINS => self::POSITIVE_INTEGER,
        ];
    }

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [BackgroundPointsTable::BACKGROUND_POINTS];
    }

    /**
     * @param PositiveInteger $backgroundPoints
     * @return int
     * @throws \DrdPlus\Tables\History\Exceptions\UnexpectedBackgroundPoints
     */
    public function getPossessionAsGoldCoins(PositiveInteger $backgroundPoints): int
    {
        try {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getValue($backgroundPoints, self::GOLD_COINS);
        } catch (RequiredRowNotFound $requiredRowNotFound) {
            throw new Exceptions\UnexpectedBackgroundPoints(
                "Given background points {$backgroundPoints} are not supported"
            );
        }
    }

}