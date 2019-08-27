<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables;

use DrdPlus\Codes\Armaments\ArmamentCode;
use DrdPlus\Codes\Armaments\ArmorCode;
use DrdPlus\Codes\Armaments\BodyArmorCode;
use DrdPlus\Codes\Armaments\HelmCode;
use DrdPlus\Codes\Armaments\MeleeWeaponlikeCode;
use DrdPlus\Codes\Armaments\MeleeWeaponCode;
use DrdPlus\Codes\Armaments\ProjectileCode;
use DrdPlus\Codes\Armaments\ProtectiveArmamentCode;
use DrdPlus\Codes\Armaments\RangedWeaponCode;
use DrdPlus\Codes\Armaments\ShieldCode;
use DrdPlus\Codes\Armaments\WeaponlikeCode;
use DrdPlus\Tables\Activities\CatchQualitiesTable;
use DrdPlus\Tables\Body\AspectsOfVisageTable;
use DrdPlus\Tables\Activities\JumpsAndFallsTable;
use DrdPlus\Tables\Body\WoundAndFatigueBoundariesTable;
use DrdPlus\Tables\Combat\Actions\CombatActionsCompatibilityTable;
use DrdPlus\Tables\Combat\Actions\CombatActionsWithWeaponTypeCompatibilityTable;
use DrdPlus\Tables\Armaments\Armors\AbstractArmorsTable;
use DrdPlus\Tables\Armaments\Armors\ArmorWearingSkillTable;
use DrdPlus\Tables\Armaments\Armors\ArmorStrengthSanctionsTable;
use DrdPlus\Tables\Armaments\Armors\BodyArmorsTable;
use DrdPlus\Tables\Armaments\Armors\HelmsTable;
use DrdPlus\Tables\Armaments\Exceptions\UnknownArmament;
use DrdPlus\Tables\Armaments\Exceptions\UnknownArmor;
use DrdPlus\Tables\Armaments\Exceptions\UnknownMeleeWeaponlike;
use DrdPlus\Tables\Armaments\Exceptions\UnknownMeleeWeapon;
use DrdPlus\Tables\Armaments\Exceptions\UnknownProjectile;
use DrdPlus\Tables\Armaments\Exceptions\UnknownProtectiveArmament;
use DrdPlus\Tables\Armaments\Exceptions\UnknownRangedWeapon;
use DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike;
use DrdPlus\Tables\Armaments\MissingProtectiveArmamentSkill;
use DrdPlus\Tables\Armaments\Partials\AbstractMeleeWeaponlikeStrengthSanctionsTable;
use DrdPlus\Tables\Armaments\Partials\MeleeWeaponlikesTable;
use DrdPlus\Tables\Armaments\Partials\StrengthSanctionsInterface;
use DrdPlus\Tables\Armaments\Partials\WeaponStrengthSanctionsInterface;
use DrdPlus\Tables\Armaments\Partials\UnwieldyTable;
use DrdPlus\Tables\Armaments\Partials\WeaponlikeTable;
use DrdPlus\Tables\Armaments\Projectiles\ArrowsTable;
use DrdPlus\Tables\Armaments\Projectiles\DartsTable;
use DrdPlus\Tables\Armaments\Projectiles\Partials\ProjectilesTable;
use DrdPlus\Tables\Armaments\Projectiles\SlingStonesTable;
use DrdPlus\Tables\Armaments\Shields\ShieldUsageSkillTable;
use DrdPlus\Tables\Armaments\Shields\ShieldStrengthSanctionsTable;
use DrdPlus\Tables\Armaments\Shields\ShieldsTable;
use DrdPlus\Tables\Armaments\Weapons\Melee\AxesTable;
use DrdPlus\Tables\Armaments\Weapons\Melee\KnivesAndDaggersTable;
use DrdPlus\Tables\Armaments\Weapons\Melee\MacesAndClubsTable;
use DrdPlus\Tables\Armaments\Weapons\Melee\MeleeWeaponStrengthSanctionsTable;
use DrdPlus\Tables\Armaments\Weapons\Melee\MorningstarsAndMorgensternsTable;
use DrdPlus\Tables\Armaments\Weapons\Melee\Partials\MeleeWeaponsTable;
use DrdPlus\Tables\Armaments\Weapons\Melee\SabersAndBowieKnivesTable;
use DrdPlus\Tables\Armaments\Weapons\Melee\StaffsAndSpearsTable;
use DrdPlus\Tables\Armaments\Weapons\Melee\SwordsTable;
use DrdPlus\Tables\Armaments\Weapons\Melee\UnarmedTable;
use DrdPlus\Tables\Armaments\Weapons\Melee\VoulgesAndTridentsTable;
use DrdPlus\Tables\Armaments\Weapons\MissingWeaponSkillTable;
use DrdPlus\Tables\Armaments\Weapons\Ranged\BowsTable;
use DrdPlus\Tables\Armaments\Weapons\Ranged\CrossbowsTable;
use DrdPlus\Tables\Armaments\Weapons\Ranged\RangedWeaponStrengthSanctionsTable;
use DrdPlus\Tables\Armaments\Weapons\Ranged\ThrowingWeaponsTable;
use DrdPlus\Tables\Combat\CombatCharacteristicsTable;
use DrdPlus\Tables\Body\CorrectionByHeightTable;
use DrdPlus\Tables\Body\FatigueByLoad\FatigueByLoadTable;
use DrdPlus\Tables\Body\Healing\HealingByActivityTable;
use DrdPlus\Tables\Body\Healing\HealingByConditionsTable;
use DrdPlus\Tables\Body\MovementTypes\MovementTypesTable;
use DrdPlus\Tables\Body\Resting\RestingBySituationTable;
use DrdPlus\Tables\Combat\Attacks\AttackNumberByContinuousDistanceTable;
use DrdPlus\Tables\Combat\FightTable;
use DrdPlus\Tables\Environments\ImpassibilityOfTerrainTable;
use DrdPlus\Tables\Combat\Attacks\AttackNumberByDistanceTable;
use DrdPlus\Tables\Environments\ImprovementOfLightSourceTable;
use DrdPlus\Tables\Environments\LightingQualityTable;
use DrdPlus\Tables\Activities\PossibleActivitiesAccordingToContrastTable;
use DrdPlus\Tables\Environments\MalusesToAutomaticSearchingTable;
use DrdPlus\Tables\Environments\MaterialResistancesTable;
use DrdPlus\Tables\Environments\PowerOfLightSourcesTable;
use DrdPlus\Tables\Environments\LandingSurfacesTable;
use DrdPlus\Tables\Environments\StealthinessTable;
use DrdPlus\Tables\History\AncestryTable;
use DrdPlus\Tables\History\BackgroundPointsDistributionTable;
use DrdPlus\Tables\History\BackgroundPointsTable;
use DrdPlus\Tables\History\InfluenceOfFortuneTable;
use DrdPlus\Tables\History\PlayerDecisionsTable;
use DrdPlus\Tables\History\PossessionTable;
use DrdPlus\Tables\Riding\RidesByMovementTypeTable;
use DrdPlus\Tables\Riding\RidingAnimalsAndFlyingBeastsMovementTypesTable;
use DrdPlus\Tables\Riding\RidingAnimalsTable;
use DrdPlus\Tables\Riding\WoundsOnFallFromHorseTable;
use DrdPlus\Tables\Measurements\Amount\AmountTable;
use DrdPlus\Tables\Measurements\BaseOfWounds\BaseOfWoundsTable;
use DrdPlus\Tables\Measurements\Distance\DistanceTable;
use DrdPlus\Tables\Measurements\Experiences\ExperiencesTable;
use DrdPlus\Tables\Measurements\Fatigue\FatigueTable;
use DrdPlus\Tables\Measurements\Speed\SpeedTable;
use DrdPlus\Tables\Measurements\Time\BonusAdjustmentByTimeTable;
use DrdPlus\Tables\Measurements\Time\TimeTable;
use DrdPlus\Tables\Measurements\Weight\WeightTable;
use DrdPlus\Tables\Measurements\Wounds\WoundsTable;
use DrdPlus\Tables\History\SkillsByBackgroundPointsTable;
use DrdPlus\Tables\Professions\ProfessionPrimaryPropertiesTable;
use DrdPlus\Tables\Races\FemaleModifiersTable;
use DrdPlus\Tables\Races\RacesTable;
use DrdPlus\Tables\Races\SightRangesTable;
use DrdPlus\Tables\Theurgist\Demons\DemonsTable;
use DrdPlus\Tables\Theurgist\Demons\DemonTraitsTable;
use DrdPlus\Tables\Theurgist\Spells\FormulasTable;
use DrdPlus\Tables\Theurgist\Spells\ModifiersTable;
use DrdPlus\Tables\Theurgist\Spells\ProfilesTable;
use DrdPlus\Tables\Theurgist\Spells\SpellTraitsTable;
use Granam\Strict\Object\StrictObject;

