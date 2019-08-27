<?php declare(strict_types=1);

namespace DrdPlus\Codes\Armaments;

/**
 * @method static MeleeWeaponCode getIt($codeValue)
 * @method static MeleeWeaponCode findIt($codeValue)
 */
class MeleeWeaponCode extends WeaponCode implements MeleeWeaponlikeCode
{

    private static $customMeleeWeaponCodePerCategory = [];

    // axes
    public const LIGHT_AXE = 'light_axe';
    public const AXE = 'axe';
    public const WAR_AXE = 'war_axe';
    public const TWO_HANDED_AXE = 'two_handed_axe';

    /**
     * @param bool $withCustomValues = true
     * @return array|string[]
     */
    public static function getAxesValues(bool $withCustomValues = true): array
    {
        $defaultValues = [self::LIGHT_AXE, self::AXE, self::WAR_AXE, self::TWO_HANDED_AXE];
        if (!$withCustomValues) {
            return $defaultValues;
        }

        return array_merge(
            $defaultValues,
            self::$customMeleeWeaponCodePerCategory[WeaponCategoryCode::AXES] ?? []
        );
    }

    // knives and daggers
    public const KNIFE = 'knife';
    public const DAGGER = 'dagger';
    public const STABBING_DAGGER = 'stabbing_dagger';
    public const LONG_KNIFE = 'long_knife';
    public const LONG_DAGGER = 'long_dagger';

    /**
     * @param bool $withCustomValues = true
     * @return array|string[]
     */
    public static function getKnivesAndDaggersValues(bool $withCustomValues = true): array
    {
        $defaultValues = [self::KNIFE, self::DAGGER, self::STABBING_DAGGER, self::LONG_KNIFE, self::LONG_DAGGER];
        if (!$withCustomValues) {
            return $defaultValues;
        }

        return array_merge(
            $defaultValues,
            self::$customMeleeWeaponCodePerCategory[WeaponCategoryCode::KNIVES_AND_DAGGERS] ?? []
        );
    }

    // maces and clubs
    public const CUDGEL = 'cudgel';
    public const CLUB = 'club';
    public const HOBNAILED_CLUB = 'hobnailed_club';
    public const LIGHT_MACE = 'light_mace';
    public const MACE = 'mace';
    public const HEAVY_CLUB = 'heavy_club';
    public const WAR_HAMMER = 'war_hammer';
    public const TWO_HANDED_CLUB = 'two_handed_club';
    public const HEAVY_SLEDGEHAMMER = 'heavy_sledgehammer';

    /**
     * @param bool $withCustomValues = true
     * @return array|string[]
     */
    public static function getMacesAndClubsValues(bool $withCustomValues = true): array
    {
        $defaultValues = [
            self::CUDGEL,
            self::CLUB,
            self::HOBNAILED_CLUB,
            self::LIGHT_MACE,
            self::MACE,
            self::HEAVY_CLUB,
            self::WAR_HAMMER,
            self::TWO_HANDED_CLUB,
            self::HEAVY_SLEDGEHAMMER,
        ];
        if (!$withCustomValues) {
            return $defaultValues;
        }

        return array_merge(
            $defaultValues,
            self::$customMeleeWeaponCodePerCategory[WeaponCategoryCode::MACES_AND_CLUBS] ?? []
        );
    }

    // morningstars and morgensterns
    public const LIGHT_MORGENSTERN = 'light_morgenstern';
    public const MORGENSTERN = 'morgenstern';
    public const HEAVY_MORGENSTERN = 'heavy_morgenstern';
    public const FLAIL = 'flail';
    public const MORNINGSTAR = 'morningstar';
    public const HOBNAILED_FLAIL = 'hobnailed_flail';
    public const HEAVY_MORNINGSTAR = 'heavy_morningstar';

    /**
     * @param bool $withCustomValues = true
     * @return array|string[]
     */
    public static function getMorningstarsAndMorgensternsValues(bool $withCustomValues = true): array
    {
        $defaultValues = [
            self::LIGHT_MORGENSTERN,
            self::MORGENSTERN,
            self::HEAVY_MORGENSTERN,
            self::FLAIL,
            self::MORNINGSTAR,
            self::HOBNAILED_FLAIL,
            self::HEAVY_MORNINGSTAR,
        ];
        if (!$withCustomValues) {
            return $defaultValues;
        }

        return array_merge(
            $defaultValues,
            self::$customMeleeWeaponCodePerCategory[WeaponCategoryCode::MORNINGSTARS_AND_MORGENSTERNS] ?? []
        );
    }

