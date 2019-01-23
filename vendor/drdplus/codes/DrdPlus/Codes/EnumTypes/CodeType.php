<?php
declare(strict_types=1); 

namespace DrdPlus\Codes\EnumTypes;

use DrdPlus\Codes\ActivityIntensityCode;
use DrdPlus\Codes\Armaments\ArrowCode;
use DrdPlus\Codes\Armaments\BodyArmorCode;
use DrdPlus\Codes\Armaments\DartCode;
use DrdPlus\Codes\Armaments\HelmCode;
use DrdPlus\Codes\Armaments\MeleeWeaponCode;
use DrdPlus\Codes\Armaments\RangedWeaponCode;
use DrdPlus\Codes\Armaments\ShieldCode;
use DrdPlus\Codes\Armaments\SlingStoneCode;
use DrdPlus\Codes\Armaments\WeaponCategoryCode;
use DrdPlus\Codes\Body\ActivityAffectingHealingCode;
use DrdPlus\Codes\Body\AfflictionByWoundDomainCode;
use DrdPlus\Codes\Body\ConditionsAffectingHealingCode;
use DrdPlus\Codes\Body\HealingAffectingActivityCode;
use DrdPlus\Codes\Body\OrdinaryWoundOriginCode;
use DrdPlus\Codes\Body\RestConditionsCode;
use DrdPlus\Codes\Body\PhysicalWoundTypeCode;
use DrdPlus\Codes\Body\SeriousWoundOriginCode;
use DrdPlus\Codes\CombatActions\CombatActionCode;
use DrdPlus\Codes\CombatActions\MeleeCombatActionCode;
use DrdPlus\Codes\CombatActions\RangedCombatActionCode;
use DrdPlus\Codes\CombatCharacteristicCode;
use DrdPlus\Codes\Theurgist\AffectionPeriodCode;
use DrdPlus\Codes\Theurgist\FormCode;
use DrdPlus\Codes\Theurgist\FormulaCode;
use DrdPlus\Codes\Theurgist\FormulaMutableSpellParameterCode;
use DrdPlus\Codes\Theurgist\ModifierCode;
use DrdPlus\Codes\Theurgist\ModifierMutableSpellParameterCode;
use DrdPlus\Codes\Theurgist\ProfileCode;
use DrdPlus\Codes\Theurgist\SpellTraitCode;
use DrdPlus\Codes\Units\DistanceUnitCode;
use DrdPlus\Codes\ElementCode;
use DrdPlus\Codes\Environment\ItemStealthinessCode;
use DrdPlus\Codes\Environment\LandingSurfaceCode;
use DrdPlus\Codes\Environment\LightSourceCode;
use DrdPlus\Codes\Environment\LightSourceEnvironmentCode;
use DrdPlus\Codes\Environment\MaterialCode;
use DrdPlus\Codes\FoodTypeCode;
use DrdPlus\Codes\GenderCode;
use DrdPlus\Codes\History\AncestryCode;
use DrdPlus\Codes\History\ExceptionalityCode;
use DrdPlus\Codes\ItemHoldingCode;
use DrdPlus\Codes\Environment\LightConditionsCode;
use DrdPlus\Codes\JumpMovementCode;
use DrdPlus\Codes\JumpTypeCode;
use DrdPlus\Codes\ProfessionCode;
use DrdPlus\Codes\Properties\CharacteristicForGameCode;
use DrdPlus\Codes\Properties\PropertyCode;
use DrdPlus\Codes\Properties\RemarkableSenseCode;
use DrdPlus\Codes\RaceCode;
use DrdPlus\Codes\SearchingItemTypeCode;
use DrdPlus\Codes\Units\SpeedUnitCode;
use DrdPlus\Codes\Transport\MovementTypeCode;
use DrdPlus\Codes\Transport\RidingAnimalCode;
use DrdPlus\Codes\Transport\RidingAnimalMovementCode;
use DrdPlus\Codes\Transport\RidingAnimalPropertyCode;
use DrdPlus\Codes\Skills\CombinedSkillCode;
use DrdPlus\Codes\Skills\PhysicalSkillCode;
use DrdPlus\Codes\Skills\PsychicalSkillCode;
use DrdPlus\Codes\Skills\SkillTypeCode;
use DrdPlus\Codes\SubRaceCode;
use DrdPlus\Codes\Environment\TerrainCode;
use DrdPlus\Codes\History\ChoiceCode;
use DrdPlus\Codes\History\FateCode;
use DrdPlus\Codes\Units\SquareUnitCode;
use DrdPlus\Codes\Units\TimeUnitCode;
use DrdPlus\Codes\Units\VolumeUnitCode;
use DrdPlus\Codes\Units\WeightUnitCode;
use DrdPlus\Codes\Wizard\SpellCode;

