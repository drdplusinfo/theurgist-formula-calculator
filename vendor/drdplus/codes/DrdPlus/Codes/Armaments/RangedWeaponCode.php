<?php
declare(strict_types=1);

namespace DrdPlus\Codes\Armaments;

/**
 * @method static RangedWeaponCode getIt($codeValue)
 * @method static RangedWeaponCode findIt($codeValue)
 */
class RangedWeaponCode extends WeaponCode
{
    private static $customRangedWeaponCodePerCategory = [];

    // bows
    public const SHORT_BOW = 'short_bow';
    public const LONG_BOW = 'long_bow';
    public const SHORT_COMPOSITE_BOW = 'short_composite_bow';
    public const LONG_COMPOSITE_BOW = 'long_composite_bow';
    public const POWER_BOW = 'power_bow';

    /**
     * @param bool $withCustomValues = true
     * @return array|string[]
     */
    public static function getBowsValues(bool $withCustomValues = true): array
    {
        $defaultValues = [
            self::SHORT_BOW,
            self::LONG_BOW,
            self::SHORT_COMPOSITE_BOW,
            self::LONG_COMPOSITE_BOW,
            self::POWER_BOW,
        ];
        if (!$withCustomValues) {
            return $defaultValues;
        }

        return \array_merge($defaultValues, static::$customRangedWeaponCodePerCategory[WeaponCategoryCode::BOWS] ?? []);
    }

    // crossbows
    public const MINICROSSBOW = 'minicrossbow';
    public const LIGHT_CROSSBOW = 'light_crossbow';
    public const MILITARY_CROSSBOW = 'military_crossbow';
    public const HEAVY_CROSSBOW = 'heavy_crossbow';

    /**
     * @param bool $withCustomValues = true
     * @return array|string[]
     */
    public static function getCrossbowsValues(bool $withCustomValues = true): array
    {
        $defaultValues = [
            self::MINICROSSBOW,
            self::LIGHT_CROSSBOW,
            self::MILITARY_CROSSBOW,
            self::HEAVY_CROSSBOW,
        ];
        if (!$withCustomValues) {
            return $defaultValues;
        }

        return \array_merge($defaultValues, static::$customRangedWeaponCodePerCategory[WeaponCategoryCode::CROSSBOWS] ?? []);
    }

    // throwing weapons
    public const SAND = 'sand';
    public const ROCK = 'rock';
    public const THROWING_DAGGER = 'throwing_dagger';
    public const LIGHT_THROWING_AXE = 'light_throwing_axe';
    public const WAR_THROWING_AXE = 'war_throwing_axe';
    public const THROWING_HAMMER = 'throwing_hammer';
    public const SHURIKEN = 'shuriken';
    public const SPEAR = 'spear';
    public const JAVELIN = 'javelin';
    public const SLING = 'sling';

    /**
     * @param bool $withCustomValues = true
     * @return array|string[]
     */
    public static function getThrowingWeaponsValues(bool $withCustomValues = true): array
    {
        $defaultValues = [
            self::SAND,
            self::ROCK,
            self::THROWING_DAGGER,
            self::LIGHT_THROWING_AXE,
            self::WAR_THROWING_AXE,
            self::THROWING_HAMMER,
            self::SHURIKEN,
            self::SPEAR,
            self::JAVELIN,
            self::SLING,
        ];
        if (!$withCustomValues) {
            return $defaultValues;
        }

        return \array_merge($defaultValues, static::$customRangedWeaponCodePerCategory[WeaponCategoryCode::THROWING_WEAPONS] ?? []);
    }

    /**
     * @return array|string[]
     */
    protected static function getDefaultValues(): array
    {
        return \array_values( // to get continual integer keys
            \array_merge(
                self::getThrowingWeaponsValues(false /* without custom */),
                self::getBowsValues(false /* without custom */),
                self::getCrossbowsValues(false /* without custom */)
            )
        );
    }

    /**
     * @param string $newRangedWeaponCodeValue
     * @param WeaponCategoryCode $rangedWeaponCategoryCode
     * @param array $translations
     * @return bool
     * @throws \DrdPlus\Codes\Armaments\Exceptions\InvalidWeaponCategoryForNewRangedWeaponCode
     * @throws \DrdPlus\Codes\Armaments\Exceptions\RangedWeaponIsAlreadyInDifferentWeaponCategory
     * @throws \DrdPlus\Codes\Partials\Exceptions\InvalidLanguageCode
     * @throws \DrdPlus\Codes\Partials\Exceptions\UnknownTranslationPlural
     * @throws \DrdPlus\Codes\Partials\Exceptions\InvalidTranslationFormat
     */
    public static function addNewRangedWeaponCode(
        string $newRangedWeaponCodeValue,
        WeaponCategoryCode $rangedWeaponCategoryCode,
        array $translations
    ): bool
    {
        if (!$rangedWeaponCategoryCode->isRangedWeaponCategory()) {
            throw new Exceptions\InvalidWeaponCategoryForNewRangedWeaponCode(
                'Expected one of ranged weapon categories, got ' . $rangedWeaponCategoryCode
            );
        }

        $extended = static::addNewCode($newRangedWeaponCodeValue, $translations);
        if (!$extended) {
            self::guardSameCategory($newRangedWeaponCodeValue, $rangedWeaponCategoryCode);

            return false;
        }
        self::$customRangedWeaponCodePerCategory[$rangedWeaponCategoryCode->getValue()][] = $newRangedWeaponCodeValue;

        return true;
    }