class Tables extends StrictObject implements \IteratorAggregate
{
    private static $tablesInstance;

    public static function getIt(): Tables
    {
        if (self::$tablesInstance === null) {
            self::$tablesInstance = new static();
        }
        return self::$tablesInstance;
    }

    protected function __construct()
    {
    }

    /** @var array|Table[] */
    private $tables = [];

    public function getAmountTable(): AmountTable
    {
        if (!\array_key_exists(AmountTable::class, $this->tables)) {
            $this->tables[AmountTable::class] = new AmountTable();
        }
        return $this->tables[AmountTable::class];
    }

    public function getBaseOfWoundsTable(): BaseOfWoundsTable
    {
        if (!\array_key_exists(BaseOfWoundsTable::class, $this->tables)) {
            $this->tables[BaseOfWoundsTable::class] = new BaseOfWoundsTable();
        }
        return $this->tables[BaseOfWoundsTable::class];
    }

    public function getDistanceTable(): DistanceTable
    {
        if (!\array_key_exists(DistanceTable::class, $this->tables)) {
            $this->tables[DistanceTable::class] = new DistanceTable();
        }
        return $this->tables[DistanceTable::class];
    }

    public function getExperiencesTable(): ExperiencesTable
    {
        if (!\array_key_exists(ExperiencesTable::class, $this->tables)) {
            $this->tables[ExperiencesTable::class] = new ExperiencesTable($this->getWoundsTable());
        }
        return $this->tables[ExperiencesTable::class];
    }

    public function getFatigueTable(): FatigueTable
    {
        if (!\array_key_exists(FatigueTable::class, $this->tables)) {
            $this->tables[FatigueTable::class] = new FatigueTable($this->getWoundsTable());
        }
        return $this->tables[FatigueTable::class];
    }

    public function getSpeedTable(): SpeedTable
    {
        if (!\array_key_exists(SpeedTable::class, $this->tables)) {
            $this->tables[SpeedTable::class] = new SpeedTable();
        }
        return $this->tables[SpeedTable::class];
    }

    public function getTimeTable(): TimeTable
    {
        if (!\array_key_exists(TimeTable::class, $this->tables)) {
            $this->tables[TimeTable::class] = new TimeTable();
        }
        return $this->tables[TimeTable::class];
    }

    public function getBonusAdjustmentByTimeTable(): BonusAdjustmentByTimeTable
    {
        if (!\array_key_exists(BonusAdjustmentByTimeTable::class, $this->tables)) {
            $this->tables[BonusAdjustmentByTimeTable::class] = new BonusAdjustmentByTimeTable($this->getTimeTable());
        }
        return $this->tables[BonusAdjustmentByTimeTable::class];
    }

    public function getWeightTable(): WeightTable
    {
        if (!\array_key_exists(WeightTable::class, $this->tables)) {
            $this->tables[WeightTable::class] = new WeightTable();
        }
        return $this->tables[WeightTable::class];
    }

    public function getWoundsTable(): WoundsTable
    {
        if (!\array_key_exists(WoundsTable::class, $this->tables)) {
            $this->tables[WoundsTable::class] = new WoundsTable();
        }
        return $this->tables[WoundsTable::class];
    }

