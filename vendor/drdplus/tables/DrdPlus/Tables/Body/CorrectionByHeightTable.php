<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Body;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Tables\Partials\AbstractFileTable;
use DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound;
use DrdPlus\Tables\Properties\HeightInterface;

/**
 * See PPH page 40 right column bottom, @link https://pph.drdplus.info/#tabulka_oprav_za_vysku
 * for speed @link https://pph.drdplus.info/#oprava_rychlosti_za_vysku
 * and for fight @link https://pph.drdplus.info/#oprava_boje_za_vysku
 */
class CorrectionByHeightTable extends AbstractFileTable
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/correction_by_height.csv';
    }

    public const CORRECTION = 'correction';

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [self::CORRECTION => self::INTEGER];
    }

    /**
     * @return array
     */
    protected function getRowsHeader(): array
    {
        return [PropertyCode::HEIGHT];
    }

    /**
     * @param HeightInterface $height
     * @return int
     * @throws \DrdPlus\Tables\Body\Exceptions\UnexpectedHeightToGetCorrectionFor
     */
    public function getCorrectionByHeight(HeightInterface $height): int
    {
        try {
            return $this->getValue($height, self::CORRECTION);
        } catch (RequiredRowNotFound $requiredRowNotFound) {
            throw new Exceptions\UnexpectedHeightToGetCorrectionFor(
                "Given height {$height} is out of range to get a correction"
            );
        }
    }
}