    // sabers and bowie knifes
    public const MACHETE = 'machete';
    public const LIGHT_SABER = 'light_saber';
    public const BOWIE_KNIFE = 'bowie_knife';
    public const SABER = 'saber';
    public const HEAVY_SABER = 'heavy_saber';

    /**
     * @param bool $withCustomValues = true
     * @return array|string[]
     */
    public static function getSabersAndBowieKnivesValues(bool $withCustomValues = true): array
    {
        $defaultValues = [self::MACHETE, self::LIGHT_SABER, self::BOWIE_KNIFE, self::SABER, self::HEAVY_SABER];
        if (!$withCustomValues) {
            return $defaultValues;
        }

        return array_merge(
            $defaultValues,
            self::$customMeleeWeaponCodePerCategory[WeaponCategoryCode::SABERS_AND_BOWIE_KNIVES] ?? []
        );
    }

    // staffs and spears
    public const LIGHT_SPEAR = 'light_spear';
    public const SHORTENED_STAFF = 'shortened_staff';
    public const LIGHT_STAFF = 'light_staff';
    public const SPEAR = 'spear';
    public const HOBNAILED_STAFF = 'hobnailed_staff';
    public const LONG_SPEAR = 'long_spear';
    public const HEAVY_HOBNAILED_STAFF = 'heavy_hobnailed_staff';
    public const PIKE = 'pike';
    public const METAL_STAFF = 'metal_staff';

    /**
     * @param bool $withCustomValues = true
     * @return array|string[]
     */
    public static function getStaffsAndSpearsValues(bool $withCustomValues = true): array
    {
        $defaultValues = [
            self::LIGHT_SPEAR,
            self::SHORTENED_STAFF,
            self::LIGHT_STAFF,
            self::SPEAR,
            self::HOBNAILED_STAFF,
            self::LONG_SPEAR,
            self::HEAVY_HOBNAILED_STAFF,
            self::PIKE,
            self::METAL_STAFF,
        ];
        if (!$withCustomValues) {
            return $defaultValues;
        }

        return array_merge(
            $defaultValues,
            self::$customMeleeWeaponCodePerCategory[WeaponCategoryCode::STAFFS_AND_SPEARS] ?? []
        );
    }

    // swords
    public const SHORT_SWORD = 'short_sword';
    public const HANGER = 'hanger';
    public const GLAIVE = 'glaive';
    public const LONG_SWORD = 'long_sword';
    public const ONE_AND_HALF_HANDED_SWORD = 'one_and_half_handed_sword';
    public const BARBARIAN_SWORD = 'barbarian_sword';
    public const TWO_HANDED_SWORD = 'two_handed_sword';

    /**
     * @param bool $withCustomValues = true
     * @return array|string[]
     */
    public static function getSwordsValues(bool $withCustomValues = true): array
    {
        $defaultValues = [
            self::SHORT_SWORD,
            self::HANGER,
            self::GLAIVE,
            self::LONG_SWORD,
            self::ONE_AND_HALF_HANDED_SWORD,
            self::BARBARIAN_SWORD,
            self::TWO_HANDED_SWORD,
        ];
        if (!$withCustomValues) {
            return $defaultValues;
        }

        return array_merge($defaultValues, self::$customMeleeWeaponCodePerCategory[WeaponCategoryCode::SWORDS] ?? []);
    }

    // voulges and tridents
    public const PITCHFORK = 'pitchfork';
    public const LIGHT_VOULGE = 'light_voulge';
    public const LIGHT_TRIDENT = 'light_trident';
    public const HALBERD = 'halberd';
    public const HEAVY_VOULGE = 'heavy_voulge';
    public const HEAVY_TRIDENT = 'heavy_trident';
    public const HEAVY_HALBERD = 'heavy_halberd';

