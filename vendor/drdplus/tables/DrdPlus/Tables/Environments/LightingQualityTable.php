<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Environments;

use DrdPlus\Codes\Environment\LightConditionsCode;
use DrdPlus\Tables\Partials\AbstractFileTable;

/**
 * See PPH page 127 right column, @link https://pph.drdplus.info/#tabulka_kvality_osvetleni
 */
class LightingQualityTable extends AbstractFileTable
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/lighting_quality.csv';
    }

    public const QUALITY = 'quality';

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [self::QUALITY => self::INTEGER];
    }

    public const LIGHT_CONDITIONS = 'light_conditions';

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [self::LIGHT_CONDITIONS];
    }

    /**
     * @param LightConditionsCode $lightConditionsCode
     * @return int
     */
    public function getLightingQualityOnConditions(LightConditionsCode $lightConditionsCode)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getValue($lightConditionsCode, self::QUALITY);
    }
}