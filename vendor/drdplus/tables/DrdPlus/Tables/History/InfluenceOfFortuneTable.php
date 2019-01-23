<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\History;

use DrdPlus\Codes\History\FateCode;
use DrdPlus\Tables\Partials\AbstractFileTable;
use DrdPlus\Tables\Partials\Exceptions\RequiredColumnNotFound;
use Granam\Integer\IntegerInterface;
use Granam\Integer\Tools\ToInteger;
use Granam\Tools\ValueDescriber;

/**
 * See PPH page 30 right column, @link https://pph.drdplus.info/#tabulka_vlivu_nahody
 */
class InfluenceOfFortuneTable extends AbstractFileTable
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/influence_of_fortune.csv';
    }

    public const PRIMARY_PROPERTY_ON_1 = 'primary_property_on_1';
    public const SECONDARY_PROPERTY_ON_1 = 'secondary_property_on_1';
    public const PRIMARY_PROPERTY_ON_2 = 'primary_property_on_2';
    public const SECONDARY_PROPERTY_ON_2 = 'secondary_property_on_2';
    public const PRIMARY_PROPERTY_ON_3 = 'primary_property_on_3';
    public const SECONDARY_PROPERTY_ON_3 = 'secondary_property_on_3';
    public const PRIMARY_PROPERTY_ON_4 = 'primary_property_on_4';
    public const SECONDARY_PROPERTY_ON_4 = 'secondary_property_on_4';
    public const PRIMARY_PROPERTY_ON_5 = 'primary_property_on_5';
    public const SECONDARY_PROPERTY_ON_5 = 'secondary_property_on_5';
    public const PRIMARY_PROPERTY_ON_6 = 'primary_property_on_6';
    public const SECONDARY_PROPERTY_ON_6 = 'secondary_property_on_6';

    /**
     * @return array|string[]
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            self::PRIMARY_PROPERTY_ON_1 => self::POSITIVE_INTEGER,
            self::SECONDARY_PROPERTY_ON_1 => self::POSITIVE_INTEGER,
            self::PRIMARY_PROPERTY_ON_2 => self::POSITIVE_INTEGER,
            self::SECONDARY_PROPERTY_ON_2 => self::POSITIVE_INTEGER,
            self::PRIMARY_PROPERTY_ON_3 => self::POSITIVE_INTEGER,
            self::SECONDARY_PROPERTY_ON_3 => self::POSITIVE_INTEGER,
            self::PRIMARY_PROPERTY_ON_4 => self::POSITIVE_INTEGER,
            self::SECONDARY_PROPERTY_ON_4 => self::POSITIVE_INTEGER,
            self::PRIMARY_PROPERTY_ON_5 => self::POSITIVE_INTEGER,
            self::SECONDARY_PROPERTY_ON_5 => self::POSITIVE_INTEGER,
            self::PRIMARY_PROPERTY_ON_6 => self::POSITIVE_INTEGER,
            self::SECONDARY_PROPERTY_ON_6 => self::POSITIVE_INTEGER,
        ];
    }

    public const FATE = 'fate';

    /**
     * @return array|string[]
     */
    protected function getRowsHeader(): array
    {
        return [self::FATE];
    }

    /**
     * @param FateCode $fateCode
     * @param int|IntegerInterface $diceRoll
     * @return int
     * @throws \DrdPlus\Tables\History\Exceptions\UnexpectedDiceRoll
     */
    public function getPrimaryPropertyOnFate(FateCode $fateCode, $diceRoll): int
    {
        try {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getValue($fateCode, 'primary_property_on_' . ToInteger::toPositiveInteger($diceRoll));
        } catch (RequiredColumnNotFound $requiredColumnNotFound) {
            throw new Exceptions\UnexpectedDiceRoll('Expected roll from 1 to 6, got ' . ValueDescriber::describe($diceRoll));
        }
    }

    /**
     * @param FateCode $fateCode
     * @param int|IntegerInterface $diceRoll
     * @return int
     * @throws \DrdPlus\Tables\History\Exceptions\UnexpectedDiceRoll
     */
    public function getSecondaryPropertyOnFate(FateCode $fateCode, $diceRoll): int
    {
        try {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getValue($fateCode, 'secondary_property_on_' . ToInteger::toPositiveInteger($diceRoll));
        } catch (RequiredColumnNotFound $requiredColumnNotFound) {
            throw new Exceptions\UnexpectedDiceRoll('Expected roll from 1 to 6, got ' . ValueDescriber::describe($diceRoll));
        }
    }

}