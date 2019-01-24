<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Armaments\Partials;

interface WeaponStrengthSanctionsInterface extends StrengthSanctionsInterface
{
    /**
     * @param int $missingStrength
     * @return int
     */
    public function getAttackNumberSanction(int $missingStrength): int;

    /**
     * @param int $missingStrength
     * @return int
     */
    public function getBaseOfWoundsSanction(int $missingStrength): int;

    /**
     * @param int $missingStrength
     * @return int
     */
    public function getFightNumberSanction(int $missingStrength): int;

    /**
     * @param $missingStrength
     * @return int
     */
    public function getDefenseNumberSanction(int $missingStrength): int;
}