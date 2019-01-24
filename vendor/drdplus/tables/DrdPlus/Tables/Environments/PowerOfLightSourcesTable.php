<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Environments;

use DrdPlus\Codes\Environment\LightSourceCode;
use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Partials\AbstractFileTable;

/**
 * See PPH page 127 right column, @link https://pph.drdplus.info/#tabulka_sily_svetelnych_zdroju
 */
class PowerOfLightSourcesTable extends AbstractFileTable
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/power_of_light_sources.csv';
    }

    public const POWER = 'power';

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [self::POWER => self::INTEGER];
    }

    public const LIGHT_SOURCE = 'light_source';

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [self::LIGHT_SOURCE];
    }

    /**
     * @param LightSourceCode $lightSourceCode
     * @return int
     */
    public function getPowerOfLightSource(LightSourceCode $lightSourceCode)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getValue($lightSourceCode, self::POWER);
    }

    /**
     * @param LightSourceCode $lightSourceCode
     * @param Distance $distance
     * @return int
     */
    public function calculateLightingQualityInDistance(LightSourceCode $lightSourceCode, Distance $distance)
    {
        return $this->getPowerOfLightSource($lightSourceCode) - 2 * $distance->getBonus()->getValue();
    }

}