    /**
     * @param string $meleeWeaponValue
     * @param WeaponCategoryCode $weaponCategoryCode
     * @throws \DrdPlus\Codes\Armaments\Exceptions\RangedWeaponIsAlreadyInDifferentWeaponCategory
     */
    private static function guardSameCategory(string $meleeWeaponValue, WeaponCategoryCode $weaponCategoryCode): void
    {
        if (!\in_array($meleeWeaponValue, self::$customRangedWeaponCodePerCategory[$weaponCategoryCode->getValue()] ?? [], true)) {
            $alreadyUsedCategory = null;
            foreach (WeaponCategoryCode::getPossibleValues() as $anotherCategory) {
                if ($anotherCategory !== $weaponCategoryCode->getValue()
                    && \in_array($meleeWeaponValue, self::$customRangedWeaponCodePerCategory[$anotherCategory] ?? [], true)
                ) {
                    $alreadyUsedCategory = $anotherCategory;
                }
            }
            throw new Exceptions\RangedWeaponIsAlreadyInDifferentWeaponCategory(
                "Can not register new ranged weapon '$meleeWeaponValue' into category '$weaponCategoryCode'"
                . " because is already registered in category '$alreadyUsedCategory'"
            );
        }
    }

    public function isMelee(): bool
    {
        return false;
    }

    final public function isRanged(): bool
    {
        return true;
    }

    public function isBow(): bool
    {
        return \in_array($this->getValue(), self::getBowsValues(), true);
    }

    public function isCrossbow(): bool
    {
        return \in_array($this->getValue(), self::getCrossbowsValues(), true);
    }

    public function isThrowingWeapon(): bool
    {
        return \in_array($this->getValue(), self::getThrowingWeaponsValues(), true);
    }

    public function isShootingWeapon(): bool
    {
        return $this->isBow() || $this->isCrossbow();
    }

    /**
     * @return MeleeWeaponCode
     * @throws \DrdPlus\Codes\Armaments\Exceptions\CanNotBeConvertedToMeleeWeaponCode
     */
    public function convertToMeleeWeaponCodeEquivalent(): MeleeWeaponCode
    {
        if ($this->getValue() !== self::SPEAR) {
            throw new Exceptions\CanNotBeConvertedToMeleeWeaponCode(
                "Range weapon code {$this} can not be converted to melee weapon code"
            );
        }

        return MeleeWeaponCode::getIt($this->getValue());
    }

    public function convertToRangedWeaponCodeEquivalent(): RangedWeaponCode
    {
        return $this;
    }

    /**
     * There is currently no ranged weapon which left your hand empty.
     *
     * @return bool
     */
    public function isUnarmed(): bool
    {
        return false;
    }