    public function getFemaleModifiersTable(): FemaleModifiersTable
    {
        if (!\array_key_exists(FemaleModifiersTable::class, $this->tables)) {
            $this->tables[FemaleModifiersTable::class] = new FemaleModifiersTable();
        }
        return $this->tables[FemaleModifiersTable::class];
    }

    public function getRacesTable(): RacesTable
    {
        if (!\array_key_exists(RacesTable::class, $this->tables)) {
            $this->tables[RacesTable::class] = new RacesTable($this->getFemaleModifiersTable());
        }
        return $this->tables[RacesTable::class];
    }

    public function getSkillsByBackgroundPointsTable(): SkillsByBackgroundPointsTable
    {
        if (!\array_key_exists(SkillsByBackgroundPointsTable::class, $this->tables)) {
            $this->tables[SkillsByBackgroundPointsTable::class] = new SkillsByBackgroundPointsTable();
        }
        return $this->tables[SkillsByBackgroundPointsTable::class];
    }

    public function getBodyArmorsTable(): BodyArmorsTable
    {
        if (!\array_key_exists(BodyArmorsTable::class, $this->tables)) {
            $this->tables[BodyArmorsTable::class] = new BodyArmorsTable();
        }
        return $this->tables[BodyArmorsTable::class];
    }

    public function getHelmsTable(): HelmsTable
    {
        if (!\array_key_exists(HelmsTable::class, $this->tables)) {
            $this->tables[HelmsTable::class] = new HelmsTable();
        }
        return $this->tables[HelmsTable::class];
    }

    public function getArmorStrengthSanctionsTable(): ArmorStrengthSanctionsTable
    {
        if (!\array_key_exists(ArmorStrengthSanctionsTable::class, $this->tables)) {
            $this->tables[ArmorStrengthSanctionsTable::class] = new ArmorStrengthSanctionsTable();
        }
        return $this->tables[ArmorStrengthSanctionsTable::class];
    }

    public function getArmorWearingSkillTable(): ArmorWearingSkillTable
    {
        if (!\array_key_exists(ArmorWearingSkillTable::class, $this->tables)) {
            $this->tables[ArmorWearingSkillTable::class] = new ArmorWearingSkillTable();
        }
        return $this->tables[ArmorWearingSkillTable::class];
    }

    public function getMeleeWeaponStrengthSanctionsTable(): MeleeWeaponStrengthSanctionsTable
    {
        if (!\array_key_exists(MeleeWeaponStrengthSanctionsTable::class, $this->tables)) {
            $this->tables[MeleeWeaponStrengthSanctionsTable::class] = new MeleeWeaponStrengthSanctionsTable();
        }
        return $this->tables[MeleeWeaponStrengthSanctionsTable::class];
    }

    public function getRangedWeaponStrengthSanctionsTable(): RangedWeaponStrengthSanctionsTable
    {
        if (!\array_key_exists(RangedWeaponStrengthSanctionsTable::class, $this->tables)) {
            $this->tables[RangedWeaponStrengthSanctionsTable::class] = new RangedWeaponStrengthSanctionsTable();
        }
        return $this->tables[RangedWeaponStrengthSanctionsTable::class];
    }

    public function getMissingWeaponSkillTable(): MissingWeaponSkillTable
    {
        if (!\array_key_exists(MissingWeaponSkillTable::class, $this->tables)) {
            $this->tables[MissingWeaponSkillTable::class] = new MissingWeaponSkillTable();
        }
        return $this->tables[MissingWeaponSkillTable::class];
    }

    public function getShieldsTable(): ShieldsTable
    {
        if (!\array_key_exists(ShieldsTable::class, $this->tables)) {
            $this->tables[ShieldsTable::class] = new ShieldsTable();
        }
        return $this->tables[ShieldsTable::class];
    }

    public function getShieldStrengthSanctionsTable(): ShieldStrengthSanctionsTable
    {
        if (!\array_key_exists(ShieldStrengthSanctionsTable::class, $this->tables)) {
            $this->tables[ShieldStrengthSanctionsTable::class] = new ShieldStrengthSanctionsTable();
        }
        return $this->tables[ShieldStrengthSanctionsTable::class];
    }

    public function getShieldUsageSkillTable(): ShieldUsageSkillTable
    {
        if (!\array_key_exists(ShieldUsageSkillTable::class, $this->tables)) {
            $this->tables[ShieldUsageSkillTable::class] = new ShieldUsageSkillTable();
        }
        return $this->tables[ShieldUsageSkillTable::class];
    }

    public function getAxesTable(): AxesTable
    {
        if (!\array_key_exists(AxesTable::class, $this->tables)) {
            $this->tables[AxesTable::class] = new AxesTable();
        }
        return $this->tables[AxesTable::class];
    }

    public function getKnivesAndDaggersTable(): KnivesAndDaggersTable
    {
        if (!\array_key_exists(KnivesAndDaggersTable::class, $this->tables)) {
            $this->tables[KnivesAndDaggersTable::class] = new KnivesAndDaggersTable();
        }
        return $this->tables[KnivesAndDaggersTable::class];
    }

    public function getMacesAndClubsTable(): MacesAndClubsTable
    {
        if (!\array_key_exists(MacesAndClubsTable::class, $this->tables)) {
            $this->tables[MacesAndClubsTable::class] = new MacesAndClubsTable();
        }
        return $this->tables[MacesAndClubsTable::class];
    }

    public function getMorningstarsAndMorgensternsTable(): MorningstarsAndMorgensternsTable
    {
        if (!\array_key_exists(MorningstarsAndMorgensternsTable::class, $this->tables)) {
            $this->tables[MorningstarsAndMorgensternsTable::class] = new MorningstarsAndMorgensternsTable();
        }
        return $this->tables[MorningstarsAndMorgensternsTable::class];
    }

    public function getSabersAndBowieKnivesTable(): SabersAndBowieKnivesTable
    {
        if (!\array_key_exists(SabersAndBowieKnivesTable::class, $this->tables)) {
            $this->tables[SabersAndBowieKnivesTable::class] = new SabersAndBowieKnivesTable();
        }
        return $this->tables[SabersAndBowieKnivesTable::class];
    }

