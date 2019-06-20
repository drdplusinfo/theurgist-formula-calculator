<?php declare(strict_types=1);

namespace DrdPlus\Tables\Theurgist\Demons;

use DrdPlus\Codes\Theurgist\DemonBodyCode;
use DrdPlus\Codes\Theurgist\DemonCode;
use DrdPlus\Codes\Theurgist\DemonKindCode;
use DrdPlus\Codes\Theurgist\DemonMutableParameterCode;
use DrdPlus\Codes\Theurgist\DemonTraitCode;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonActivationDuration;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonAgility;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonArea;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonArmor;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonCapacity;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonEndurance;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonInvisibility;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonKnack;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonQuality;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonRadius;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonStrength;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonWill;
use DrdPlus\Tables\Theurgist\Demons\Exceptions\InvalidValueForDemonParameter;
use DrdPlus\Tables\Theurgist\Demons\Exceptions\UnknownDemonParameter;
use DrdPlus\Tables\Theurgist\Exceptions\InvalidValueForMutableParameter;
use DrdPlus\Tables\Theurgist\Exceptions\UnknownParameter;
use DrdPlus\Tables\Theurgist\Partials\SanitizeMutableParameterChangesTrait;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Difficulty;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameter;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Evocation;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Realm;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\RealmsAffection;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellSpeed;
use DrdPlus\Tables\Theurgist\Spells\SpellTrait;
use Granam\Strict\Object\StrictObject;
use Granam\Tools\ValueDescriber;

class Demon extends StrictObject
{
    use SanitizeMutableParameterChangesTrait;
    /**
     * @var DemonCode
     */
    private $demonCode;
    /**
     * @var Tables
     */
    private $tables;
    /**
     * @var array|int[]
     */
    private $demonParameterChanges;
    /**
     * @var DemonTrait[]
     */
    private $demonTraits;

    /**
     * @param DemonCode $demonCode
     * @param Tables $tables
     * @param array $demonParameterChanges
     * @param array|DemonTrait[] $demonTraits
     */
    public function __construct(
        DemonCode $demonCode,
        Tables $tables,
        array $demonParameterChanges,
        array $demonTraits
    )
    {
        $this->demonCode = $demonCode;
        $this->tables = $tables;
        // gets demon parameter changes as delta of current values and default values
        $this->demonParameterChanges = $this->sanitizeDemonParameterChanges($demonParameterChanges);
        $this->demonTraits = $this->checkDemonTraits($demonTraits);
    }

    /**
     * @param array|int[]|string[] $demonParameterChanges
     * @return array|int[]
     * @throws \DrdPlus\Tables\Theurgist\Demons\Exceptions\UnknownDemonParameter
     * @throws \DrdPlus\Tables\Theurgist\Demons\Exceptions\InvalidValueForDemonParameter
     */
    private function sanitizeDemonParameterChanges(array $demonParameterChanges): array
    {
        try {
            return $this->sanitizeMutableParameterChanges(
                $demonParameterChanges,
                DemonMutableParameterCode::getPossibleValues(),
                $this->demonCode,
                $this->tables->getDemonsTable()
            );
        } catch (InvalidValueForMutableParameter $invalidValueForMutableParameter) {
            throw new InvalidValueForDemonParameter(
                $invalidValueForMutableParameter->getMessage(),
                $invalidValueForMutableParameter->getCode(),
                $invalidValueForMutableParameter
            );
        } catch (UnknownParameter $unknownParameter) {
            throw new UnknownDemonParameter(
                $unknownParameter->getMessage(),
                $unknownParameter->getCode(),
                $unknownParameter
            );
        }
    }

    /**
     * @param array|SpellTrait[] $demonTraits
     * @return array|SpellTrait[]
     * @throws \DrdPlus\Tables\Theurgist\Demons\Exceptions\InvalidDemonTrait
     */
    private function checkDemonTraits(array $demonTraits): array
    {
        foreach ($demonTraits as $demonTrait) {
            if (!is_a($demonTrait, DemonTrait::class)) {
                throw new Exceptions\InvalidDemonTrait(
                    'Expected instance of ' . DemonTrait::class . ', got ' . ValueDescriber::describe($demonTrait)
                );
            }
        }

        return $demonTraits;
    }

    public function getDemonCode(): DemonCode
    {
        return $this->demonCode;
    }

