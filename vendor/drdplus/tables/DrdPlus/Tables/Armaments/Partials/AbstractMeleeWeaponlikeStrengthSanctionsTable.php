<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Armaments\Partials;

use DrdPlus\Tables\Armaments\Weapons\Exceptions\CanNotUseWeaponBecauseOfMissingStrength;

abstract class AbstractMeleeWeaponlikeStrengthSanctionsTable extends AbstractStrengthSanctionsTable
    implements WeaponStrengthSanctionsInterface
{
    protected function getDataFileName(): string
    {
        return __DIR__ . '/data/melee_weaponlike_strength_sanctions.csv';
    }

    public const FIGHT_NUMBER = 'fight_number';
    public const ATTACK_NUMBER = 'attack_number';
    public const DEFENSE_NUMBER = 'defense_number';
    public const BASE_OF_WOUNDS = 'base_of_wounds';
    public const CAN_USE_ARMAMENT = 'can_use_armament';

    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            self::MISSING_STRENGTH => self::POSITIVE_INTEGER,
            self::FIGHT_NUMBER => self::NEGATIVE_INTEGER,
            self::ATTACK_NUMBER => self::NEGATIVE_INTEGER,
            self::DEFENSE_NUMBER => self::NEGATIVE_INTEGER,
            self::BASE_OF_WOUNDS => self::NEGATIVE_INTEGER,
            self::CAN_USE_ARMAMENT => self::BOOLEAN,
        ];
    }

    /**
     * @param int $missingStrength
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Weapons\Exceptions\CanNotUseWeaponBecauseOfMissingStrength
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     */
    public function getFightNumberSanction(int $missingStrength): int
    {
        return $this->getSanctionOf($missingStrength, self::FIGHT_NUMBER);
    }

    /**
     * @param int $missingStrength
     * @param string $columnName
     * @param bool $guardMaximumMissingStrength
     * @return int|bool
     * @throws \DrdPlus\Tables\Armaments\Weapons\Exceptions\CanNotUseWeaponBecauseOfMissingStrength
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     */
    private function getSanctionOf(int $missingStrength, string $columnName, bool $guardMaximumMissingStrength = true)
    {
        if ($guardMaximumMissingStrength && !$this->canUseIt($missingStrength)) {
            throw new CanNotUseWeaponBecauseOfMissingStrength(
                "Too much missing strength {$missingStrength} to use a melee weapon"
            );
        }

        return $this->getSanctionsForMissingStrength($missingStrength)[$columnName];
    }

    /**
     * @param int $missingStrength
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Weapons\Exceptions\CanNotUseWeaponBecauseOfMissingStrength
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     */
    public function getAttackNumberSanction(int $missingStrength): int
    {
        return $this->getSanctionOf($missingStrength, self::ATTACK_NUMBER);
    }

    /**
     * @param int $missingStrength
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Weapons\Exceptions\CanNotUseWeaponBecauseOfMissingStrength
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     */
    public function getDefenseNumberSanction(int $missingStrength): int
    {
        return $this->getSanctionOf($missingStrength, self::DEFENSE_NUMBER);
    }

    /**
     * @param int $missingStrength
     * @return int
     * @throws \DrdPlus\Tables\Armaments\Weapons\Exceptions\CanNotUseWeaponBecauseOfMissingStrength
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     */
    public function getBaseOfWoundsSanction(int $missingStrength): int
    {
        return $this->getSanctionOf($missingStrength, self::BASE_OF_WOUNDS);
    }

    /**
     * @param int $missingStrength
     * @return bool
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     */
    public function canUseIt(int $missingStrength): bool
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $this->getSanctionOf(
            $missingStrength,
            self::CAN_USE_ARMAMENT,
            false /* do not check missing strength before value determination */
        );
    }
}