    public function getStaffsAndSpearsTable(): StaffsAndSpearsTable
    {
        if (!\array_key_exists(StaffsAndSpearsTable::class, $this->tables)) {
            $this->tables[StaffsAndSpearsTable::class] = new StaffsAndSpearsTable();
        }
        return $this->tables[StaffsAndSpearsTable::class];
    }

    public function getSwordsTable(): SwordsTable
    {
        if (!\array_key_exists(SwordsTable::class, $this->tables)) {
            $this->tables[SwordsTable::class] = new SwordsTable();
        }
        return $this->tables[SwordsTable::class];
    }

    public function getVoulgesAndTridentsTable(): VoulgesAndTridentsTable
    {
        if (!\array_key_exists(VoulgesAndTridentsTable::class, $this->tables)) {
            $this->tables[VoulgesAndTridentsTable::class] = new VoulgesAndTridentsTable();
        }
        return $this->tables[VoulgesAndTridentsTable::class];
    }

    public function getUnarmedTable(): UnarmedTable
    {
        if (!\array_key_exists(UnarmedTable::class, $this->tables)) {
            $this->tables[UnarmedTable::class] = new UnarmedTable();
        }
        return $this->tables[UnarmedTable::class];
    }

    public function getArrowsTable(): ArrowsTable
    {
        if (!\array_key_exists(ArrowsTable::class, $this->tables)) {
            $this->tables[ArrowsTable::class] = new ArrowsTable();
        }
        return $this->tables[ArrowsTable::class];
    }

    public function getBowsTable(): BowsTable
    {
        if (!\array_key_exists(BowsTable::class, $this->tables)) {
            $this->tables[BowsTable::class] = new BowsTable();
        }
        return $this->tables[BowsTable::class];
    }

    public function getDartsTable(): DartsTable
    {
        if (!\array_key_exists(DartsTable::class, $this->tables)) {
            $this->tables[DartsTable::class] = new DartsTable();
        }
        return $this->tables[DartsTable::class];
    }

    public function getCrossbowsTable(): CrossbowsTable
    {
        if (!\array_key_exists(CrossbowsTable::class, $this->tables)) {
            $this->tables[CrossbowsTable::class] = new CrossbowsTable();
        }
        return $this->tables[CrossbowsTable::class];
    }

    public function getSlingStonesTable(): SlingStonesTable
    {
        if (!\array_key_exists(SlingStonesTable::class, $this->tables)) {
            $this->tables[SlingStonesTable::class] = new SlingStonesTable();
        }
        return $this->tables[SlingStonesTable::class];
    }

    public function getThrowingWeaponsTable(): ThrowingWeaponsTable
    {
        if (!\array_key_exists(ThrowingWeaponsTable::class, $this->tables)) {
            $this->tables[ThrowingWeaponsTable::class] = new ThrowingWeaponsTable();
        }
        return $this->tables[ThrowingWeaponsTable::class];
    }

    public function getHealingByActivityTable(): HealingByActivityTable
    {
        if (!\array_key_exists(HealingByActivityTable::class, $this->tables)) {
            $this->tables[HealingByActivityTable::class] = new HealingByActivityTable();
        }
        return $this->tables[HealingByActivityTable::class];
    }

    public function getHealingByConditionsTable(): HealingByConditionsTable
    {
        if (!\array_key_exists(HealingByConditionsTable::class, $this->tables)) {
            $this->tables[HealingByConditionsTable::class] = new HealingByConditionsTable();
        }
        return $this->tables[HealingByConditionsTable::class];
    }

    public function getMovementTypesTable(): MovementTypesTable
    {
        if (!\array_key_exists(MovementTypesTable::class, $this->tables)) {
            $this->tables[MovementTypesTable::class] = new MovementTypesTable($this->getSpeedTable(), $this->getTimeTable());
        }
        return $this->tables[MovementTypesTable::class];
    }

    public function getImpassibilityOfTerrainTable(): ImpassibilityOfTerrainTable
    {
        if (!\array_key_exists(ImpassibilityOfTerrainTable::class, $this->tables)) {
            $this->tables[ImpassibilityOfTerrainTable::class] = new ImpassibilityOfTerrainTable();
        }
        return $this->tables[ImpassibilityOfTerrainTable::class];
    }

    public function getAttackNumberByDistanceTable(): AttackNumberByDistanceTable
    {
        if (!\array_key_exists(AttackNumberByDistanceTable::class, $this->tables)) {
            $this->tables[AttackNumberByDistanceTable::class] = new AttackNumberByDistanceTable();
        }
        return $this->tables[AttackNumberByDistanceTable::class];
    }

    public function getAttackNumberByContinuousDistanceTable(): AttackNumberByContinuousDistanceTable
    {
        if (!\array_key_exists(AttackNumberByContinuousDistanceTable::class, $this->tables)) {
            $this->tables[AttackNumberByContinuousDistanceTable::class] = new AttackNumberByContinuousDistanceTable();
        }
        return $this->tables[AttackNumberByContinuousDistanceTable::class];
    }

    public function getFatigueByLoadTable(): FatigueByLoadTable
    {
        if (!\array_key_exists(FatigueByLoadTable::class, $this->tables)) {
            $this->tables[FatigueByLoadTable::class] = new FatigueByLoadTable();
        }
        return $this->tables[FatigueByLoadTable::class];
    }

    public function getRestingBySituationTable(): RestingBySituationTable
    {
        if (!\array_key_exists(RestingBySituationTable::class, $this->tables)) {
            $this->tables[RestingBySituationTable::class] = new RestingBySituationTable();
        }
        return $this->tables[RestingBySituationTable::class];
    }

    public function getRidesByMovementTypeTable(): RidesByMovementTypeTable
    {
        if (!\array_key_exists(RidesByMovementTypeTable::class, $this->tables)) {
            $this->tables[RidesByMovementTypeTable::class] = new RidesByMovementTypeTable();
        }
        return $this->tables[RidesByMovementTypeTable::class];
    }

