<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Environments;

use DrdPlus\Codes\Environment\LightSourceEnvironmentCode;
use DrdPlus\Tables\Partials\AbstractFileTable;

/**
 * See PPH page 128 left column, @link https://pph.drdplus.info/#tabulka_zvyseni_sily_zdroje
 */
class ImprovementOfLightSourceTable extends AbstractFileTable
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/improvement_of_light_source.csv';
    }

    public const IMPROVEMENT_OF_LIGHT_SOURCE = 'improvement_of_light_source';

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [self::IMPROVEMENT_OF_LIGHT_SOURCE => self::POSITIVE_INTEGER];
    }

    public const ENVIRONMENT = 'environment';

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [self::ENVIRONMENT];
    }

    /**
     * @param LightSourceEnvironmentCode $lightSourceEnvironmentCode
     * @return int
     */
    public function getLightSourceImprovement(LightSourceEnvironmentCode $lightSourceEnvironmentCode)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getValue($lightSourceEnvironmentCode, self::IMPROVEMENT_OF_LIGHT_SOURCE);
    }

}