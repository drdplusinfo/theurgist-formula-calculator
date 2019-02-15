<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Environments;

use DrdPlus\Codes\ActivityIntensityCode;
use DrdPlus\Tables\Partials\AbstractFileTable;
use DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound;

/**
 * See PPH page 135 right column, @link https://pph.drdplus.info/#tabulka_postihu_k_automatickemu_hledani
 */
class MalusesToAutomaticSearchingTable extends AbstractFileTable
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/maluses_to_automatic_searching.csv';
    }

    public const MALUS = 'malus';

    /**
     * @return string[]|array
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [self::MALUS => self::NEGATIVE_INTEGER];
    }

    public const AT_THE_SAME_TIME_WITH = 'at_the_same_time_with';

    /**
     * @return array
     */
    protected function getRowsHeader(): array
    {
        return [self::AT_THE_SAME_TIME_WITH];
    }

    /**
     * @param ActivityIntensityCode $activityIntensityCode
     * @return int
     * @throws \DrdPlus\Tables\Environments\Exceptions\CanNotSearchWithCurrentActivity
     */
    public function getMalusWhenSearchingAtTheSameTimeWith(ActivityIntensityCode $activityIntensityCode)
    {
        try {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getValue($activityIntensityCode, self::MALUS);
        } catch (RequiredRowNotFound $requiredRowNotFound) {
            throw new Exceptions\CanNotSearchWithCurrentActivity(
                "Can not search when doing '$activityIntensityCode'"
            );
        }
    }
}