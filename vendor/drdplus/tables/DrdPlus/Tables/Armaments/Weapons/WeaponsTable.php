<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Armaments\Weapons;

use DrdPlus\Codes\Armaments\WeaponCategoryCode;
use DrdPlus\Codes\Armaments\WeaponCode;
use DrdPlus\Tables\Armaments\Partials\AbstractArmamentsTable;
use DrdPlus\Tables\Armaments\Partials\WeaponlikeTable;
use Granam\String\StringTools;

abstract class WeaponsTable extends AbstractArmamentsTable implements WeaponlikeTable
{
    private $customWeapons = [];

    /**
     * @param WeaponCode $weaponCode
     * @param WeaponCategoryCode $weaponCategoryCode
     * @param array $newWeaponParameters
     * @return bool
     * @throws \DrdPlus\Tables\Armaments\Weapons\Exceptions\NewWeaponIsNotOfRequiredType
     * @throws \DrdPlus\Tables\Armaments\Weapons\Exceptions\DifferentWeaponIsUnderSameName
     */
    protected function addNewCustomWeapon(
        WeaponCode $weaponCode,
        WeaponCategoryCode $weaponCategoryCode,
        array $newWeaponParameters
    ): bool
    {
        /** like @see RangedWeaponCode::isBow() */
        $isType = StringTools::assembleIsForName(
            \rtrim(
                \str_replace(
                    ['knives', 's_and_', '_and_'], ['knife', '_or_', '_or_'],
                    $weaponCategoryCode->getValue()
                ),
                's'
            )
        );
        /** like @see RangedWeaponCode::getBowValues() */
        $getTypeCodes = StringTools::assembleGetterForName($weaponCategoryCode->getValue()) . 'Values';
        if (!\method_exists($weaponCode, $isType) || !$weaponCode->$isType()
            || !\method_exists($weaponCode, $getTypeCodes)
            || !\in_array($weaponCode->getValue(), $weaponCode::$getTypeCodes(), true)
        ) {
            throw new Exceptions\NewWeaponIsNotOfRequiredType(
                "Expected new weapon to be '$weaponCategoryCode' type, got {$weaponCode}"
                . ' with values ' . \implode(',', $weaponCode::getPossibleValues())
            );
        }
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $previousParameters = $this->findRow($weaponCode);
        if ($previousParameters) {
            if ($newWeaponParameters === $previousParameters) {
                return false;
            }
            throw new Exceptions\DifferentWeaponIsUnderSameName(
                "New weapon {$weaponCode} can not be added as there is already a weapon under same name"
                . ' but with different parameters: '
                . \var_export(\array_diff_assoc($previousParameters, $newWeaponParameters), true)
            );
        }
        $this->customWeapons[static::class][$weaponCode->getValue()] = $newWeaponParameters;

        return true;
    }

    public function getIndexedValues(): array
    {
        $indexedValues = parent::getIndexedValues();

        return \array_merge($indexedValues, $this->customWeapons[static::class] ?? []);
    }

}