    public function getRidingAnimalsAndFlyingBeastsMovementTypesTable(): RidingAnimalsAndFlyingBeastsMovementTypesTable
    {
        if (!\array_key_exists(RidingAnimalsAndFlyingBeastsMovementTypesTable::class, $this->tables)) {
            $this->tables[RidingAnimalsAndFlyingBeastsMovementTypesTable::class] = new RidingAnimalsAndFlyingBeastsMovementTypesTable(
                $this->getSpeedTable(),
                $this->getMovementTypesTable()
            );
        }
        return $this->tables[RidingAnimalsAndFlyingBeastsMovementTypesTable::class];
    }

    public function getRidingAnimalsTable(): RidingAnimalsTable
    {
        if (!\array_key_exists(RidingAnimalsTable::class, $this->tables)) {
            $this->tables[RidingAnimalsTable::class] = new RidingAnimalsTable();
        }
        return $this->tables[RidingAnimalsTable::class];
    }

    public function getWoundsOnFallFromHorseTable(): WoundsOnFallFromHorseTable
    {
        if (!\array_key_exists(WoundsOnFallFromHorseTable::class, $this->tables)) {
            $this->tables[WoundsOnFallFromHorseTable::class] = new WoundsOnFallFromHorseTable();
        }
        return $this->tables[WoundsOnFallFromHorseTable::class];
    }

    public function getCombatActionsCompatibilityTable(): CombatActionsCompatibilityTable
    {
        if (!\array_key_exists(CombatActionsCompatibilityTable::class, $this->tables)) {
            $this->tables[CombatActionsCompatibilityTable::class] = new CombatActionsCompatibilityTable();
        }
        return $this->tables[CombatActionsCompatibilityTable::class];
    }

    public function getCombatActionsWithWeaponTypeCompatibilityTable(): CombatActionsWithWeaponTypeCompatibilityTable
    {
        if (!\array_key_exists(CombatActionsWithWeaponTypeCompatibilityTable::class, $this->tables)) {
            $this->tables[CombatActionsWithWeaponTypeCompatibilityTable::class] = new CombatActionsWithWeaponTypeCompatibilityTable();
        }
        return $this->tables[CombatActionsWithWeaponTypeCompatibilityTable::class];
    }

    public function getLightingQualityTable(): LightingQualityTable
    {
        if (!\array_key_exists(LightingQualityTable::class, $this->tables)) {
            $this->tables[LightingQualityTable::class] = new LightingQualityTable();
        }
        return $this->tables[LightingQualityTable::class];
    }

    public function getPowerOfLightSourcesTable(): PowerOfLightSourcesTable
    {
        if (!\array_key_exists(PowerOfLightSourcesTable::class, $this->tables)) {
            $this->tables[PowerOfLightSourcesTable::class] = new PowerOfLightSourcesTable();
        }
        return $this->tables[PowerOfLightSourcesTable::class];
    }

    public function getImprovementOfLightSourceTable(): ImprovementOfLightSourceTable
    {
        if (!\array_key_exists(ImprovementOfLightSourceTable::class, $this->tables)) {
            $this->tables[ImprovementOfLightSourceTable::class] = new ImprovementOfLightSourceTable();
        }
        return $this->tables[ImprovementOfLightSourceTable::class];
    }

    public function getPossibleActivitiesAccordingToContrastTable(): PossibleActivitiesAccordingToContrastTable
    {
        if (!\array_key_exists(PossibleActivitiesAccordingToContrastTable::class, $this->tables)) {
            $this->tables[PossibleActivitiesAccordingToContrastTable::class] = new PossibleActivitiesAccordingToContrastTable();
        }
        return $this->tables[PossibleActivitiesAccordingToContrastTable::class];
    }

    public function getSightRangesTable(): SightRangesTable
    {
        if (!\array_key_exists(SightRangesTable::class, $this->tables)) {
            $this->tables[SightRangesTable::class] = new SightRangesTable();
        }
        return $this->tables[SightRangesTable::class];
    }

    public function getProfessionPrimaryPropertiesTable(): ProfessionPrimaryPropertiesTable
    {
        if (!\array_key_exists(ProfessionPrimaryPropertiesTable::class, $this->tables)) {
            $this->tables[ProfessionPrimaryPropertiesTable::class] = new ProfessionPrimaryPropertiesTable();
        }
        return $this->tables[ProfessionPrimaryPropertiesTable::class];
    }

    public function getBackgroundPointsTable(): BackgroundPointsTable
    {
        if (!\array_key_exists(BackgroundPointsTable::class, $this->tables)) {
            $this->tables[BackgroundPointsTable::class] = new BackgroundPointsTable();
        }
        return $this->tables[BackgroundPointsTable::class];
    }

    public function getPlayerDecisionsTable(): PlayerDecisionsTable
    {
        if (!\array_key_exists(PlayerDecisionsTable::class, $this->tables)) {
            $this->tables[PlayerDecisionsTable::class] = new PlayerDecisionsTable();
        }
        return $this->tables[PlayerDecisionsTable::class];
    }

    public function getInfluenceOfFortuneTable(): InfluenceOfFortuneTable
    {
        if (!\array_key_exists(InfluenceOfFortuneTable::class, $this->tables)) {
            $this->tables[InfluenceOfFortuneTable::class] = new InfluenceOfFortuneTable();
        }
        return $this->tables[InfluenceOfFortuneTable::class];
    }

    public function getAncestryTable(): AncestryTable
    {
        if (!\array_key_exists(AncestryTable::class, $this->tables)) {
            $this->tables[AncestryTable::class] = new AncestryTable();
        }
        return $this->tables[AncestryTable::class];
    }

    public function getBackgroundPointsDistributionTable(): BackgroundPointsDistributionTable
    {
        if (!\array_key_exists(BackgroundPointsDistributionTable::class, $this->tables)) {
            $this->tables[BackgroundPointsDistributionTable::class] = new BackgroundPointsDistributionTable();
        }
        return $this->tables[BackgroundPointsDistributionTable::class];
    }

    public function getPossessionTable(): PossessionTable
    {
        if (!\array_key_exists(PossessionTable::class, $this->tables)) {
            $this->tables[PossessionTable::class] = new PossessionTable();
        }
        return $this->tables[PossessionTable::class];
    }

