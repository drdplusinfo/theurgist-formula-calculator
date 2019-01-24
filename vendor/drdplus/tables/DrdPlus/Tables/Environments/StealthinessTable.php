<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Environments;

use DrdPlus\Codes\Environment\ItemStealthinessCode;
use DrdPlus\Tables\Partials\AbstractFileTable;
use DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound;

/**
 * See PPH page 136 right column, @link https://pph.drdplus.info/#tabulka_nenapadnosti
 */
class StealthinessTable extends AbstractFileTable
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/stealthiness.csv';
    }

    public const STEALTHINESS = 'stealthiness';

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            self::STEALTHINESS => self::POSITIVE_INTEGER,
        ];
    }

    public const SITUATION = 'situation';

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [self::SITUATION];
    }

    /**
     * @param ItemStealthinessCode $itemStealthinessCode
     * @return int
     * @throws \DrdPlus\Tables\Environments\Exceptions\UnknownStealthinessCode
     */
    public function getStealthinessOnSituation(ItemStealthinessCode $itemStealthinessCode)
    {
        try {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getValue($itemStealthinessCode, self::STEALTHINESS);
        } catch (RequiredRowNotFound $requiredRowNotFound) {
            throw new Exceptions\UnknownStealthinessCode(
                "Given stealthiness is unknown: $itemStealthinessCode"
            );
        }
    }
}