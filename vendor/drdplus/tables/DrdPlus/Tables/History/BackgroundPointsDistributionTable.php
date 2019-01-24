<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\History;

use DrdPlus\Codes\History\AncestryCode;
use DrdPlus\Codes\History\ExceptionalityCode;
use DrdPlus\Tables\Partials\AbstractFileTable;
use DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound;

/**
 * See PPH page 38 left column, @link https://pph.drdplus.info/#tabulka_rozdeleni_bodu_zazemi
 */
class BackgroundPointsDistributionTable extends AbstractFileTable
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/background_points_distribution.csv';
    }

    public const MAX_POINTS = 'max_points';
    public const MORE_THAN_FOR_ANCESTRY_UP_TO = 'more_than_for_ancestry_up_to';

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            self::MAX_POINTS => self::POSITIVE_INTEGER,
            self::MORE_THAN_FOR_ANCESTRY_UP_TO => self::POSITIVE_INTEGER,
        ];
    }

    public const BACKGROUND = 'background';

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [self::BACKGROUND];
    }

    /**
     * @param ExceptionalityCode $exceptionalityCode
     * @param AncestryTable $ancestryTable
     * @param AncestryCode $ancestryCode
     * @return int
     * @throws \DrdPlus\Tables\History\Exceptions\UnknownExceptionalityCode
     */
    public function getMaxPointsToDistribute(
        ExceptionalityCode $exceptionalityCode,
        AncestryTable $ancestryTable,
        AncestryCode $ancestryCode
    ): int
    {
        try {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            $row = $this->getRow($exceptionalityCode);
        } catch (RequiredRowNotFound $requiredRowNotFound) {
            throw new Exceptions\UnknownExceptionalityCode("Given exceptionality {$exceptionalityCode} is not known");
        }
        $maxPointsToDistribute = $row[self::MAX_POINTS];
        $moreThanAncestryUpTo = $row[self::MORE_THAN_FOR_ANCESTRY_UP_TO];
        if ($moreThanAncestryUpTo === false) {
            return $maxPointsToDistribute;
        }
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $backgroundPointsForAncestry = $ancestryTable->getBackgroundPointsByAncestryCode($ancestryCode);
        if ($backgroundPointsForAncestry + $moreThanAncestryUpTo >= $maxPointsToDistribute) {
            return $maxPointsToDistribute;
        }

        return $backgroundPointsForAncestry + $moreThanAncestryUpTo;
    }

}