    public function getCorrectionByHeightTable(): CorrectionByHeightTable
    {
        if (!\array_key_exists(CorrectionByHeightTable::class, $this->tables)) {
            $this->tables[CorrectionByHeightTable::class] = new CorrectionByHeightTable();
        }
        return $this->tables[CorrectionByHeightTable::class];
    }

    public function getCombatCharacteristicsTable(): CombatCharacteristicsTable
    {
        if (!\array_key_exists(CombatCharacteristicsTable::class, $this->tables)) {
            $this->tables[CombatCharacteristicsTable::class] = new CombatCharacteristicsTable();
        }
        return $this->tables[CombatCharacteristicsTable::class];
    }

    public function getFightTable(): FightTable
    {
        if (!\array_key_exists(FightTable::class, $this->tables)) {
            $this->tables[FightTable::class] = new FightTable();
        }
        return $this->tables[FightTable::class];
    }

    public function getWoundAndFatigueBoundariesTable(): WoundAndFatigueBoundariesTable
    {
        if (!\array_key_exists(WoundAndFatigueBoundariesTable::class, $this->tables)) {
            $this->tables[WoundAndFatigueBoundariesTable::class] = new WoundAndFatigueBoundariesTable();
        }
        return $this->tables[WoundAndFatigueBoundariesTable::class];
    }

    public function getAspectsOfVisageTable(): AspectsOfVisageTable
    {
        if (!\array_key_exists(AspectsOfVisageTable::class, $this->tables)) {
            $this->tables[AspectsOfVisageTable::class] = new AspectsOfVisageTable();
        }
        return $this->tables[AspectsOfVisageTable::class];
    }

    public function getLandingSurfacesTable(): LandingSurfacesTable
    {
        if (!\array_key_exists(LandingSurfacesTable::class, $this->tables)) {
            $this->tables[LandingSurfacesTable::class] = new LandingSurfacesTable();
        }
        return $this->tables[LandingSurfacesTable::class];
    }

    public function getJumpsAndFallsTable(): JumpsAndFallsTable
    {
        if (!\array_key_exists(JumpsAndFallsTable::class, $this->tables)) {
            $this->tables[JumpsAndFallsTable::class] = new JumpsAndFallsTable();
        }
        return $this->tables[JumpsAndFallsTable::class];
    }

    public function getMalusesToAutomaticSearchingTable(): MalusesToAutomaticSearchingTable
    {
        if (!\array_key_exists(MalusesToAutomaticSearchingTable::class, $this->tables)) {
            $this->tables[MalusesToAutomaticSearchingTable::class] = new MalusesToAutomaticSearchingTable();
        }
        return $this->tables[MalusesToAutomaticSearchingTable::class];
    }

    public function getStealthinessTable(): StealthinessTable
    {
        if (!\array_key_exists(StealthinessTable::class, $this->tables)) {
            $this->tables[StealthinessTable::class] = new StealthinessTable();
        }
        return $this->tables[StealthinessTable::class];
    }

    public function getCatchQualitiesTable(): CatchQualitiesTable
    {
        if (!\array_key_exists(CatchQualitiesTable::class, $this->tables)) {
            $this->tables[CatchQualitiesTable::class] = new CatchQualitiesTable();
        }
        return $this->tables[CatchQualitiesTable::class];
    }

    public function getMaterialResistancesTable(): MaterialResistancesTable
    {
        if (!\array_key_exists(MaterialResistancesTable::class, $this->tables)) {
            $this->tables[MaterialResistancesTable::class] = new MaterialResistancesTable();
        }
        return $this->tables[MaterialResistancesTable::class];
    }

    public function getIterator(): \ArrayObject
    {
        return new \ArrayObject([
            $this->getAmountTable(),
            $this->getSkillsByBackgroundPointsTable(),
            $this->getBaseOfWoundsTable(),
            $this->getDistanceTable(),
            $this->getExperiencesTable(),
            $this->getFatigueTable(),
            $this->getFemaleModifiersTable(),
            $this->getRacesTable(),
            $this->getSpeedTable(),
            $this->getTimeTable(),
            $this->getBonusAdjustmentByTimeTable(),
            $this->getWeightTable(),
            $this->getWoundsTable(),
            $this->getBodyArmorsTable(),
            $this->getHelmsTable(),
            $this->getArmorStrengthSanctionsTable(),
            $this->getArmorWearingSkillTable(),
            $this->getShieldsTable(),
            $this->getShieldUsageSkillTable(),
            $this->getAxesTable(),
            $this->getKnivesAndDaggersTable(),
            $this->getMacesAndClubsTable(),
            $this->getMorningstarsAndMorgensternsTable(),
            $this->getSabersAndBowieKnivesTable(),
            $this->getStaffsAndSpearsTable(),
            $this->getSwordsTable(),
            $this->getVoulgesAndTridentsTable(),
            $this->getUnarmedTable(),
            $this->getArrowsTable(),
            $this->getBowsTable(),
            $this->getDartsTable(),
            $this->getCrossbowsTable(),
            $this->getSlingStonesTable(),
            $this->getThrowingWeaponsTable(),
            $this->getMeleeWeaponStrengthSanctionsTable(),
            $this->getShieldStrengthSanctionsTable(),
            $this->getRangedWeaponStrengthSanctionsTable(),
            $this->getMissingWeaponSkillTable(),
            $this->getHealingByActivityTable(),
            $this->getHealingByConditionsTable(),
            $this->getMovementTypesTable(),
            $this->getImpassibilityOfTerrainTable(),
            $this->getAttackNumberByDistanceTable(),
            $this->getAttackNumberByContinuousDistanceTable(),
            $this->getFatigueByLoadTable(),
            $this->getRestingBySituationTable(),
            $this->getRidesByMovementTypeTable(),
            $this->getRidingAnimalsAndFlyingBeastsMovementTypesTable(),
            $this->getRidingAnimalsTable(),
            $this->getWoundsOnFallFromHorseTable(),
            $this->getCombatActionsCompatibilityTable(),
            $this->getCombatActionsWithWeaponTypeCompatibilityTable(),
            $this->getLightingQualityTable(),
            $this->getPowerOfLightSourcesTable(),
            $this->getImprovementOfLightSourceTable(),
            $this->getPossibleActivitiesAccordingToContrastTable(),
            $this->getSightRangesTable(),
            $this->getProfessionPrimaryPropertiesTable(),
            $this->getBackgroundPointsTable(),
            $this->getPlayerDecisionsTable(),
            $this->getInfluenceOfFortuneTable(),
            $this->getAncestryTable(),
            $this->getBackgroundPointsDistributionTable(),
            $this->getPossessionTable(),
            $this->getCorrectionByHeightTable(),
            $this->getCombatCharacteristicsTable(),
            $this->getFightTable(),
            $this->getWoundAndFatigueBoundariesTable(),
            $this->getAspectsOfVisageTable(),
            $this->getLandingSurfacesTable(),
            $this->getJumpsAndFallsTable(),
            $this->getMalusesToAutomaticSearchingTable(),
            $this->getStealthinessTable(),
            $this->getCatchQualitiesTable(),
            $this->getMaterialResistancesTable(),
            $this->getSpellTraitsTable(),
            $this->getFormulasTable(),
            $this->getModifiersTable(),
            $this->getProfilesTable(),
            $this->getDemonsTable(),
            $this->getDemonTraitsTable(),
        ]);
    }

