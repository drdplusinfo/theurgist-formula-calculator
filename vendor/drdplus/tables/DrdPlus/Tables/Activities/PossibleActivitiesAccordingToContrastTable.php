<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Activities;

use DrdPlus\Tables\Partials\AbstractFileTable;
use Granam\Integer\PositiveInteger;

/**
 * See PPH page 129, left column (table without name), @link https://pph.drdplus.info/#tabulka_moznych_cinnosti_podle_kontrastu
 */
class PossibleActivitiesAccordingToContrastTable extends AbstractFileTable
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/possible_activities_according_to_contrast.csv';
    }

    public const POSSIBLE_ACTIVITIES_EXAMPLE = 'possible_activities_example';
    public const FIGHT_TYPE_BY_CONTRAST = 'fight_type_by_contrast';

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [self::POSSIBLE_ACTIVITIES_EXAMPLE => self::STRING, self::FIGHT_TYPE_BY_CONTRAST => self::STRING];
    }

    public const CONTRAST = 'contrast';

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [self::CONTRAST];
    }

    /**
     * @param PositiveInteger $contrast
     * @return string
     */
    public function getPossibleActionsExample(PositiveInteger $contrast): string
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getPossibility($contrast, self::POSSIBLE_ACTIVITIES_EXAMPLE);
    }

    /**
     * @param PositiveInteger $contrast
     * @param string $actionName
     * @return string
     */
    private function getPossibility(PositiveInteger $contrast, string $actionName): string
    {
        $contrastValue = $contrast->getValue();
        if ($contrastValue > 6) {
            $contrastValue = 6;
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getValue($contrastValue, $actionName);
    }

    /**
     * @param PositiveInteger $contrast
     * @return string
     */
    public function getFightTypeByContrast(PositiveInteger $contrast): string
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getPossibility($contrast, self::FIGHT_TYPE_BY_CONTRAST);
    }
}