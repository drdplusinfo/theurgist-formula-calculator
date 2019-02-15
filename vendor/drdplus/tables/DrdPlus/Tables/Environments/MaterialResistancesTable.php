<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Environments;

use DrdPlus\Codes\Environment\MaterialCode;
use DrdPlus\Tables\Partials\AbstractFileTable;
use DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound;
use Granam\DiceRolls\Templates\Rolls\Roll2d6DrdPlus;
use Granam\Integer\IntegerInterface;

/**
 * See PPH page 133 right column, @link https://pph.drdplus.info/#tabulka_odolnosti_materialu
 */
class MaterialResistancesTable extends AbstractFileTable
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/material_resistances.csv';
    }

    public const RESISTANCE = 'resistance';

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            self::RESISTANCE => self::POSITIVE_INTEGER,
        ];
    }

    public const MATERIAL = 'material';

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [self::MATERIAL];
    }

    /**
     * @param MaterialCode $materialCode
     * @return int
     * @throws \DrdPlus\Tables\Environments\Exceptions\UnknownMaterialToGetResistanceFor
     */
    public function getResistanceOfMaterial(MaterialCode $materialCode): int
    {
        try {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getValue($materialCode, self::RESISTANCE);
        } catch (RequiredRowNotFound $requiredRowNotFound) {
            throw new Exceptions\UnknownMaterialToGetResistanceFor(
                "Given '{$materialCode}' material is unknown"
            );
        }
    }

    /**
     * @param MaterialCode $materialCode
     * @param IntegerInterface $powerOfDestruction
     * @param Roll2d6DrdPlus $roll2D6DrdPlus
     * @return bool
     * @throws \DrdPlus\Tables\Environments\Exceptions\UnknownMaterialToGetResistanceFor
     */
    public function hasItBeenDamaged(
        MaterialCode $materialCode,
        IntegerInterface $powerOfDestruction,
        Roll2d6DrdPlus $roll2D6DrdPlus
    ): bool
    {
        return $this->getResistanceOfMaterial($materialCode)
            < ($powerOfDestruction->getValue() + $roll2D6DrdPlus->getValue());
    }

}