    /**
     * Gives the highest required realm (by difficulty and by demon itself or by active traits)
     *
     * @return Realm
     */
    public function getRequiredRealm(): Realm
    {
        $requiredRealm = $this->getEffectiveRealm();

        $realmsAdditionSum = 0;
        foreach ($this->getDemonTraits() as $demonTrait) {
            $realmsAdditionSum += $demonTrait->getRealmsAddition()->getValue();
        }

        if ($realmsAdditionSum === 0) {
            return $requiredRealm;
        }

        return $requiredRealm->add($realmsAdditionSum);
    }

    public function getEffectiveRealm(): Realm
    {
        $baseRealm = $this->getBaseRealm();

        $realmsIncrementBecauseOfDifficulty = $this->getCurrentDifficulty()->getCurrentRealmsIncrement();
        return $baseRealm->add($realmsIncrementBecauseOfDifficulty);
    }

    public function getBaseRealm(): Realm
    {
        return $this->tables->getDemonsTable()->getRealm($this->getDemonCode());
    }

    public function getCurrentDifficulty(): Difficulty
    {
        if (!$this->getDemonParameters()) {
            return $this->getBaseDifficulty();
        }

        $parametersDifficultyChangeSum = 0;
        foreach ($this->getDemonParameters() as $demonParameter) {
            $parametersDifficultyChangeSum += $demonParameter->getAdditionByDifficulty()->getCurrentDifficultyIncrement();
        }
        return $this->getBaseDifficulty()->getWithDifficultyChange($parametersDifficultyChangeSum);
    }

    public function getBaseDifficulty(): Difficulty
    {
        return $this->tables->getDemonsTable()->getDifficulty($this->getDemonCode());
    }

    /**
     * @return array|CastingParameter[]
     */
    private function getDemonParameters(): array
    {
        return array_filter(
            [
                $this->getDemonCapacityWithAddition(),
                $this->getDemonEnduranceWithAddition(),
                $this->getDemonActivationDurationWithAddition(),
                $this->getDemonQualityWithAddition(),
                $this->getDemonRadiusWithAddition(),
                $this->getDemonAreaWithAddition(),
                $this->getDemonInvisibilityWithAddition(),
                $this->getDemonArmorWithAddition(),
                $this->getSpellSpeedWithAddition(),
                $this->getDemonStrengthWithAddition(),
                $this->getDemonAgilityWithAddition(),
                $this->getDemonKnackWithAddition(),
            ],
            function (CastingParameter $formulaParameter = null) {
                return $formulaParameter !== null;
            }
        );
    }

    public function getCurrentDemonRadius(): ?DemonRadius
    {
        $radiusWithAddition = $this->getDemonRadiusWithAddition();
        if (!$radiusWithAddition) {
            return null;
        }
        return new DemonRadius([$radiusWithAddition->getValue(), 0], $this->tables);
    }

    public function getBaseDemonRadius(): ?DemonRadius
    {
        return $this->tables->getDemonsTable()->getDemonRadius($this->getDemonCode());
    }

    private function getDemonRadiusWithAddition(): ?DemonRadius
    {
        $baseDemonRadius = $this->getBaseDemonRadius();
        if ($baseDemonRadius === null) {
            return null;
        }
        return $baseDemonRadius->getWithAddition($this->demonParameterChanges[DemonMutableParameterCode::DEMON_RADIUS]);
    }

    public function getCurrentEvocation(): Evocation
    {
        // Evocation time can not be currently affected directly nor by any trait.
        return $this->getBaseEvocation();
    }

    public function getBaseEvocation(): Evocation
    {
        return $this->tables->getDemonsTable()->getEvocation($this->getDemonCode());
    }

    public function getDemonBodyCode(): DemonBodyCode
    {
        return $this->tables->getDemonsTable()->getDemonBodyCode($this->getDemonCode());
    }

    public function getDemonKindCode(): DemonKindCode
    {
        return $this->tables->getDemonsTable()->getDemonKindCode($this->getDemonCode());
    }

    public function getBaseRealmsAffection(): ?RealmsAffection
    {
        return $this->tables->getDemonsTable()->getRealmsAffection($this->getDemonCode());
    }

    /**
     * Daily, monthly and lifetime affections of realms
     *
     * @return array|RealmsAffection[]
     */
    public function getCurrentRealmsAffections(): array
    {
        $realmsAffections = [];
        foreach ($this->getRealmsAffectionsSum() as $periodName => $periodSum) {
            $realmsAffections[$periodName] = new RealmsAffection([$periodSum, $periodName]);
        }
        return $realmsAffections;
    }

