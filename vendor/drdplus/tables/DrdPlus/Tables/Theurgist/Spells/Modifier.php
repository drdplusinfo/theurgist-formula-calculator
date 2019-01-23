<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Theurgist\Spells;

use DrdPlus\Codes\Theurgist\ModifierCode;
use DrdPlus\Codes\Theurgist\ModifierMutableSpellParameterCode;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Attack;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\CastingRounds;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Noise;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\NumberOfConditions;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\DifficultyChange;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\EpicenterShift;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Grafts;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Invisibility;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\NumberOfSituations;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameter;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\NumberOfWaypoints;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Power;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Quality;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Radius;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Realm;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\RealmsAffection;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Resistance;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellSpeed;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Threshold;
use Granam\Integer\Tools\ToInteger;
use Granam\Strict\Object\StrictObject;
use Granam\String\StringTools;
use Granam\Tools\ValueDescriber;

class Modifier extends StrictObject
{
    use ToFlatArrayTrait;

    /** @var ModifierCode */
    private $modifierCode;
    /** @var ModifiersTable */
    private $modifiersTable;
    /** @var array|int[] */
    private $modifierSpellParameterChanges;
    /** @var array|SpellTrait[] */
    private $modifierSpellTraits;

    /**
     * @param ModifierCode $modifierCode
     * @param ModifiersTable $modifiersTable
     * @param array|int[] $modifierSpellParameterValues spell parameters current values (delta will be calculated from them)
     * by @see ModifierMutableSpellParameterCode value indexed its value change
     * @param array|SpellTrait[] $modifierSpellTraits
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\UselessValueForUnusedSpellParameter
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\UnknownModifierParameter
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\InvalidValueForModifierParameter
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\InvalidSpellTrait
     */
    public function __construct(
        ModifierCode $modifierCode,
        ModifiersTable $modifiersTable,
        array $modifierSpellParameterValues,
        array $modifierSpellTraits
    )
    {
        $this->modifierCode = $modifierCode;
        $this->modifiersTable = $modifiersTable;
        $this->modifierSpellParameterChanges = $this->sanitizeSpellParameterChanges($modifierSpellParameterValues);
        $this->modifierSpellTraits = $this->getCheckedSpellTraits($this->toFlatArray($modifierSpellTraits));
    }

    /**
     * @param array $spellParameterValues
     * @return array
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\UselessValueForUnusedSpellParameter
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\InvalidValueForModifierParameter
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\UnknownModifierParameter
     */
    private function sanitizeSpellParameterChanges(array $spellParameterValues): array
    {
        $sanitizedChanges = [];
        foreach (ModifierMutableSpellParameterCode::getPossibleValues() as $mutableSpellParameter) {
            if (!array_key_exists($mutableSpellParameter, $spellParameterValues)) {
                $sanitizedChanges[$mutableSpellParameter] = 0; // no change
                continue;
            }
            try {
                $sanitizedValue = ToInteger::toInteger($spellParameterValues[$mutableSpellParameter]);
            } catch (\Granam\Integer\Tools\Exceptions\Exception $exception) {
                throw new Exceptions\InvalidValueForModifierParameter(
                    'Expected integer, got ' . ValueDescriber::describe($spellParameterValues[$mutableSpellParameter])
                    . ' for ' . $mutableSpellParameter . ": '{$exception->getMessage()}'"
                );
            }
            /** like @see getBaseAttack */
            $getBaseParameter = StringTools::assembleGetterForName('base_' . $mutableSpellParameter);
            /** @var CastingParameter $baseParameter */
            $baseParameter = $this->$getBaseParameter();
            if ($baseParameter === null) {
                throw new Exceptions\UselessValueForUnusedSpellParameter(
                    "Casting parameter {$mutableSpellParameter} is not used for modifier {$this->modifierCode}"
                    . ', so given spell parameter value ' . ValueDescriber::describe($spellParameterValues[$mutableSpellParameter])
                    . ' is thrown away'
                );
            }
            $parameterChange = $sanitizedValue - $baseParameter->getDefaultValue();
            $sanitizedChanges[$mutableSpellParameter] = $parameterChange;

            unset($spellParameterValues[$mutableSpellParameter]);
        }
        if (\count($spellParameterValues) > 0) { // there are some remains
            throw new Exceptions\UnknownModifierParameter(
                'Unexpected mutable spell parameter(s) [' . implode(', ', array_keys($spellParameterValues)) . ']. Expected only '
                . implode(', ', ModifierMutableSpellParameterCode::getPossibleValues())
            );
        }

        return $sanitizedChanges;
    }