    /**
     * @param bool $withCustomValues = true
     * @return array|string[]
     */
    public static function getVoulgesAndTridentsValues(bool $withCustomValues = true): array
    {
        $defaultValues = [
            self::PITCHFORK,
            self::LIGHT_VOULGE,
            self::LIGHT_TRIDENT,
            self::HALBERD,
            self::HEAVY_VOULGE,
            self::HEAVY_TRIDENT,
            self::HEAVY_HALBERD,
        ];
        if (!$withCustomValues) {
            return $defaultValues;
        }

        return array_merge(
            $defaultValues,
            self::$customMeleeWeaponCodePerCategory[WeaponCategoryCode::VOULGES_AND_TRIDENTS] ?? []
        );
    }

    // unarmed
    public const HAND = 'hand';
    public const HOBNAILED_GLOVE = 'hobnailed_glove';
    public const LEG = 'leg';
    public const HOBNAILED_BOOT = 'hobnailed_boot';

    /**
     * @param bool $withCustomValues = true
     * @return array|string[]
     */
    public static function getUnarmedValues(bool $withCustomValues = true): array
    {
        $defaultValues = [self::HAND, self::HOBNAILED_GLOVE, self::LEG, self::HOBNAILED_BOOT];
        if (!$withCustomValues) {
            return $defaultValues;
        }

        return array_merge(
            $defaultValues,
            self::$customMeleeWeaponCodePerCategory[WeaponCategoryCode::UNARMED] ?? []
        );
    }

    /**
     * @return array|string[]
     */
    protected static function getDefaultValues(): array
    {
        return array_values( // to get continual integer keys
            array_merge(
                self::getUnarmedValues(false /* without custom */),
                self::getAxesValues(false /* without custom */),
                self::getKnivesAndDaggersValues(false /* without custom */),
                self::getMacesAndClubsValues(false /* without custom */),
                self::getMorningstarsAndMorgensternsValues(false /* without custom */),
                self::getSabersAndBowieKnivesValues(false /* without custom */),
                self::getStaffsAndSpearsValues(false /* without custom */),
                self::getSwordsValues(false /* without custom */),
                self::getVoulgesAndTridentsValues(false /* without custom */)
            )
        );
    }

    /**
     * @param string $newMeleeWeaponCodeValue
     * @param WeaponCategoryCode $meleeWeaponCategoryCode
     * @param array $translations
     * @return bool
     * @throws \DrdPlus\Codes\Armaments\Exceptions\InvalidWeaponCategoryForNewMeleeWeaponCode
     * @throws \DrdPlus\Codes\Armaments\Exceptions\MeleeWeaponIsAlreadyInDifferentWeaponCategory
     * @throws \DrdPlus\Codes\Partials\Exceptions\InvalidLanguageCode
     * @throws \DrdPlus\Codes\Partials\Exceptions\UnknownTranslationPlural
     * @throws \DrdPlus\Codes\Partials\Exceptions\InvalidTranslationFormat
     */
    public static function addNewMeleeWeaponCode(
        string $newMeleeWeaponCodeValue,
        WeaponCategoryCode $meleeWeaponCategoryCode,
        array $translations
    ): bool
    {
        if (!$meleeWeaponCategoryCode->isMeleeWeaponCategory()) {
            throw new Exceptions\InvalidWeaponCategoryForNewMeleeWeaponCode(
                'Expected one of melee weapon categories, got ' . $meleeWeaponCategoryCode
            );
        }
        $extended = parent::addNewCode($newMeleeWeaponCodeValue, $translations);
        if (!$extended) {
            self::guardSameCategory($newMeleeWeaponCodeValue, $meleeWeaponCategoryCode);

            return false;
        }
        self::$customMeleeWeaponCodePerCategory[$meleeWeaponCategoryCode->getValue()][] = $newMeleeWeaponCodeValue;

        return true;
    }