    /**
     * @return array|int[] by affection period indexed summary of that period realms-affection
     */
    private function getRealmsAffectionsSum(): array
    {
        $realmsAffectionsSum = [];
        foreach ($this->getDemonTraits() as $demonTrait) {
            $affectionPeriodName = $demonTrait->getRealmsAffection()->getAffectionPeriodCode()->getValue();
            // like ['daily' => -2]
            $realmsAffectionsSum[$affectionPeriodName] = ($realmsAffectionsSum[$affectionPeriodName] ?? 0) + $demonTrait->getRealmsAffection()->getValue();
        }
        $baseRealmsAffection = $this->getBaseRealmsAffection();
        $affectionPeriodName = $baseRealmsAffection->getAffectionPeriodCode()->getValue();
        $realmsAffectionsSum[$affectionPeriodName] = ($realmsAffectionsSum[$affectionPeriodName] ?? 0) + $baseRealmsAffection->getValue();
        return $realmsAffectionsSum;
    }

    public function getCurrentDemonWill(): DemonWill
    {
        // Demon will can not be currently modified
        return $this->getBaseDemonWill();
    }

    public function getBaseDemonWill(): DemonWill
    {
        return $this->tables->getDemonsTable()->getDemonWill($this->getDemonCode());
    }

    public function getCurrentDemonActivationDuration(): ?DemonActivationDuration
    {
        return $this->getDemonActivationDurationWithAddition();
    }

    public function getBaseDemonActivationDuration(): ?DemonActivationDuration
    {
        return $this->tables->getDemonsTable()->getDemonActivationDuration($this->getDemonCode());
    }

    private function getDemonActivationDurationWithAddition(): ?DemonActivationDuration
    {
        $activationDuration = $this->getBaseDemonActivationDuration();
        if (!$activationDuration) {
            return null;
        }
        return $activationDuration->getWithAddition($this->demonParameterChanges[DemonMutableParameterCode::DEMON_ACTIVATION_DURATION]);
    }

    public function getCurrentDemonCapacity(): ?DemonCapacity
    {
        return $this->getDemonCapacityWithAddition();
    }

    public function getBaseDemonCapacity(): ?DemonCapacity
    {
        return $this->tables->getDemonsTable()->getDemonCapacity($this->getDemonCode());
    }

    private function getDemonCapacityWithAddition(): ?DemonCapacity
    {
        $demonCapacity = $this->getBaseDemonCapacity();
        if (!$demonCapacity) {
            return null;
        }
        return $demonCapacity->getWithAddition($this->demonParameterChanges[DemonMutableParameterCode::DEMON_CAPACITY]);
    }

    public function getCurrentDemonEndurance(): ?DemonEndurance
    {
        return $this->getDemonEnduranceWithAddition();
    }

    public function getBaseDemonEndurance(): ?DemonEndurance
    {
        return $this->tables->getDemonsTable()->getDemonEndurance($this->getDemonCode());
    }

    private function getDemonEnduranceWithAddition(): ?DemonEndurance
    {
        $demonEndurance = $this->getBaseDemonEndurance();
        if (!$demonEndurance) {
            return null;
        }
        return $demonEndurance->getWithAddition($this->demonParameterChanges[DemonMutableParameterCode::DEMON_ENDURANCE]);
    }

    /**
     * @return array|DemonTrait[]
     */
    public function getDemonTraits(): array
    {
        return $this->demonTraits;
    }

    public function hasUnlimitedEndurance(): bool
    {
        foreach ($this->getDemonTraits() as $demonTrait) {
            if ($demonTrait->getDemonTraitCode()->is(DemonTraitCode::UNLIMITED_ENDURANCE)) {
                return true;
            }
        }
        return false;
    }

    public function getCurrentSpellSpeed(): ?SpellSpeed
    {
        return $this->getSpellSpeedWithAddition();
    }

    public function getBaseSpellSpeed(): ?SpellSpeed
    {
        return $this->tables->getDemonsTable()->getSpellSpeed($this->getDemonCode());
    }

    private function getSpellSpeedWithAddition(): ?SpellSpeed
    {
        $spellSpeed = $this->getBaseSpellSpeed();
        if (!$spellSpeed) {
            return null;
        }
        return $spellSpeed->getWithAddition($this->demonParameterChanges[DemonMutableParameterCode::SPELL_SPEED]);
    }

