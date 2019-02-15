<?php
declare(strict_types=1);

namespace DrdPlus\Tables\History;

use DrdPlus\Codes\History\AncestryCode;
use DrdPlus\Tables\Partials\AbstractFileTable;
use DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound;
use Granam\Integer\PositiveInteger;

/**
 * See PPH page 38 left column, @link https://pph.drdplus.info/#tabulka_puvodu
 */
class AncestryTable extends AbstractFileTable
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/ancestry.csv';
    }

    public const ANCESTRY = 'ancestry';

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [self::ANCESTRY => self::STRING];
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
     * @return AncestryCode
     * @throws \DrdPlus\Tables\History\Exceptions\UnexpectedBackgroundPoints
     */
    public function getAncestryCodeByBackgroundPoints(PositiveInteger $backgroundPoints): AncestryCode
    {
        try {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return AncestryCode::getIt($this->getValue($backgroundPoints, self::ANCESTRY));
        } catch (RequiredRowNotFound $requiredRowNotFound) {
            throw new Exceptions\UnexpectedBackgroundPoints(
                "Given background points value {$backgroundPoints} is out of range"
            );
        }
    }

    /**
     * @param AncestryCode $ancestryCode
     * @return int
     * @throws \DrdPlus\Tables\History\Exceptions\UnknownAncestryCode
     */
    public function getBackgroundPointsByAncestryCode(AncestryCode $ancestryCode): int
    {
        foreach ($this->getIndexedValues() as $points => $wrappedAncestry) {
            $currentAncestry = end($wrappedAncestry);
            if ($currentAncestry === $ancestryCode->getValue()) {
                return $points;
            }
        }

        throw new Exceptions\UnknownAncestryCode("Given ancestry {$ancestryCode} is not known");
    }

}