    /**
     * @param string $meleeWeaponValue
     * @param WeaponCategoryCode $weaponCategoryCode
     * @throws \DrdPlus\Codes\Armaments\Exceptions\MeleeWeaponIsAlreadyInDifferentWeaponCategory
     */
    private static function guardSameCategory(string $meleeWeaponValue, WeaponCategoryCode $weaponCategoryCode)
    {
        if (!in_array($meleeWeaponValue, self::$customMeleeWeaponCodePerCategory[$weaponCategoryCode->getValue()] ?? [], true)) {
            $alreadyUsedCategory = null;
            foreach (WeaponCategoryCode::getPossibleValues() as $anotherCategory) {
                if ($anotherCategory !== $weaponCategoryCode->getValue()
                    && in_array($meleeWeaponValue, self::$customMeleeWeaponCodePerCategory[$anotherCategory] ?? [], true)
                ) {
                    $alreadyUsedCategory = $anotherCategory;
                }
            }
            throw new Exceptions\MeleeWeaponIsAlreadyInDifferentWeaponCategory(
                "Can not register new melee weapon '$meleeWeaponValue' into category '$weaponCategoryCode'"
                . " because is already registered in category '$alreadyUsedCategory'"
            );
        }
    }

    /**
     * Both melee weapons and shields are melee.
     *
     * @return bool
     */
    final public function isMelee(): bool
    {
        return true;
    }

    /**
     * Note: Shield is weaponlike, but not a weapon.
     *
     * @return bool
     */
    final public function isMeleeWeapon(): bool
    {
        return true;
    }

    /**
     * Even melee weapon can be ranged (currently spear only).
     *
     * @return bool
     */
    public function isRanged(): bool
    {
        return $this->isShootingWeapon() || $this->isThrowingWeapon();
    }

    public function isShootingWeapon(): bool
    {
        return false;
    }

    public function isThrowingWeapon(): bool
    {
        return false;
    }

    public function isAxe(): bool
    {
        return in_array($this->getValue(), self::getAxesValues(), true);
    }

    public function isKnifeOrDagger(): bool
    {
        return in_array($this->getValue(), self::getKnivesAndDaggersValues(), true);
    }

    public function isMaceOrClub(): bool
    {
        return in_array($this->getValue(), self::getMacesAndClubsValues(), true);
    }

    public function isMorningstarOrMorgenstern(): bool
    {
        return in_array($this->getValue(), self::getMorningstarsAndMorgensternsValues(), true);
    }

    public function isSaberOrBowieKnife(): bool
    {
        return in_array($this->getValue(), self::getSabersAndBowieKnivesValues(), true);
    }

    public function isStaffOrSpear(): bool
    {
        return in_array($this->getValue(), self::getStaffsAndSpearsValues(), true);
    }

    public function isSword(): bool
    {
        return in_array($this->getValue(), self::getSwordsValues(), true);
    }

    public function isVoulgeOrTrident(): bool
    {
        return in_array($this->getValue(), self::getVoulgesAndTridentsValues(), true);
    }

    public function isUnarmed(): bool
    {
        return in_array($this->getValue(), self::getUnarmedValues(), true);
    }

    /**
     * @return RangedWeaponCode
     * @throws \DrdPlus\Codes\Armaments\Exceptions\CanNotBeConvertedToRangeWeaponCode
     */
    public function convertToRangedWeaponCodeEquivalent(): RangedWeaponCode
    {
        if ($this->getValue() !== self::SPEAR) {
            throw new Exceptions\CanNotBeConvertedToRangeWeaponCode(
                "Melee weapon code {$this} can not be converted to range weapon code"
            );
        }

        return RangedWeaponCode::getIt($this->getValue());
    }

    public function convertToMeleeWeaponCodeEquivalent(): MeleeWeaponCode
    {
        return $this;
    }