    /**
     * @param array $spellTraits
     * @return array|SpellTrait[]
     * @throws \DrdPlus\Tables\Theurgist\Spells\Exceptions\InvalidSpellTrait
     */
    private function getCheckedSpellTraits(array $spellTraits): array
    {
        foreach ($spellTraits as $spellTrait) {
            if (!is_a($spellTrait, SpellTrait::class)) {
                throw new Exceptions\InvalidSpellTrait(
                    'Expected instance of ' . static::class . ', got ' . ValueDescriber::describe($spellTrait)
                );
            }
        }

        return $spellTraits;
    }

    /**
     * @return ModifierCode
     */
    public function getModifierCode(): ModifierCode
    {
        return $this->modifierCode;
    }

    public function getDifficultyChange(): DifficultyChange
    {
        $modifierParameters = [
            $this->getAttackWithAddition(),
            $this->getNumberOfConditionsWithAddition(),
            $this->getEpicenterShiftWithAddition(),
            $this->getGraftsWithAddition(),
            $this->getInvisibilityWithAddition(),
            $this->getNumberOfSituationsWithAddition(),
            $this->getNumberOfWaypointsWithAddition(),
            $this->getPowerWithAddition(),
            $this->getNoiseWithAddition(),
            $this->getQualityWithAddition(),
            $this->getRadiusWithAddition(),
            $this->getResistanceWithAddition(),
            $this->getSpellSpeedWithAddition(),
            $this->getThresholdWithAddition(),
        ];
        $modifierParameters = array_filter(
            $modifierParameters,
            function (CastingParameter $modifierParameter = null) {
                return $modifierParameter !== null;
            }
        );
        $parametersDifficultyChangeSum = 0;
        /** @var CastingParameter $parameter */
        foreach ($modifierParameters as $parameter) {
            $parametersDifficultyChangeSum += $parameter->getAdditionByDifficulty()->getCurrentDifficultyIncrement();
        }
        $spellTraitsDifficultyChangeSum = 0;
        foreach ($this->modifierSpellTraits as $spellTrait) {
            $spellTraitsDifficultyChangeSum += $spellTrait->getDifficultyChange()->getValue();
        }
        $difficultyChange = $this->modifiersTable->getDifficultyChange($this->getModifierCode());

        return $difficultyChange->add($parametersDifficultyChangeSum + $spellTraitsDifficultyChangeSum);
    }

    public function getCastingRounds(): CastingRounds
    {
        return $this->modifiersTable->getCastingRounds($this->getModifierCode());
    }

    public function getRequiredRealm(): Realm
    {
        return $this->modifiersTable->getRealm($this->getModifierCode());
    }

    public function getRealmsAffection(): ?RealmsAffection
    {
        return $this->modifiersTable->getRealmsAffection($this->getModifierCode());
    }

    public function getBaseRadius(): ?Radius
    {
        return $this->modifiersTable->getRadius($this->modifierCode);
    }

    public function getRadiusWithAddition(): ?Radius
    {
        $baseRadius = $this->getBaseRadius();
        if ($baseRadius === null) {
            return null;
        }

        return $baseRadius->getWithAddition($this->getRadiusAddition());
    }

    public function getRadiusAddition(): int
    {
        return $this->modifierSpellParameterChanges[ModifierMutableSpellParameterCode::RADIUS];
    }