    /**
     * @return array|string[]
     */
    protected function fetchTranslations(): array
    {
        return [
            'en' => [
                self::SHORT_BOW => [self::$ONE => 'short bow', self::$FEW => 'short bows', self::$MANY => 'short bows'],
                self::LONG_BOW => [self::$ONE => 'long bow', self::$FEW => 'long bows', self::$MANY => 'long bows'],
                self::SHORT_COMPOSITE_BOW => [self::$ONE => 'short composite bow', self::$FEW => 'short composite bows', self::$MANY => 'short composite bows'],
                self::LONG_COMPOSITE_BOW => [self::$ONE => 'long composite bow', self::$FEW => 'long composite bows', self::$MANY => 'long composite bows'],
                self::POWER_BOW => [self::$ONE => 'power bow', self::$FEW => 'power bows', self::$MANY => 'power bows'],
                self::MINICROSSBOW => [self::$ONE => 'minicrossbow', self::$FEW => 'minicrossbows', self::$MANY => 'minicrossbows'],
                self::LIGHT_CROSSBOW => [self::$ONE => 'light crossbow', self::$FEW => 'light crossbows', self::$MANY => 'light crossbows'],
                self::MILITARY_CROSSBOW => [self::$ONE => 'military crossbow', self::$FEW => 'military crossbows', self::$MANY => 'military crossbows'],
                self::HEAVY_CROSSBOW => [self::$ONE => 'heavy crossbow', self::$FEW => 'heavy crossbows', self::$MANY => 'heavy crossbows'],
                self::SAND => [self::$ONE => 'sand', self::$FEW => 'sands', self::$MANY => 'sands'],
                self::ROCK => [self::$ONE => 'rock', self::$FEW => 'rocks', self::$MANY => 'rocks'],
                self::THROWING_DAGGER => [self::$ONE => 'throwing dagger', self::$FEW => 'throwing daggers', self::$MANY => 'throwing daggers'],
                self::LIGHT_THROWING_AXE => [self::$ONE => 'light throwing axe', self::$FEW => 'light throwing axes', self::$MANY => 'light throwing axes'],
                self::WAR_THROWING_AXE => [self::$ONE => 'war throwing axe', self::$FEW => 'war throwing axes', self::$MANY => 'war throwing axes'],
                self::THROWING_HAMMER => [self::$ONE => 'throwing hammer', self::$FEW => 'throwing hammers', self::$MANY => 'throwing hammers'],
                self::SHURIKEN => [self::$ONE => 'shuriken', self::$FEW => 'shurikens', self::$MANY => 'shurikens'],
                self::SPEAR => [self::$ONE => 'spear', self::$FEW => 'spears', self::$MANY => 'spears'],
                self::JAVELIN => [self::$ONE => 'javelin', self::$FEW => 'javelins', self::$MANY => 'javelins'],
                self::SLING => [self::$ONE => 'sling', self::$FEW => 'slings', self::$MANY => 'slings'],
            ],
            'cs' => [
                self::SHORT_BOW => [self::$ONE => 'krátký luk', self::$FEW => 'krátké luky', self::$MANY => 'krátkých luků'],
                self::LONG_BOW => [self::$ONE => 'dlouhý luk', self::$FEW => 'dlouhé luky', self::$MANY => 'dlouhých luků'],
                self::SHORT_COMPOSITE_BOW => [self::$ONE => 'krátký skládaný luk', self::$FEW => 'krátké skládané luky', self::$MANY => 'krátkých skládaných luků'],
                self::LONG_COMPOSITE_BOW => [self::$ONE => 'dlouhý skládaný luk', self::$FEW => 'dlouhé skládané luky', self::$MANY => 'dlouhých skládaných luků'],
                self::POWER_BOW => [self::$ONE => 'silový luk', self::$FEW => 'silové luky', self::$MANY => 'silových luků'],
                self::MINICROSSBOW => [self::$ONE => 'minikuše', self::$FEW => 'minikuše', self::$MANY => 'minikuší'],
                self::LIGHT_CROSSBOW => [self::$ONE => 'lehká kuše', self::$FEW => 'lehké kuše', self::$MANY => 'lehkých kuší'],
                self::MILITARY_CROSSBOW => [self::$ONE => 'válečná kuše', self::$FEW => 'válečné kuše', self::$MANY => 'válečných kuší'],
                self::HEAVY_CROSSBOW => [self::$ONE => 'těžká kuše', self::$FEW => 'těžké kuše', self::$MANY => 'těžkých kuší'],
                self::SAND => [self::$ONE => 'písek', self::$FEW => 'písky', self::$MANY => 'písků'],
                self::ROCK => [self::$ONE => 'kámen', self::$FEW => 'kameny', self::$MANY => 'kamenů'],
                self::THROWING_DAGGER => [self::$ONE => 'vrhací dýka', self::$FEW => 'vrhací dýky', self::$MANY => 'vrhacích dýk'],
                self::LIGHT_THROWING_AXE => [self::$ONE => 'lehká vrhací sekera', self::$FEW => 'lehké vrhací sekery', self::$MANY => 'lehkých vrhacích seker'],
                self::WAR_THROWING_AXE => [self::$ONE => 'válečná vrhací sekera', self::$FEW => 'válečné vrhací sekery', self::$MANY => 'válečných vrhacích seker'],
                self::THROWING_HAMMER => [self::$ONE => 'vrhací kladivo', self::$FEW => 'vrhací kladiva', self::$MANY => 'vrhacích kladiv'],
                self::SHURIKEN => [self::$ONE => 'hvězdice', self::$FEW => 'hvězdice', self::$MANY => 'hvězdic'],
                self::SPEAR => [self::$ONE => 'kopí', self::$FEW => 'kopí', self::$MANY => 'kopí'],
                self::JAVELIN => [self::$ONE => 'oštěp', self::$FEW => 'oštěpy', self::$MANY => 'oštěpů'],
                self::SLING => [self::$ONE => 'prak', self::$FEW => 'praky', self::$MANY => 'praků'],
            ],
        ];
    }
}