    /**
     * @return array|string[]
     */
    protected function fetchTranslations(): array
    {
        return [
            'en' => [
                self::LIGHT_AXE => [self::$ONE => 'light axe', self::$FEW => 'light axes', self::$MANY => 'light axes'],
                self::AXE => [self::$ONE => 'axe', self::$FEW => 'axes', self::$MANY => 'axes'],
                self::WAR_AXE => [self::$ONE => 'war axe', self::$FEW => 'war axes', self::$MANY => 'war axes'],
                self::TWO_HANDED_AXE => [self::$ONE => 'two handed axe', self::$FEW => 'two handed axes', self::$MANY => 'two handed axes'],
                self::KNIFE => [self::$ONE => 'knife', self::$FEW => 'knives', self::$MANY => 'knives'],
                self::DAGGER => [self::$ONE => 'dagger', self::$FEW => 'daggers', self::$MANY => 'daggers'],
                self::STABBING_DAGGER => [self::$ONE => 'stabbing dagger', self::$FEW => 'stabbing daggers', self::$MANY => 'stabbing daggers'],
                self::LONG_KNIFE => [self::$ONE => 'long knife', self::$FEW => 'long knives', self::$MANY => 'long knives'],
                self::LONG_DAGGER => [self::$ONE => 'long dagger', self::$FEW => 'long daggers', self::$MANY => 'long daggers'],
                self::CUDGEL => [self::$ONE => 'cudgel', self::$FEW => 'cudgels', self::$MANY => 'cudgels'],
                self::CLUB => [self::$ONE => 'club', self::$FEW => 'clubs', self::$MANY => 'clubs'],
                self::HOBNAILED_CLUB => [self::$ONE => 'hobnailed club', self::$FEW => 'hobnailed clubs', self::$MANY => 'hobnailed clubs'],
                self::LIGHT_MACE => [self::$ONE => 'light mace', self::$FEW => 'light maces', self::$MANY => 'light maces'],
                self::MACE => [self::$ONE => 'mace', self::$FEW => 'maces', self::$MANY => 'maces'],
                self::HEAVY_CLUB => [self::$ONE => 'heavy club', self::$FEW => 'heavy clubs', self::$MANY => 'heavy clubs'],
                self::WAR_HAMMER => [self::$ONE => 'war hammer', self::$FEW => 'war hammers', self::$MANY => 'war hammers'],
                self::TWO_HANDED_CLUB => [self::$ONE => 'two handed club', self::$FEW => 'two handed clubs', self::$MANY => 'two handed clubs'],
                self::HEAVY_SLEDGEHAMMER => [self::$ONE => 'heavy sledgehammer', self::$FEW => 'heavy sledgehammers', self::$MANY => 'heavy sledgehammers'],
                self::LIGHT_MORGENSTERN => [self::$ONE => 'light morgenstern', self::$FEW => 'light morgensterns', self::$MANY => 'light morgensterns'],
                self::MORGENSTERN => [self::$ONE => 'morgenstern', self::$FEW => 'morgensterns', self::$MANY => 'morgensterns'],
                self::HEAVY_MORGENSTERN => [self::$ONE => 'heavy morgenstern', self::$FEW => 'heavy morgensterns', self::$MANY => 'heavy morgensterns'],
                self::FLAIL => [self::$ONE => 'flail', self::$FEW => 'flails', self::$MANY => 'flails'],
                self::MORNINGSTAR => [self::$ONE => 'morningstar', self::$FEW => 'morningstars', self::$MANY => 'morningstars'],
                self::HOBNAILED_FLAIL => [self::$ONE => 'hobnailed flail', self::$FEW => 'hobnailed flails', self::$MANY => 'hobnailed flails'],
                self::HEAVY_MORNINGSTAR => [self::$ONE => 'heavy morningstar', self::$FEW => 'heavy morningstars', self::$MANY => 'heavy morningstars'],
                self::MACHETE => [self::$ONE => 'machete', self::$FEW => 'machetes', self::$MANY => 'machetes'],
                self::LIGHT_SABER => [self::$ONE => 'light saber', self::$FEW => 'light sabers', self::$MANY => 'light sabers'],
                self::BOWIE_KNIFE => [self::$ONE => 'bowie knife', self::$FEW => 'bowie knives', self::$MANY => 'bowie knives'],
                self::SABER => [self::$ONE => 'saber', self::$FEW => 'sabers', self::$MANY => 'sabers'],
                self::HEAVY_SABER => [self::$ONE => 'heavy saber', self::$FEW => 'heavy sabers', self::$MANY => 'heavy sabers'],
                self::LIGHT_SPEAR => [self::$ONE => 'light spear', self::$FEW => 'light spears', self::$MANY => 'light spears'],
                self::SHORTENED_STAFF => [self::$ONE => 'shortened staff', self::$FEW => 'shortened staffs', self::$MANY => 'shortened staffs'],
                self::LIGHT_STAFF => [self::$ONE => 'light staff', self::$FEW => 'light staffs', self::$MANY => 'light staffs'],
                self::SPEAR => [self::$ONE => 'spear', self::$FEW => 'spears', self::$MANY => 'spears'],
                self::HOBNAILED_STAFF => [self::$ONE => 'hobnailed staff', self::$FEW => 'hobnailed staffs', self::$MANY => 'hobnailed staffs'],
                self::LONG_SPEAR => [self::$ONE => 'long spear', self::$FEW => 'long spears', self::$MANY => 'long spears'],
                self::HEAVY_HOBNAILED_STAFF => [self::$ONE => 'heavy hobnailed staff', self::$FEW => 'heavy hobnailed staffs', self::$MANY => 'heavy hobnailed staffs'],
                self::PIKE => [self::$ONE => 'pike', self::$FEW => 'pikes', self::$MANY => 'pikes'],
                self::METAL_STAFF => [self::$ONE => 'metal staff', self::$FEW => 'metal staffs', self::$MANY => 'metal staffs'],
                self::SHORT_SWORD => [self::$ONE => 'short sword', self::$FEW => 'short swords', self::$MANY => 'short swords'],
                self::HANGER => [self::$ONE => 'hanger', self::$FEW => 'hangers', self::$MANY => 'hangers'],
                self::GLAIVE => [self::$ONE => 'glaive', self::$FEW => 'glaives', self::$MANY => 'glaives'],
                self::LONG_SWORD => [self::$ONE => 'long sword', self::$FEW => 'long swords', self::$MANY => 'long swords'],
                self::ONE_AND_HALF_HANDED_SWORD => [self::$ONE => 'one and half handed sword', self::$FEW => 'one and half handed swords', self::$MANY => 'one and half handed swords'],
                self::BARBARIAN_SWORD => [self::$ONE => 'barbarian sword', self::$FEW => 'barbarian swords', self::$MANY => 'barbarian swords'],
                self::TWO_HANDED_SWORD => [self::$ONE => 'two handed sword', self::$FEW => 'two handed swords', self::$MANY => 'two handed swords'],
                self::PITCHFORK => [self::$ONE => 'pitchfork', self::$FEW => 'pitchforks', self::$MANY => 'pitchforks'],
                self::LIGHT_VOULGE => [self::$ONE => 'light voulge', self::$FEW => 'light voulges', self::$MANY => 'light voulges'],
                self::LIGHT_TRIDENT => [self::$ONE => 'light trident', self::$FEW => 'light tridents', self::$MANY => 'light tridents'],
                self::HALBERD => [self::$ONE => 'halberd', self::$FEW => 'halberds', self::$MANY => 'halberds'],
                self::HEAVY_VOULGE => [self::$ONE => 'heavy voulge', self::$FEW => 'heavy voulges', self::$MANY => 'heavy voulges'],
                self::HEAVY_TRIDENT => [self::$ONE => 'heavy trident', self::$FEW => 'heavy tridents', self::$MANY => 'heavy tridents'],
                self::HEAVY_HALBERD => [self::$ONE => 'heavy halberd', self::$FEW => 'heavy halberds', self::$MANY => 'heavy halberds'],
                self::HAND => [self::$ONE => 'hand', self::$FEW => 'hands', self::$MANY => 'hands'],
                self::HOBNAILED_GLOVE => [self::$ONE => 'hobnailed glove', self::$FEW => 'hobnailed gloves', self::$MANY => 'hobnailed gloves'],
                self::LEG => [self::$ONE => 'leg', self::$FEW => 'legs', self::$MANY => 'legs'],
                self::HOBNAILED_BOOT => [self::$ONE => 'hobnailed boot', self::$FEW => 'hobnailed boots', self::$MANY => 'hobnailed boots'],
            ],
            'cs' => [
                self::LIGHT_AXE => [self::$ONE => 'lehká sekerka', self::$FEW => 'lehké sekerky', self::$MANY => 'lehkých sekerek'],
                self::AXE => [self::$ONE => 'sekera', self::$FEW => 'sekery', self::$MANY => 'seker'],
                self::WAR_AXE => [self::$ONE => 'válečná sekera', self::$FEW => 'válečné sekery', self::$MANY => 'válečných seker'],
                self::TWO_HANDED_AXE => [self::$ONE => 'obouruční sekera', self::$FEW => 'obouruční sekery', self::$MANY => 'obouručních seker'],
                self::KNIFE => [self::$ONE => 'nůž', self::$FEW => 'nože', self::$MANY => 'nožů'],
                self::DAGGER => [self::$ONE => 'dýka', self::$FEW => 'dýky', self::$MANY => 'dýk'],
                self::STABBING_DAGGER => [self::$ONE => 'bodná dýka', self::$FEW => 'bodné dýky', self::$MANY => 'bodných dýk'],
                self::LONG_KNIFE => [self::$ONE => 'dlouhý nůž', self::$FEW => 'dlouhé nože', self::$MANY => 'dlouhých nožů'],
                self::LONG_DAGGER => [self::$ONE => 'dlouhá dýka', self::$FEW => 'dlouhé dýky', self::$MANY => 'dlouhých dýk'],
                self::CUDGEL => [self::$ONE => 'obušek', self::$FEW => 'obušky', self::$MANY => 'obušků'],
                self::CLUB => [self::$ONE => 'kyj', self::$FEW => 'kyje', self::$MANY => 'kyjů'],
                self::HOBNAILED_CLUB => [self::$ONE => 'okovaný kyj', self::$FEW => 'okované kyje', self::$MANY => 'okovaných kyjů'],
                self::LIGHT_MACE => [self::$ONE => 'lehký palcát', self::$FEW => 'lehké palcáty', self::$MANY => 'lehkých palcátů'],
                self::MACE => [self::$ONE => 'palcát', self::$FEW => 'palcáty', self::$MANY => 'palcátů'],
                self::HEAVY_CLUB => [self::$ONE => 'těžký kyj', self::$FEW => 'těžké kyje', self::$MANY => 'těžkých kyjů'],
                self::WAR_HAMMER => [self::$ONE => 'válečné kladivo', self::$FEW => 'válečná kladiva', self::$MANY => 'válečných kladiv'],
                self::TWO_HANDED_CLUB => [self::$ONE => 'obouruční kyj', self::$FEW => 'obouruční kyje', self::$MANY => 'obouručních kyjů'],
                self::HEAVY_SLEDGEHAMMER => [self::$ONE => 'těžký perlík', self::$FEW => 'těžké perlíky', self::$MANY => 'těžkých perlíků'],
                self::LIGHT_MORGENSTERN => [self::$ONE => 'lehký biják', self::$FEW => 'lehké bijáky', self::$MANY => 'lehkých bijáků'],
                self::MORGENSTERN => [self::$ONE => 'biják', self::$FEW => 'bijáky', self::$MANY => 'bijáků'],
                self::HEAVY_MORGENSTERN => [self::$ONE => 'těžký biják', self::$FEW => 'těžké bijáky', self::$MANY => 'těžkých bijáků'],
                self::FLAIL => [self::$ONE => 'cep', self::$FEW => 'cepy', self::$MANY => 'cepů'],
                self::MORNINGSTAR => [self::$ONE => 'řemdih', self::$FEW => 'řemdihy', self::$MANY => 'řemdihů'],
                self::HOBNAILED_FLAIL => [self::$ONE => 'okovaný cep', self::$FEW => 'okované cepy', self::$MANY => 'okovaných cepů'],
                self::HEAVY_MORNINGSTAR => [self::$ONE => 'těžký řemdih', self::$FEW => 'těžké řemdihy', self::$MANY => 'těžkých řemdihů'],
                self::MACHETE => [self::$ONE => 'mačeta', self::$FEW => 'mačety', self::$MANY => 'mačet'],
                self::LIGHT_SABER => [self::$ONE => 'lehká šavle', self::$FEW => 'lehké šavle', self::$MANY => 'lehkých šavlí'],
                self::BOWIE_KNIFE => [self::$ONE => 'tesák', self::$FEW => 'tesáky', self::$MANY => 'tesáků'],
                self::SABER => [self::$ONE => 'šavle', self::$FEW => 'šavle', self::$MANY => 'šavlí'],
                self::HEAVY_SABER => [self::$ONE => 'těžká šavle', self::$FEW => 'těžké šavle', self::$MANY => 'těžkých šavlí'],
                self::LIGHT_SPEAR => [self::$ONE => 'lehké kopí', self::$FEW => 'lehká kopí', self::$MANY => 'lehkých kopí'],
                self::SHORTENED_STAFF => [self::$ONE => 'zkrácená hůl', self::$FEW => 'zkrácené hole', self::$MANY => 'zkrácených holí'],
                self::LIGHT_STAFF => [self::$ONE => 'lehká hůl', self::$FEW => 'lehké hole', self::$MANY => 'lehkých holí'],
                self::SPEAR => [self::$ONE => 'kopí', self::$FEW => 'kopí', self::$MANY => 'kopí'],
                self::HOBNAILED_STAFF => [self::$ONE => 'okovaná hůl', self::$FEW => 'okované hole', self::$MANY => 'okovaných holí'],
                self::LONG_SPEAR => [self::$ONE => 'dlouhé kopí', self::$FEW => 'dlouhá kopí', self::$MANY => 'dlouhých kopí'],
                self::HEAVY_HOBNAILED_STAFF => [self::$ONE => 'těžká okovaná hůl', self::$FEW => 'těžké okované hole', self::$MANY => 'těžkých okovaných holí'],
                self::PIKE => [self::$ONE => 'píka', self::$FEW => 'píky', self::$MANY => 'pík'],
                self::METAL_STAFF => [self::$ONE => 'kovová hůl', self::$FEW => 'kovové hole', self::$MANY => 'kovových holí'],
                self::SHORT_SWORD => [self::$ONE => 'krátký meč', self::$FEW => 'krátké meče', self::$MANY => 'krátkých mečů'],
                self::HANGER => [self::$ONE => 'krátký široký meč', self::$FEW => 'krátké široké meče', self::$MANY => 'krátkých širokých mečů'],
                self::GLAIVE => [self::$ONE => 'široký meč', self::$FEW => 'široké meče', self::$MANY => 'širokých mečů'],
                self::LONG_SWORD => [self::$ONE => 'dlouhý meč', self::$FEW => 'dlouhé meče', self::$MANY => 'dlouhých mečů'],
                self::ONE_AND_HALF_HANDED_SWORD => [self::$ONE => 'jedenapůlruční meč', self::$FEW => 'jedenapůlruční meče', self::$MANY => 'jedenapůlručních mečů'],
                self::BARBARIAN_SWORD => [self::$ONE => 'barbarský meč', self::$FEW => 'barbarské meče', self::$MANY => 'barbarských mečů'],
                self::TWO_HANDED_SWORD => [self::$ONE => 'obouruční meč', self::$FEW => 'obouruční meče', self::$MANY => 'obouručních mečů'],
                self::PITCHFORK => [self::$ONE => 'vidle', self::$FEW => 'vidle', self::$MANY => 'vidlí'],
                self::LIGHT_VOULGE => [self::$ONE => 'lehká sudlice', self::$FEW => 'lehké sudlice', self::$MANY => 'lehkých sudlic'],
                self::LIGHT_TRIDENT => [self::$ONE => 'lehký trojzubec', self::$FEW => 'lehké trojzubce', self::$MANY => 'lehkých trojzubců'],
                self::HALBERD => [self::$ONE => 'halapartna', self::$FEW => 'halapartny', self::$MANY => 'halaparten'],
                self::HEAVY_VOULGE => [self::$ONE => 'těžká sudlice', self::$FEW => 'těžké sudlice', self::$MANY => 'těžkých sudlic'],
                self::HEAVY_TRIDENT => [self::$ONE => 'těžký trojzubec', self::$FEW => 'těžké trojzubce', self::$MANY => 'těžkých trojzubců'],
                self::HEAVY_HALBERD => [self::$ONE => 'těžká halapartna', self::$FEW => 'těžké halapartny', self::$MANY => 'těžkých halaparten'],
                self::HAND => [self::$ONE => 'ruka', self::$FEW => 'ruce', self::$MANY => 'rukou'],
                self::HOBNAILED_GLOVE => [self::$ONE => 'okovaná rukavice', self::$FEW => 'okované rukavice', self::$MANY => 'okovaných rukavic'],
                self::LEG => [self::$ONE => 'noha', self::$FEW => 'nohy', self::$MANY => 'nohou'],
                self::HOBNAILED_BOOT => [self::$ONE => 'okovaná bota', self::$FEW => 'okované boty', self::$MANY => 'okovaných bot'],
            ],
        ];
    }
}