    /**
     * @return EpicenterShift|null
     */
    public function getBaseEpicenterShift(): ?EpicenterShift
    {
        return $this->modifiersTable->getEpicenterShift($this->modifierCode);
    }

    public function getEpicenterShiftWithAddition(): ?EpicenterShift
    {
        $baseEpicenterShift = $this->getBaseEpicenterShift();
        if ($baseEpicenterShift === null) {
            return null;
        }

        return $baseEpicenterShift->getWithAddition($this->getEpicenterShiftAddition());
    }

    public function getEpicenterShiftAddition(): int
    {
        return $this->modifierSpellParameterChanges[ModifierMutableSpellParameterCode::EPICENTER_SHIFT];
    }

    /**
     * @return Power|null
     */
    public function getBasePower(): ?Power
    {
        return $this->modifiersTable->getPower($this->modifierCode);
    }

    public function getPowerWithAddition(): ?Power
    {
        $basePower = $this->getBasePower();
        if ($basePower === null) {
            return null;
        }

        return $basePower->getWithAddition($this->getPowerAddition());
    }

    public function getPowerAddition(): int
    {
        return $this->modifierSpellParameterChanges[ModifierMutableSpellParameterCode::POWER];
    }

    /**
     * @return Noise|null
     */
    public function getBaseNoise(): ?Noise
    {
        return $this->modifiersTable->getNoise($this->modifierCode);
    }

    public function getNoiseWithAddition(): ?Noise
    {
        $baseNoise = $this->getBaseNoise();
        if ($baseNoise === null) {
            return null;
        }

        return $baseNoise->getWithAddition($this->getNoiseAddition());
    }

    public function getNoiseAddition(): int
    {
        return $this->modifierSpellParameterChanges[ModifierMutableSpellParameterCode::NOISE];
    }

    public function getBaseAttack(): ?Attack
    {
        return $this->modifiersTable->getAttack($this->modifierCode);
    }

    public function getAttackWithAddition(): ?Attack
    {
        $baseAttack = $this->getBaseAttack();
        if ($baseAttack === null) {
            return null;
        }

        return $baseAttack->getWithAddition($this->getAttackAddition());
    }

    public function getAttackAddition(): int
    {
        return $this->modifierSpellParameterChanges[ModifierMutableSpellParameterCode::ATTACK];
    }

    public function getBaseGrafts(): ?Grafts
    {
        return $this->modifiersTable->getGrafts($this->modifierCode);
    }

    public function getGraftsWithAddition(): ?Grafts
    {
        $baseGrafts = $this->getBaseGrafts();
        if ($baseGrafts === null) {
            return null;
        }

        return $baseGrafts->getWithAddition($this->getGraftsAddition());
    }

    public function getGraftsAddition(): int
    {
        return $this->modifierSpellParameterChanges[ModifierMutableSpellParameterCode::GRAFTS];
    }

    public function getBaseSpellSpeed(): ?SpellSpeed
    {
        return $this->modifiersTable->getSpellSpeed($this->modifierCode);
    }

    public function getSpellSpeedWithAddition(): ?SpellSpeed
    {
        $baseSpellSpeed = $this->getBaseSpellSpeed();
        if ($baseSpellSpeed === null) {
            return null;
        }

        return $baseSpellSpeed->getWithAddition($this->getSpellSpeedAddition());
    }

    public function getSpellSpeedAddition(): int
    {
        return $this->modifierSpellParameterChanges[ModifierMutableSpellParameterCode::SPELL_SPEED];
    }

    public function getBaseInvisibility(): ?Invisibility
    {
        return $this->modifiersTable->getInvisibility($this->modifierCode);
    }

    public function getInvisibilityWithAddition(): ?Invisibility
    {
        $baseInvisibility = $this->getBaseInvisibility();
        if ($baseInvisibility === null) {
            return null;
        }

        return $baseInvisibility->getWithAddition($this->getInvisibilityAddition());
    }