    /**
     * @param ArmamentCode $armamentCode
     * @return WeaponlikeTable|AbstractArmorsTable|ProjectilesTable
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownArmament
     */
    public function getArmamentsTableByArmamentCode(ArmamentCode $armamentCode)
    {
        if ($armamentCode instanceof WeaponlikeCode) {
            return $this->getWeaponlikeTableByWeaponlikeCode($armamentCode);
        }
        if ($armamentCode instanceof ArmorCode) {
            return $this->getArmorsTableByArmorCode($armamentCode);
        }
        if ($armamentCode instanceof ProjectileCode) {
            return $this->getProjectilesTableByProjectiveCode($armamentCode);
        }
        throw new UnknownArmament("Unknown type of armament '{$armamentCode}'");
    }

    /**
     * @param WeaponlikeCode $weaponlikeCode
     * @return WeaponlikeTable
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     */
    public function getWeaponlikeTableByWeaponlikeCode(WeaponlikeCode $weaponlikeCode): WeaponlikeTable
    {
        if ($weaponlikeCode instanceof RangedWeaponCode) {
            return $this->getRangedWeaponsTableByRangedWeaponCode($weaponlikeCode);
        }
        if ($weaponlikeCode instanceof MeleeWeaponlikeCode) {
            return $this->getMeleeWeaponlikeTableByMeleeWeaponlikeCode($weaponlikeCode);
        }
        throw new UnknownWeaponlike("Unknown type of weapon-like '{$weaponlikeCode}'");
    }

    /**
     * @param MeleeWeaponlikeCode $meleeWeaponlikeCode
     * @return MeleeWeaponlikesTable
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownMeleeWeaponlike
     */
    public function getMeleeWeaponlikeTableByMeleeWeaponlikeCode(MeleeWeaponlikeCode $meleeWeaponlikeCode): MeleeWeaponlikesTable
    {
        if ($meleeWeaponlikeCode instanceof MeleeWeaponCode) {
            return $this->getMeleeWeaponsTableByMeleeWeaponCode($meleeWeaponlikeCode);
        }
        if ($meleeWeaponlikeCode instanceof ShieldCode) {
            return $this->getShieldsTable();
        }
        throw new UnknownMeleeWeaponlike("Unknown type of melee weapon-like '{$meleeWeaponlikeCode}'");
    }

    /**
     * @param MeleeWeaponCode $meleeWeaponCode
     * @return MeleeWeaponsTable
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownMeleeWeapon
     */
    public function getMeleeWeaponsTableByMeleeWeaponCode(MeleeWeaponCode $meleeWeaponCode): MeleeWeaponsTable
    {
        if ($meleeWeaponCode->isAxe()) {
            return $this->getAxesTable();
        }
        if ($meleeWeaponCode->isKnifeOrDagger()) {
            return $this->getKnivesAndDaggersTable();
        }
        if ($meleeWeaponCode->isMaceOrClub()) {
            return $this->getMacesAndClubsTable();
        }
        if ($meleeWeaponCode->isMorningstarOrMorgenstern()) {
            return $this->getMorningstarsAndMorgensternsTable();
        }
        if ($meleeWeaponCode->isSaberOrBowieKnife()) {
            return $this->getSabersAndBowieKnivesTable();
        }
        if ($meleeWeaponCode->isStaffOrSpear()) {
            return $this->getStaffsAndSpearsTable();
        }
        if ($meleeWeaponCode->isSword()) {
            return $this->getSwordsTable();
        }
        if ($meleeWeaponCode->isUnarmed()) {
            return $this->getUnarmedTable();
        }
        if ($meleeWeaponCode->isVoulgeOrTrident()) {
            return $this->getVoulgesAndTridentsTable();
        }
        throw new UnknownMeleeWeapon("Unknown type of melee weapon '{$meleeWeaponCode}'");
    }

    /**
     * @param RangedWeaponCode $rangeWeaponCode
     * @return BowsTable|CrossbowsTable|ThrowingWeaponsTable
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownRangedWeapon
     */
    public function getRangedWeaponsTableByRangedWeaponCode(RangedWeaponCode $rangeWeaponCode)
    {
        if ($rangeWeaponCode->isBow()) {
            return $this->getBowsTable();
        }
        if ($rangeWeaponCode->isCrossbow()) {
            return $this->getCrossbowsTable();
        }

        if ($rangeWeaponCode->isThrowingWeapon()) {
            return $this->getThrowingWeaponsTable();
        }
        throw new UnknownRangedWeapon("Unknown type of range weapon '{$rangeWeaponCode}'");
    }

    /**
     * @param ProjectileCode $projectileCode
     * @return ArrowsTable|DartsTable|SlingStonesTable
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownProjectile
     */
    public function getProjectilesTableByProjectiveCode(ProjectileCode $projectileCode)
    {
        if ($projectileCode->isArrow()) {
            return $this->getArrowsTable();
        }
        if ($projectileCode->isDart()) {
            return $this->getDartsTable();
        }
        if ($projectileCode->isSlingStone()) {
            return $this->getSlingStonesTable();
        }
        throw new UnknownProjectile("Unknown type of projectile '{$projectileCode}'");
    }

