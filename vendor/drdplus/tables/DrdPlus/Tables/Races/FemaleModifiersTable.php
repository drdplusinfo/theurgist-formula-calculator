<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Races;

use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Codes\RaceCode;
use DrdPlus\Tables\Partials\AbstractFileTable;
use DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound;

/**
 * See PPH page 29 left column, @link https://pph.drdplus.info/#tabulka_pohlavi
 */
class FemaleModifiersTable extends AbstractFileTable
{
    /**
     * @return string
     */
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/female_modifiers.csv';
    }

    /**
     * @return array|string
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
            PropertyCode::BODY_WEIGHT => self::INTEGER,
            PropertyCode::SIZE => self::INTEGER,
        ];
    }

    /**
     * @return array
     */
    protected function getRowsHeader(): array
    {
        return [RacesTable::RACE];
    }

    /**
     * @return array|\int[]
     */
    public function getHumanModifiers(): array
    {
        return $this->getRaceModifiers(RaceCode::HUMAN);
    }

    /**
     * @param string $race
     * @return array|int[]
     */
    private function getRaceModifiers(string $race): array
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getRow($race);
    }

    /**
     * @return array|\int[]
     */
    public function getElfModifiers(): array
    {
        return $this->getRaceModifiers(RaceCode::ELF);
    }

    /**
     * @return array|\int[]
     */
    public function getDwarfModifiers(): array
    {
        return $this->getRaceModifiers(RaceCode::DWARF);
    }

    /**
     * @return array|\int[]
     */
    public function getHobbitModifiers(): array
    {
        return $this->getRaceModifiers(RaceCode::HOBBIT);
    }

    /**
     * @return array|\int[]
     */
    public function getKrollModifiers(): array
    {
        return $this->getRaceModifiers(RaceCode::KROLL);
    }

    /**
     * @return array|\int[]
     */
    public function getOrcModifiers(): array
    {
        return $this->getRaceModifiers(RaceCode::ORC);
    }

    /**
     * @param RaceCode $raceCode
     * @return int
     */
    public function getStrength(RaceCode $raceCode): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getProperty($raceCode, PropertyCode::STRENGTH);
    }

    /**
     * @param RaceCode $raceCode
     * @param string $propertyName
     * @return int
     * @throws \DrdPlus\Tables\Races\Exceptions\UnknownRace
     */
    private function getProperty(RaceCode $raceCode, $propertyName): int
    {
        try {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return $this->getValue($raceCode, $propertyName);
        } catch (RequiredRowNotFound $requiredRowNotFound) {
            throw new Exceptions\UnknownRace("Unsupported race $raceCode");
        }
    }

    /**
     * @param RaceCode $raceCode
     * @return int
     */
    public function getAgility(RaceCode $raceCode): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getProperty($raceCode, PropertyCode::AGILITY);
    }

    /**
     * @param RaceCode $raceCode
     * @return int
     */
    public function getKnack(RaceCode $raceCode): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getProperty($raceCode, PropertyCode::KNACK);
    }

    /**
     * @param RaceCode $raceCode
     * @return int
     */
    public function getWill(RaceCode $raceCode): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getProperty($raceCode, PropertyCode::WILL);
    }

    /**
     * @param RaceCode $raceCode
     * @return int
     */
    public function getIntelligence(RaceCode $raceCode): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getProperty($raceCode, PropertyCode::INTELLIGENCE);
    }

    /**
     * @param RaceCode $raceCode
     * @return int
     */
    public function getCharisma(RaceCode $raceCode): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getProperty($raceCode, PropertyCode::CHARISMA);
    }

    /**
     * @param RaceCode $raceCode
     * @return int
     */
    public function getWeightBonus(RaceCode $raceCode): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getProperty($raceCode, PropertyCode::BODY_WEIGHT);
    }

    /**
     * @param RaceCode $raceCode
     * @return int
     */
    public function getSize(RaceCode $raceCode): int
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getProperty($raceCode, PropertyCode::SIZE);
    }
}