    public function getCurrentDemonQuality(): ?DemonQuality
    {
        return $this->getDemonQualityWithAddition();
    }

    public function getBaseDemonQuality(): ?DemonQuality
    {
        return $this->tables->getDemonsTable()->getDemonQuality($this->getDemonCode());
    }

    private function getDemonQualityWithAddition(): ?DemonQuality
    {
        $demonQuality = $this->getBaseDemonQuality();
        if (!$demonQuality) {
            return null;
        }
        return $demonQuality->getWithAddition($this->demonParameterChanges[DemonMutableParameterCode::DEMON_QUALITY]);
    }

    public function getCurrentDemonInvisibility(): ?DemonInvisibility
    {
        return $this->getDemonInvisibilityWithAddition();
    }

    public function getBaseDemonInvisibility(): ?DemonInvisibility
    {
        return $this->tables->getDemonsTable()->getDemonInvisibility($this->getDemonCode());
    }

    private function getDemonInvisibilityWithAddition(): ?DemonInvisibility
    {
        $baseDemonInvisibility = $this->getBaseDemonInvisibility();
        if (!$baseDemonInvisibility) {
            return null;
        }
        return $baseDemonInvisibility->getWithAddition($this->demonParameterChanges[DemonMutableParameterCode::DEMON_INVISIBILITY]);
    }

    public function getCurrentDemonStrength(): ?DemonStrength
    {
        return $this->getDemonStrengthWithAddition();
    }

    public function getBaseDemonStrength(): ?DemonStrength
    {
        return $this->tables->getDemonsTable()->getDemonStrength($this->getDemonCode());
    }

    private function getDemonStrengthWithAddition(): ?DemonStrength
    {
        $demonStrength = $this->getBaseDemonStrength();
        if (!$demonStrength) {
            return null;
        }
        return $demonStrength->getWithAddition($this->demonParameterChanges[DemonMutableParameterCode::DEMON_STRENGTH]);
    }

    public function getCurrentDemonAgility(): ?DemonAgility
    {
        return $this->getDemonAgilityWithAddition();
    }

    public function getBaseDemonAgility(): ?DemonAgility
    {
        return $this->tables->getDemonsTable()->getDemonAgility($this->getDemonCode());
    }

    private function getDemonAgilityWithAddition(): ?DemonAgility
    {
        $demonAgility = $this->getBaseDemonAgility();
        if (!$demonAgility) {
            return null;
        }
        return $demonAgility->getWithAddition($this->demonParameterChanges[DemonMutableParameterCode::DEMON_AGILITY]);
    }

    public function getCurrentDemonKnack(): ?DemonKnack
    {
        return $this->getDemonKnackWithAddition();
    }

    public function getBaseDemonKnack(): ?DemonKnack
    {
        return $this->tables->getDemonsTable()->getDemonKnack($this->getDemonCode());
    }

    private function getDemonKnackWithAddition(): ?DemonKnack
    {
        $demonKnack = $this->getBaseDemonKnack();
        if (!$demonKnack) {
            return null;
        }
        return $demonKnack->getWithAddition($this->demonParameterChanges[DemonMutableParameterCode::DEMON_KNACK]);
    }

    public function getCurrentDemonArmor(): ?DemonArmor
    {
        return $this->getDemonArmorWithAddition();
    }

    public function getBaseDemonArmor(): ?DemonArmor
    {
        return $this->tables->getDemonsTable()->getDemonArmor($this->getDemonCode());
    }

    private function getDemonArmorWithAddition(): ?DemonArmor
    {
        $demonArmor = $this->getBaseDemonArmor();
        if (!$demonArmor) {
            return null;
        }
        return $demonArmor->getWithAddition($this->demonParameterChanges[DemonMutableParameterCode::DEMON_ARMOR]);
    }

    public function getCurrentDemonArea(): ?DemonArea
    {
        return $this->getDemonAreaWithAddition();
    }

    public function getBaseDemonArea(): ?DemonArea
    {
        return $this->tables->getDemonsTable()->getDemonArea($this->getDemonCode());
    }

    private function getDemonAreaWithAddition(): ?DemonArea
    {
        $demonArea = $this->getBaseDemonArea();
        if (!$demonArea) {
            return null;
        }
        return $demonArea->getWithAddition($this->demonParameterChanges[DemonMutableParameterCode::DEMON_AREA]);
    }
}