    /**
     * @param ArmorCode $armorCode
     * @return AbstractArmorsTable
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownArmor
     */
    public function getArmorsTableByArmorCode(ArmorCode $armorCode): AbstractArmorsTable
    {
        if ($armorCode instanceof BodyArmorCode) {
            return $this->getBodyArmorsTable();
        }
        if ($armorCode instanceof HelmCode) {
            return $this->getHelmsTable();
        }

        throw new UnknownArmor("Unknown type of armor '{$armorCode}'");
    }

    /**
     * @param ArmamentCode $armamentCode
     * @return StrengthSanctionsInterface
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownArmament
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownMeleeWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     */
    public function getArmamentStrengthSanctionsTableByCode(ArmamentCode $armamentCode): StrengthSanctionsInterface
    {
        if ($armamentCode instanceof ArmorCode) {
            return $this->getArmorStrengthSanctionsTable();
        }
        if ($armamentCode instanceof WeaponlikeCode) {
            return $this->getWeaponlikeStrengthSanctionsTableByCode($armamentCode);
        }

        throw new UnknownArmament("Unknown type of armament '{$armamentCode}'");
    }

    /**
     * @param WeaponlikeCode $weaponlikeCode
     * @return WeaponStrengthSanctionsInterface
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownMeleeWeaponlike
     */
    public function getWeaponlikeStrengthSanctionsTableByCode(WeaponlikeCode $weaponlikeCode): WeaponStrengthSanctionsInterface
    {
        if ($weaponlikeCode instanceof RangedWeaponCode) {
            return $this->getRangedWeaponStrengthSanctionsTable();
        }
        if ($weaponlikeCode instanceof MeleeWeaponlikeCode) {
            return $this->getMeleeWeaponlikeStrengthSanctionsTableByCode($weaponlikeCode);
        }

        throw new UnknownWeaponlike("Unknown type of weapon '{$weaponlikeCode}'");
    }

    /**
     * @param MeleeWeaponlikeCode $meleeWeaponlikeCode
     * @return AbstractMeleeWeaponlikeStrengthSanctionsTable
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownMeleeWeaponlike
     */
    public function getMeleeWeaponlikeStrengthSanctionsTableByCode(MeleeWeaponlikeCode $meleeWeaponlikeCode): AbstractMeleeWeaponlikeStrengthSanctionsTable
    {
        if ($meleeWeaponlikeCode instanceof MeleeWeaponCode) {
            return $this->getMeleeWeaponStrengthSanctionsTable();
        }
        if ($meleeWeaponlikeCode instanceof ShieldCode) {
            return $this->getShieldStrengthSanctionsTable();
        }

        throw new UnknownMeleeWeaponlike("Unknown type of melee armament '{$meleeWeaponlikeCode}'");
    }

    /**
     * @param ProtectiveArmamentCode $protectiveArmamentCode
     * @return MissingProtectiveArmamentSkill
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownProtectiveArmament
     */
    public function getProtectiveArmamentMissingSkillTableByCode(ProtectiveArmamentCode $protectiveArmamentCode): MissingProtectiveArmamentSkill
    {
        if ($protectiveArmamentCode instanceof ArmorCode) {
            return $this->getArmorWearingSkillTable();
        }
        if ($protectiveArmamentCode instanceof ShieldCode) {
            return $this->getShieldUsageSkillTable();
        }
        throw new UnknownProtectiveArmament("Unknown type of protective armament {$protectiveArmamentCode}");
    }

    /**
     * @param ProtectiveArmamentCode $protectiveArmamentCode
     * @return UnwieldyTable
     * @throws \DrdPlus\Tables\Armaments\Exceptions\UnknownProtectiveArmament
     */
    public function getProtectiveArmamentsTable(ProtectiveArmamentCode $protectiveArmamentCode): UnwieldyTable
    {
        if ($protectiveArmamentCode instanceof BodyArmorCode) {
            return $this->getBodyArmorsTable();
        }
        if ($protectiveArmamentCode instanceof HelmCode) {
            return $this->getHelmsTable();
        }
        if ($protectiveArmamentCode instanceof ShieldCode) {
            return $this->getShieldsTable();
        }
        throw new UnknownProtectiveArmament("Unknown type of protective armament {$protectiveArmamentCode}");
    }

    public function getSpellTraitsTable(): SpellTraitsTable
    {
        if (!\array_key_exists(SpellTraitsTable::class, $this->tables)) {
            $this->tables[SpellTraitsTable::class] = new SpellTraitsTable($this);
        }
        return $this->tables[SpellTraitsTable::class];
    }

    public function getFormulasTable(): FormulasTable
    {
        if (!\array_key_exists(FormulasTable::class, $this->tables)) {
            $this->tables[FormulasTable::class] = new FormulasTable($this);
        }
        return $this->tables[FormulasTable::class];
    }

    public function getModifiersTable(): ModifiersTable
    {
        if (!\array_key_exists(ModifiersTable::class, $this->tables)) {
            $this->tables[ModifiersTable::class] = new ModifiersTable($this);
        }
        return $this->tables[ModifiersTable::class];
    }

    public function getProfilesTable(): ProfilesTable
    {
        if (!\array_key_exists(ProfilesTable::class, $this->tables)) {
            $this->tables[ProfilesTable::class] = new ProfilesTable();
        }
        return $this->tables[ProfilesTable::class];
    }

    public function getDemonsTable(): DemonsTable
    {
        if (!\array_key_exists(DemonsTable::class, $this->tables)) {
            $this->tables[DemonsTable::class] = new DemonsTable($this);
        }
        return $this->tables[DemonsTable::class];
    }

    public function getDemonTraitsTable(): DemonTraitsTable
    {
        if (!\array_key_exists(DemonTraitsTable::class, $this->tables)) {
            $this->tables[DemonTraitsTable::class] = new DemonTraitsTable($this);
        }
        return $this->tables[DemonTraitsTable::class];
    }

}