class CodeType extends AbstractCodeType
{
    public const CODE = 'code';

    public static function registerSelf(): bool
    {
        $somethingRegistered = parent::registerSelf();
        // ARMAMENTS
        $somethingRegistered = static::registerCodeAsSubTypeEnum(ArrowCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(BodyArmorCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(DartCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(HelmCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(MeleeWeaponCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(RangedWeaponCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(ShieldCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(SlingStoneCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(WeaponCategoryCode::class) || $somethingRegistered;
        // BODY
        $somethingRegistered = static::registerCodeAsSubTypeEnum(ActivityAffectingHealingCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(AfflictionByWoundDomainCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(ConditionsAffectingHealingCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(RestConditionsCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(SeriousWoundOriginCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(OrdinaryWoundOriginCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(PhysicalWoundTypeCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(HealingAffectingActivityCode::class) || $somethingRegistered;
        // COMBAT ACTIONS
        $somethingRegistered = static::registerCodeAsSubTypeEnum(CombatActionCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(MeleeCombatActionCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(RangedCombatActionCode::class) || $somethingRegistered;
        // ENVIRONMENT
        $somethingRegistered = static::registerCodeAsSubTypeEnum(ItemStealthinessCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(LandingSurfaceCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(LightConditionsCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(LightSourceCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(LightSourceEnvironmentCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(MaterialCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(TerrainCode::class) || $somethingRegistered;
        // HISTORY
        $somethingRegistered = static::registerCodeAsSubTypeEnum(AncestryCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(ExceptionalityCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(ChoiceCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(FateCode::class) || $somethingRegistered;
        // PROPERTIES
        $somethingRegistered = static::registerCodeAsSubTypeEnum(CharacteristicForGameCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(CombatCharacteristicCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(PropertyCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(RemarkableSenseCode::class) || $somethingRegistered;
        // SKILL
        $somethingRegistered = static::registerCodeAsSubTypeEnum(CombinedSkillCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(PhysicalSkillCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(PsychicalSkillCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(SkillTypeCode::class) || $somethingRegistered;
        // TRANSPORT
        $somethingRegistered = static::registerCodeAsSubTypeEnum(MovementTypeCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(RidingAnimalCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(RidingAnimalMovementCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(RidingAnimalPropertyCode::class) || $somethingRegistered;
        // UNIT
        $somethingRegistered = static::registerCodeAsSubTypeEnum(DistanceUnitCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(SpeedUnitCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(TimeUnitCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(WeightUnitCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(SquareUnitCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(VolumeUnitCode::class) || $somethingRegistered;
        // WIZARD
        $somethingRegistered = static::registerCodeAsSubTypeEnum(SpellCode::class) || $somethingRegistered;
        // THEURGIST
        $somethingRegistered = static::registerCodeAsSubTypeEnum(AffectionPeriodCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(FormCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(FormulaCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(FormulaMutableSpellParameterCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(ModifierCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(ModifierMutableSpellParameterCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(ProfileCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(SpellTraitCode::class) || $somethingRegistered;
        // OTHER
        $somethingRegistered = static::registerCodeAsSubTypeEnum(ActivityIntensityCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(CombatCharacteristicCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(ElementCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(FoodTypeCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(GenderCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(ItemHoldingCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(JumpMovementCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(JumpTypeCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(ProfessionCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(RaceCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(SearchingItemTypeCode::class) || $somethingRegistered;
        $somethingRegistered = static::registerCodeAsSubTypeEnum(SubRaceCode::class) || $somethingRegistered;

        return $somethingRegistered;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::CODE;
    }
}