    public function getInvisibilityAddition(): int
    {
        return $this->modifierSpellParameterChanges[ModifierMutableSpellParameterCode::INVISIBILITY];
    }

    public function getBaseQuality(): ?Quality
    {
        return $this->modifiersTable->getQuality($this->modifierCode);
    }

    public function getQualityWithAddition(): ?Quality
    {
        $baseQuality = $this->getBaseQuality();
        if ($baseQuality === null) {
            return null;
        }

        return $baseQuality->getWithAddition($this->getQualityAddition());
    }

    public function getQualityAddition(): int
    {
        return $this->modifierSpellParameterChanges[ModifierMutableSpellParameterCode::QUALITY];
    }

    public function getBaseNumberOfConditions(): ?NumberOfConditions
    {
        return $this->modifiersTable->getNumberOfConditions($this->modifierCode);
    }

    public function getNumberOfConditionsWithAddition(): ?NumberOfConditions
    {
        $baseConditions = $this->getBaseNumberOfConditions();
        if ($baseConditions === null) {
            return null;
        }

        return $baseConditions->getWithAddition($this->getNumberOfConditionsAddition());
    }

    public function getNumberOfConditionsAddition(): int
    {
        return $this->modifierSpellParameterChanges[ModifierMutableSpellParameterCode::NUMBER_OF_CONDITIONS];
    }

    public function getBaseResistance(): ?Resistance
    {
        return $this->modifiersTable->getResistance($this->modifierCode);
    }

    public function getResistanceWithAddition(): ?Resistance
    {
        $baseResistance = $this->getBaseResistance();
        if ($baseResistance === null) {
            return null;
        }

        return $baseResistance->getWithAddition($this->getResistanceAddition());
    }

    public function getResistanceAddition(): int
    {
        return $this->modifierSpellParameterChanges[ModifierMutableSpellParameterCode::RESISTANCE];
    }

    public function getBaseNumberOfSituations(): ?NumberOfSituations
    {
        return $this->modifiersTable->getNumberOfSituations($this->modifierCode);
    }

    public function getNumberOfSituationsWithAddition(): ?NumberOfSituations
    {
        $baseNumberOfSituations = $this->getBaseNumberOfSituations();
        if ($baseNumberOfSituations === null) {
            return null;
        }

        return $baseNumberOfSituations->getWithAddition($this->getNumberOfSituationsAddition());
    }

    public function getNumberOfSituationsAddition(): int
    {
        return $this->modifierSpellParameterChanges[ModifierMutableSpellParameterCode::NUMBER_OF_SITUATIONS];
    }

    public function getBaseThreshold(): ?Threshold
    {
        return $this->modifiersTable->getThreshold($this->modifierCode);
    }

    public function getThresholdWithAddition(): ?Threshold
    {
        $baseThreshold = $this->getBaseThreshold();
        if ($baseThreshold === null) {
            return null;
        }

        return $baseThreshold->getWithAddition($this->getThresholdAddition());
    }

    public function getThresholdAddition(): int
    {
        return $this->modifierSpellParameterChanges[ModifierMutableSpellParameterCode::THRESHOLD];
    }

    public function getBaseNumberOfWaypoints(): ?NumberOfWaypoints
    {
        return $this->modifiersTable->getNumberOfWaypoints($this->modifierCode);
    }

    public function getNumberOfWaypointsWithAddition(): ?NumberOfWaypoints
    {
        $baseNumberOfWaypoints = $this->getBaseNumberOfWaypoints();
        if ($baseNumberOfWaypoints === null) {
            return null;
        }

        return $baseNumberOfWaypoints->getWithAddition($this->getNumberOfWaypointsAddition());
    }

    public function getNumberOfWaypointsAddition(): int
    {
        return $this->modifierSpellParameterChanges[ModifierMutableSpellParameterCode::NUMBER_OF_WAYPOINTS];
    }

    public function __toString()
    {
        return $this->getModifierCode()->getValue();
    }
}