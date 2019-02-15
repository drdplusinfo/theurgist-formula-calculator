<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Races;

use DrdPlus\Codes\GenderCode;
use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Codes\RaceCode;
use DrdPlus\Codes\SubRaceCode;
use DrdPlus\Tables\Partials\AbstractFileTable;
use DrdPlus\Tables\Measurements\Weight\Weight;
use DrdPlus\Tables\Measurements\Weight\WeightBonus;
use DrdPlus\Tables\Measurements\Weight\WeightTable;
use Granam\Tools\ValueDescriber;

/**
 * See PPH page 29 top, @link https://pph.drdplus.info/#tabulka_ras
 */
class RacesTable extends AbstractFileTable
{
    /**
     * @var FemaleModifiersTable
     */
    private $femaleModifiersTable;

    public function __construct(FemaleModifiersTable $femaleModifiersTable)
    {
        $this->femaleModifiersTable = $femaleModifiersTable;
    }

    /** @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/races.csv';
    }

    /** @return array|string
     */
    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            PropertyCode::STRENGTH => self::INTEGER,
            PropertyCode::AGILITY => self::INTEGER,
            PropertyCode::KNACK => self::INTEGER,
            PropertyCode::WILL => self::INTEGER,
            PropertyCode::INTELLIGENCE => self::INTEGER,
            PropertyCode::CHARISMA => self::INTEGER,
            PropertyCode::TOUGHNESS => self::INTEGER,
            PropertyCode::HEIGHT_IN_CM => self::FLOAT,
            PropertyCode::BODY_WEIGHT_IN_KG => self::FLOAT,
            PropertyCode::SIZE => self::INTEGER,
            PropertyCode::SENSES => self::INTEGER,
            PropertyCode::REMARKABLE_SENSE => self::STRING,
            PropertyCode::INFRAVISION => self::BOOLEAN,
            PropertyCode::NATIVE_REGENERATION => self::BOOLEAN,
            PropertyCode::REQUIRES_DM_AGREEMENT => self::BOOLEAN,
            PropertyCode::AGE => self::POSITIVE_INTEGER,
        ];
    }

    public const RACE = 'race';
    public const SUBRACE = 'subrace';

    /** @return array
     */
    protected function getRowsHeader(): array
    {
        return [self::RACE, self::SUBRACE];
    }

    /** @return array|\mixed[]
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     */
    public function getCommonHumanModifiers(): array
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getRow([RaceCode::HUMAN, SubRaceCode::COMMON]);
    }

    /** @return array|\mixed[]
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     */
    public function getHighlanderModifiers(): array
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getRow([RaceCode::HUMAN, SubRaceCode::HIGHLANDER]);
    }

    /** @return array|\mixed[]
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     */
    public function getCommonElfModifiers(): array
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getRow([RaceCode::ELF, SubRaceCode::COMMON]);
    }

    /** @return array|\mixed[]
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     */
    public function getDarkElfModifiers(): array
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getRow([RaceCode::ELF, SubRaceCode::DARK]);
    }

    /** @return array|\mixed[]
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     */
    public function getGreenElfModifiers(): array
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getRow([RaceCode::ELF, SubRaceCode::GREEN]);
    }

    /** @return array|\mixed[]
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     */
    public function getCommonDwarfModifiers(): array
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getRow([RaceCode::DWARF, SubRaceCode::COMMON]);
    }

    /** @return array|\mixed[]
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     */
    public function getMountainDwarfModifiers(): array
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getRow([RaceCode::DWARF, SubRaceCode::MOUNTAIN]);
    }

    /** @return array|\mixed[]
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     */
    public function getWoodDwarfModifiers(): array
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getRow([RaceCode::DWARF, SubRaceCode::WOOD]);
    }

    /** @return array|\mixed[]
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     */
    public function getCommonHobbitModifiers(): array
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getRow([RaceCode::HOBBIT, SubRaceCode::COMMON]);
    }

    /** @return array|\mixed[]
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     */
    public function getCommonKrollModifiers(): array
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getRow([RaceCode::KROLL, SubRaceCode::COMMON]);
    }

    /** @return array|\mixed[]
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     */
    public function getWildKrollModifiers(): array
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getRow([RaceCode::KROLL, SubRaceCode::WILD]);
    }

    /** @return array|\mixed[]
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     */
    public function getCommonOrcModifiers(): array
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getRow([RaceCode::ORC, SubRaceCode::COMMON]);
    }

    /** @return array|\mixed[]
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     */
    public function getGoblinModifiers(): array
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getRow([RaceCode::ORC, SubRaceCode::GOBLIN]);
    }

    /** @return array|\mixed[]
     * @throws \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     */
    public function getSkurutModifiers(): array
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getRow([RaceCode::ORC, SubRaceCode::SKURUT]);
    }

    /**
     * @param RaceCode $raceCode
     * @param SubRaceCode $subRaceCode
     * @return int
     * @throws \DrdPlus\Tables\Races\Exceptions\RaceToSubRaceMismatch
     */
    public function getMaleStrength(RaceCode $raceCode, SubRaceCode $subRaceCode): int
    {
        return $this->getProperty($raceCode, $subRaceCode, PropertyCode::STRENGTH);
    }

    /**
     * @param RaceCode $raceCode
     * @param SubRaceCode $subRaceCode
     * @param FemaleModifiersTable $femaleModifiersTable
     * @return int
     * @throws \DrdPlus\Tables\Races\Exceptions\RaceToSubRaceMismatch
     */
    public function getFemaleStrength(RaceCode $raceCode, SubRaceCode $subRaceCode, FemaleModifiersTable $femaleModifiersTable): int
    {
        return $this->getMaleStrength($raceCode, $subRaceCode) + $femaleModifiersTable->getStrength($raceCode);
    }

    /**
     * @param RaceCode $raceCode
     * @param SubRaceCode $subRaceCode
     * @param string $propertyName
     * @return int|float|string|bool
     * @throws \DrdPlus\Tables\Races\Exceptions\RaceToSubRaceMismatch
     */
    private function getProperty(RaceCode $raceCode, SubRaceCode $subRaceCode, string $propertyName)
    {
        if (!$subRaceCode->isRace($raceCode)) {
            throw new Exceptions\RaceToSubRaceMismatch("Given race '{$raceCode}' does not have given sub-race '{$subRaceCode}'");
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getValue([$raceCode, $subRaceCode], $propertyName);
    }

    /**
     * @param RaceCode $raceCode
     * @param SubRaceCode $subRaceCode
     * @return int
     * @throws \DrdPlus\Tables\Races\Exceptions\RaceToSubRaceMismatch
     */
    public function getMaleAgility(RaceCode $raceCode, SubRaceCode $subRaceCode): int
    {
        return $this->getProperty($raceCode, $subRaceCode, PropertyCode::AGILITY);
    }

    /**
     * @param RaceCode $raceCode
     * @param SubRaceCode $subRaceCode
     * @param FemaleModifiersTable $femaleModifiersTable
     * @return int
     * @throws \DrdPlus\Tables\Races\Exceptions\RaceToSubRaceMismatch
     */
    public function getFemaleAgility(RaceCode $raceCode, SubRaceCode $subRaceCode, FemaleModifiersTable $femaleModifiersTable): int
    {
        return $this->getMaleAgility($raceCode, $subRaceCode) + $femaleModifiersTable->getAgility($raceCode);
    }

    /**
     * @param RaceCode $raceCode
     * @param SubRaceCode $subRaceCode
     * @return int
     * @throws \DrdPlus\Tables\Races\Exceptions\RaceToSubRaceMismatch
     */
    public function getMaleKnack(RaceCode $raceCode, SubRaceCode $subRaceCode): int
    {
        return $this->getProperty($raceCode, $subRaceCode, PropertyCode::KNACK);
    }

    /**
     * @param RaceCode $raceCode
     * @param SubRaceCode $subRaceCode
     * @return int
     * @throws \DrdPlus\Tables\Races\Exceptions\RaceToSubRaceMismatch
     */
    public function getFemaleKnack(RaceCode $raceCode, SubRaceCode $subRaceCode): int
    {
        return $this->getMaleKnack($raceCode, $subRaceCode) + $this->femaleModifiersTable->getKnack($raceCode);
    }

    /**
     * @param RaceCode $raceCode
     * @param SubRaceCode $subRaceCode
     * @param GenderCode $genderCode
     * @return bool|float|int|string
     * @throws \DrdPlus\Tables\Races\Exceptions\RaceToSubRaceMismatch
     */
    public function getWill(RaceCode $raceCode, SubRaceCode $subRaceCode, GenderCode $genderCode)
    {
        $maleWill = $this->getProperty($raceCode, $subRaceCode, PropertyCode::WILL);
        if ($genderCode->isMale()) {
            return $maleWill;
        }

        return $maleWill + $this->femaleModifiersTable->getWill($raceCode);
    }

    /**
     * @param RaceCode $raceCode
     * @param SubRaceCode $subRaceCode
     * @return int
     * @throws \DrdPlus\Tables\Races\Exceptions\RaceToSubRaceMismatch
     */
    public function getMaleWill(RaceCode $raceCode, SubRaceCode $subRaceCode): int
    {
        return $this->getWill($raceCode, $subRaceCode, GenderCode::getIt(GenderCode::MALE));
    }

    /**
     * @param RaceCode $raceCode
     * @param SubRaceCode $subRaceCode
     * @return int
     * @throws \DrdPlus\Tables\Races\Exceptions\RaceToSubRaceMismatch
     */
    public function getFemaleWill(RaceCode $raceCode, SubRaceCode $subRaceCode): int
    {
        return $this->getWill($raceCode, $subRaceCode, GenderCode::getIt(GenderCode::FEMALE));
    }

    /**
     * @param RaceCode $raceCode
     * @param SubRaceCode $subRaceCode
     * @return int
     * @throws \DrdPlus\Tables\Races\Exceptions\RaceToSubRaceMismatch
     */
    public function getMaleIntelligence(RaceCode $raceCode, SubRaceCode $subRaceCode): int
    {
        return $this->getProperty($raceCode, $subRaceCode, PropertyCode::INTELLIGENCE);
    }

    /**
     * @param RaceCode $raceCode
     * @param SubRaceCode $subRaceCode
     * @param FemaleModifiersTable $femaleModifiersTable
     * @return int
     * @throws \DrdPlus\Tables\Races\Exceptions\RaceToSubRaceMismatch
     */
    public function getFemaleIntelligence(RaceCode $raceCode, SubRaceCode $subRaceCode, FemaleModifiersTable $femaleModifiersTable): int
    {
        return $this->getMaleIntelligence($raceCode, $subRaceCode) + $femaleModifiersTable->getIntelligence($raceCode);
    }

    /**
     * @param RaceCode $raceCode
     * @param SubRaceCode $subRaceCode
     * @return int
     * @throws \DrdPlus\Tables\Races\Exceptions\RaceToSubRaceMismatch
     */
    public function getMaleCharisma(RaceCode $raceCode, SubRaceCode $subRaceCode): int
    {
        return $this->getProperty($raceCode, $subRaceCode, PropertyCode::CHARISMA);
    }

    /**
     * @param RaceCode $raceCode
     * @param SubRaceCode $subRaceCode
     * @param FemaleModifiersTable $femaleModifiersTable
     * @return int
     * @throws \DrdPlus\Tables\Races\Exceptions\RaceToSubRaceMismatch
     */
    public function getFemaleCharisma(RaceCode $raceCode, SubRaceCode $subRaceCode, FemaleModifiersTable $femaleModifiersTable): int
    {
        return $this->getMaleCharisma($raceCode, $subRaceCode) + $femaleModifiersTable->getCharisma($raceCode);
    }

    /**
     * @param RaceCode $raceCode
     * @param SubRaceCode $subRaceCode
     * @return int
     * @throws \DrdPlus\Tables\Races\Exceptions\RaceToSubRaceMismatch
     */
    public function getToughness(RaceCode $raceCode, SubRaceCode $subRaceCode): int
    {
        return $this->getProperty($raceCode, $subRaceCode, PropertyCode::TOUGHNESS);
    }

    /**
     * @param RaceCode $raceCode
     * @param SubRaceCode $subRaceCode
     * @return float
     * @throws \DrdPlus\Tables\Races\Exceptions\RaceToSubRaceMismatch
     */
    public function getHeightInCm(RaceCode $raceCode, SubRaceCode $subRaceCode): float
    {
        return $this->getProperty($raceCode, $subRaceCode, PropertyCode::HEIGHT_IN_CM);
    }

    /**
     * @param RaceCode $raceCode
     * @param SubRaceCode $subRaceCode
     * @param GenderCode $genderCode
     * @param FemaleModifiersTable $femaleModifiersTable
     * @param WeightTable $weightTable
     * @return float
     * @throws \DrdPlus\Tables\Races\Exceptions\UnknownGender
     * @throws \DrdPlus\Tables\Races\Exceptions\RaceToSubRaceMismatch
     */
    public function getWeightInKg(
        RaceCode $raceCode,
        SubRaceCode $subRaceCode,
        GenderCode $genderCode,
        FemaleModifiersTable $femaleModifiersTable,
        WeightTable $weightTable
    ): float
    {
        switch ($genderCode) {
            case GenderCode::MALE :
                return $this->getMaleWeightInKg($raceCode, $subRaceCode);
            case GenderCode::FEMALE :
                return $this->getFemaleWeightInKg($raceCode, $subRaceCode, $femaleModifiersTable, $weightTable);
            default :
                throw new Exceptions\UnknownGender(
                    'Unknown gender ' . ValueDescriber::describe($genderCode)
                );
        }
    }

    /**
     * @param RaceCode $raceCode
     * @param SubRaceCode $subRaceCode
     * @return float
     * @throws \DrdPlus\Tables\Races\Exceptions\RaceToSubRaceMismatch
     */
    public function getMaleWeightInKg(RaceCode $raceCode, SubRaceCode $subRaceCode): float
    {
        return $this->getProperty($raceCode, $subRaceCode, PropertyCode::BODY_WEIGHT_IN_KG);
    }

    /**
     * @param RaceCode $raceCode
     * @param SubRaceCode $subRaceCode
     * @param FemaleModifiersTable $femaleModifiersTable
     * @param WeightTable $weightTable
     * @return float
     * @throws \DrdPlus\Tables\Races\Exceptions\RaceToSubRaceMismatch
     */
    public function getFemaleWeightInKg(
        $raceCode,
        $subRaceCode,
        FemaleModifiersTable $femaleModifiersTable,
        WeightTable $weightTable
    ): float
    {
        $maleWeightValue = $this->getMaleWeightInKg($raceCode, $subRaceCode);
        $maleWeightBonus = $weightTable->toBonus(new Weight($maleWeightValue, Weight::KG, $weightTable));
        $femaleWeightBonusModifier = $femaleModifiersTable->getWeightBonus($raceCode);
        $femaleWeightBonusValue = $maleWeightBonus->getValue() + $femaleWeightBonusModifier;
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $femaleWeightBonus = new WeightBonus($femaleWeightBonusValue, $weightTable);

        return $femaleWeightBonus->getWeight()->getValue();
    }

    /**
     * @param RaceCode $raceCode
     * @param SubRaceCode $subRaceCode
     * @return int
     * @throws \DrdPlus\Tables\Races\Exceptions\RaceToSubRaceMismatch
     */
    public function getMaleSize(RaceCode $raceCode, SubRaceCode $subRaceCode): int
    {
        return $this->getProperty($raceCode, $subRaceCode, PropertyCode::SIZE);
    }

    /**
     * @param RaceCode $raceCode
     * @param SubRaceCode $subRaceCode
     * @param FemaleModifiersTable $femaleModifiersTable
     * @return int
     * @throws \DrdPlus\Tables\Races\Exceptions\RaceToSubRaceMismatch
     */
    public function getFemaleSize(RaceCode $raceCode, SubRaceCode $subRaceCode, FemaleModifiersTable $femaleModifiersTable): int
    {
        return $this->getMaleSize($raceCode, $subRaceCode) + $femaleModifiersTable->getSize($raceCode);
    }

    /**
     * @param RaceCode $raceCode
     * @param SubRaceCode $subRaceCode
     * @param GenderCode $genderCode
     * @param FemaleModifiersTable $femaleModifiersTable
     * @return int
     * @throws \DrdPlus\Tables\Races\Exceptions\UnknownGender
     * @throws \DrdPlus\Tables\Races\Exceptions\RaceToSubRaceMismatch
     */
    public function getSize(RaceCode $raceCode, SubRaceCode $subRaceCode, GenderCode $genderCode, FemaleModifiersTable $femaleModifiersTable): int
    {
        switch ($genderCode) {
            case GenderCode::MALE :
                return $this->getMaleSize($raceCode, $subRaceCode);
            case GenderCode::FEMALE :
                return $this->getFemaleSize($raceCode, $subRaceCode, $femaleModifiersTable);
            default :
                throw new Exceptions\UnknownGender(
                    'Expected one of ' . GenderCode::MALE . ' or ' . GenderCode::FEMALE
                    . ', got ' . ValueDescriber::describe($genderCode)
                );
        }
    }

    /**
     * @param RaceCode $raceCode
     * @param SubRaceCode $subRaceCode
     * @return string
     * @throws \DrdPlus\Tables\Races\Exceptions\RaceToSubRaceMismatch
     */
    public function getRemarkableSense(RaceCode $raceCode, SubRaceCode $subRaceCode): string
    {
        return $this->getProperty($raceCode, $subRaceCode, PropertyCode::REMARKABLE_SENSE);
    }

    /**
     * @param RaceCode $raceCode
     * @param SubRaceCode $subRaceCode
     * @return bool
     * @throws \DrdPlus\Tables\Races\Exceptions\RaceToSubRaceMismatch
     */
    public function hasInfravision(RaceCode $raceCode, SubRaceCode $subRaceCode): bool
    {
        return $this->getProperty($raceCode, $subRaceCode, PropertyCode::INFRAVISION);
    }

    /**
     * @param RaceCode $raceCode
     * @param SubRaceCode $subRaceCode
     * @return bool
     * @throws \DrdPlus\Tables\Races\Exceptions\RaceToSubRaceMismatch
     */
    public function hasNativeRegeneration(RaceCode $raceCode, SubRaceCode $subRaceCode): bool
    {
        return $this->getProperty($raceCode, $subRaceCode, PropertyCode::NATIVE_REGENERATION);
    }

    /**
     * @param RaceCode $raceCode
     * @param SubRaceCode $subRaceCode
     * @return int
     * @throws \DrdPlus\Tables\Races\Exceptions\RaceToSubRaceMismatch
     */
    public function getSenses(RaceCode $raceCode, SubRaceCode $subRaceCode): int
    {
        return $this->getProperty($raceCode, $subRaceCode, PropertyCode::SENSES);
    }

    /**
     * @param RaceCode $raceCode
     * @param SubRaceCode $subRaceCode
     * @return bool
     * @throws \DrdPlus\Tables\Races\Exceptions\RaceToSubRaceMismatch
     */
    public function requiresDmAgreement(RaceCode $raceCode, SubRaceCode $subRaceCode): bool
    {
        return $this->getProperty($raceCode, $subRaceCode, PropertyCode::REQUIRES_DM_AGREEMENT);
    }

    /**
     * Gives usual age of a race on his first great adventure - like 15 years for common human or 25 for hobbit.
     *
     * @param RaceCode $raceCode
     * @param SubRaceCode $subRaceCode
     * @return int
     * @throws \DrdPlus\Tables\Races\Exceptions\RaceToSubRaceMismatch
     */
    public function getAge(RaceCode $raceCode, SubRaceCode $subRaceCode): int
    {
        return $this->getProperty($raceCode, $subRaceCode, PropertyCode::AGE);
    }
}