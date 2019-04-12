<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells;

use DrdPlus\Tables\Tables;
use DrdPlus\Codes\Theurgist\FormulaCode;
use DrdPlus\Codes\Theurgist\ModifierCode;
use DrdPlus\Codes\Theurgist\SpellTraitCode;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\DifficultyChange;
use DrdPlus\Tables\Theurgist\Spells\FormulasTable;
use DrdPlus\Tables\Theurgist\Spells\ModifiersTable;
use DrdPlus\Tables\Theurgist\Spells\SpellTraitsTable;
use DrdPlus\Tests\Tables\Theurgist\AbstractTheurgistTableTest;

class SpellTraitsTableTest extends AbstractTheurgistTableTest
{
    protected function getMandatoryParameters(): array
    {
        return [SpellTraitsTable::DIFFICULTY_CHANGE];
    }

    protected function getMainCodeClass(): string
    {
        return SpellTraitCode::class;
    }

    protected function getOptionalParameters(): array
    {
        return [SpellTraitsTable::TRAP];
    }

    /**
     * @test
     */
    public function I_can_get_modifiers()
    {
        $spellTraitsTable = new SpellTraitsTable(Tables::getIt());
        foreach (SpellTraitCode::getPossibleValues() as $spellTraitValue) {
            $modifierCodes = $spellTraitsTable->getModifiers(SpellTraitCode::getIt($spellTraitValue));
            self::assertTrue(is_array($modifierCodes));
            $collectedModifierValues = [];
            foreach ($modifierCodes as $modifierCode) {
                self::assertInstanceOf(ModifierCode::class, $modifierCode);
                $collectedModifierValues[] = $modifierCode->getValue();
            }
            sort($collectedModifierValues);
            $possibleModifierValues = $this->getExpectedModifierValues($spellTraitValue);
            sort($possibleModifierValues);
            self::assertEquals(
                $possibleModifierValues,
                $collectedModifierValues,
                'Expected different modifiers for spell trait ' . $spellTraitValue
            );
        }
    }

    private static $impossibleModifiers = [
        SpellTraitCode::AFFECTING => [ModifierCode::COLOR, ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::HAMMER, ModifierCode::CAMOUFLAGE, ModifierCode::INVISIBILITY, ModifierCode::MOVEMENT, ModifierCode::BREACH, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::TRANSPOSITION, ModifierCode::RELEASE, ModifierCode::FRAGRANCE],
        SpellTraitCode::INVISIBLE => [ModifierCode::COLOR, ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::INVISIBILITY, ModifierCode::MOVEMENT, ModifierCode::BREACH, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::TRANSPOSITION, ModifierCode::RELEASE, ModifierCode::FRAGRANCE],
        SpellTraitCode::SILENT => [ModifierCode::COLOR, ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::INVISIBILITY, ModifierCode::MOVEMENT, ModifierCode::BREACH, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::TRANSPOSITION, ModifierCode::RELEASE, ModifierCode::FRAGRANCE],
        SpellTraitCode::ODORLESS => [ModifierCode::COLOR, ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::INVISIBILITY, ModifierCode::MOVEMENT, ModifierCode::BREACH, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::TRANSPOSITION, ModifierCode::RELEASE, ModifierCode::FRAGRANCE],
        SpellTraitCode::CYCLIC => [ModifierCode::COLOR, ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::CAMOUFLAGE, ModifierCode::INVISIBILITY, ModifierCode::BREACH, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::TRANSPOSITION, ModifierCode::RELEASE, ModifierCode::FRAGRANCE],
        SpellTraitCode::MEMORY => [ModifierCode::COLOR, ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::CAMOUFLAGE, ModifierCode::INVISIBILITY, ModifierCode::MOVEMENT, ModifierCode::BREACH, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::TRANSPOSITION, ModifierCode::RELEASE, ModifierCode::FRAGRANCE],
        SpellTraitCode::DEFORMATION => [ModifierCode::COLOR, ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::CAMOUFLAGE, ModifierCode::INVISIBILITY, ModifierCode::MOVEMENT, ModifierCode::BREACH, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::TRANSPOSITION, ModifierCode::FRAGRANCE],
        SpellTraitCode::BIDIRECTIONAL => [ModifierCode::COLOR, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::CAMOUFLAGE, ModifierCode::INVISIBILITY, ModifierCode::MOVEMENT, ModifierCode::BREACH, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::TRANSPOSITION, ModifierCode::RELEASE, ModifierCode::FRAGRANCE],
        SpellTraitCode::UNIDIRECTIONAL => [ModifierCode::COLOR, ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::CAMOUFLAGE, ModifierCode::INVISIBILITY, ModifierCode::MOVEMENT, ModifierCode::BREACH, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::TRANSPOSITION, ModifierCode::RELEASE, ModifierCode::FRAGRANCE],
        SpellTraitCode::INACRID => [ModifierCode::COLOR, ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::CAMOUFLAGE, ModifierCode::INVISIBILITY, ModifierCode::MOVEMENT, ModifierCode::BREACH, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::TRANSPOSITION, ModifierCode::RELEASE, ModifierCode::FRAGRANCE],
        SpellTraitCode::EVERY_SENSE => [ModifierCode::COLOR, ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::CAMOUFLAGE, ModifierCode::INVISIBILITY, ModifierCode::MOVEMENT, ModifierCode::BREACH, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::TRANSPOSITION, ModifierCode::RELEASE, ModifierCode::FRAGRANCE],
        SpellTraitCode::SITUATIONAL => [ModifierCode::COLOR, ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::CAMOUFLAGE, ModifierCode::INVISIBILITY, ModifierCode::MOVEMENT, ModifierCode::BREACH, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::TRANSPOSITION, ModifierCode::RELEASE, ModifierCode::FRAGRANCE],
        SpellTraitCode::SHAPESHIFT => [ModifierCode::COLOR, ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::CAMOUFLAGE, ModifierCode::INVISIBILITY, ModifierCode::MOVEMENT, ModifierCode::BREACH, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::TRANSPOSITION, ModifierCode::RELEASE, ModifierCode::FRAGRANCE],
        SpellTraitCode::STATE_CHANGE => [ModifierCode::COLOR, ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::CAMOUFLAGE, ModifierCode::INVISIBILITY, ModifierCode::MOVEMENT, ModifierCode::BREACH, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::TRANSPOSITION, ModifierCode::RELEASE, ModifierCode::FRAGRANCE],
        SpellTraitCode::NATURE_CHANGE => [ModifierCode::COLOR, ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::CAMOUFLAGE, ModifierCode::INVISIBILITY, ModifierCode::MOVEMENT, ModifierCode::BREACH, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::TRANSPOSITION, ModifierCode::RELEASE, ModifierCode::FRAGRANCE],
        SpellTraitCode::NO_SMOKE => [ModifierCode::COLOR, ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::CAMOUFLAGE, ModifierCode::INVISIBILITY, ModifierCode::MOVEMENT, ModifierCode::BREACH, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::TRANSPOSITION, ModifierCode::RELEASE, ModifierCode::FRAGRANCE],
        SpellTraitCode::TRANSPARENCY => [ModifierCode::COLOR, ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::CAMOUFLAGE, ModifierCode::INVISIBILITY, ModifierCode::MOVEMENT, ModifierCode::BREACH, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::TRANSPOSITION, ModifierCode::RELEASE, ModifierCode::FRAGRANCE],
        SpellTraitCode::MULTIPLE_ENTRY => [ModifierCode::COLOR, ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::CAMOUFLAGE, ModifierCode::INVISIBILITY, ModifierCode::MOVEMENT, ModifierCode::BREACH, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::TRANSPOSITION, ModifierCode::RELEASE, ModifierCode::FRAGRANCE],
        SpellTraitCode::OMNIPRESENT => [ModifierCode::COLOR, ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::CAMOUFLAGE, ModifierCode::INVISIBILITY, ModifierCode::MOVEMENT, ModifierCode::BREACH, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::TRANSPOSITION, ModifierCode::RELEASE, ModifierCode::FRAGRANCE],
        SpellTraitCode::ACTIVE => [ModifierCode::COLOR, ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::CAMOUFLAGE, ModifierCode::INVISIBILITY, ModifierCode::MOVEMENT, ModifierCode::BREACH, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::TRANSPOSITION, ModifierCode::RELEASE, ModifierCode::FRAGRANCE],
    ];

    /**
     * @param string $spellTraitValue
     * @return array|string[]
     */
    private function getExpectedModifierValues(string $spellTraitValue): array
    {
        $expectedModifierValues = array_diff(ModifierCode::getPossibleValues(), self::$impossibleModifiers[$spellTraitValue]);
        sort($expectedModifierValues);
        $modifierValuesFromModifiersTable = $this->getModifierValuesFromModifiersTable($spellTraitValue);
        sort($modifierValuesFromModifiersTable);
        self::assertEquals(
            $expectedModifierValues,
            $modifierValuesFromModifiersTable,
            "Expected different modifiers for spell trait '{$spellTraitValue}'"
        );

        return $expectedModifierValues;
    }

    /**
     * @param string $spellTraitValue
     * @return array
     */
    private function getModifierValuesFromModifiersTable(string $spellTraitValue): array
    {
        $matchingModifierValues = [];
        $modifiersTable = new ModifiersTable(Tables::getIt());
        foreach (ModifierCode::getPossibleValues() as $modifierValue) {
            $spellTraitCodes = $modifiersTable->getSpellTraits(ModifierCode::getIt($modifierValue));
            foreach ($spellTraitCodes as $spellTraitCode) {
                if ($spellTraitCode->getValue() === $spellTraitValue) {
                    $matchingModifierValues[] = $modifierValue;
                    continue;
                }
            }
        }

        return $matchingModifierValues;
    }

    /**
     * @test
     */
    public function I_can_get_formulas()
    {
        $spellTraitsTable = new SpellTraitsTable(Tables::getIt());
        foreach (SpellTraitCode::getPossibleValues() as $spellTraitValue) {
            $formulaCodes = $spellTraitsTable->getFormulas(SpellTraitCode::getIt($spellTraitValue));
            self::assertTrue(is_array($formulaCodes));
            $collectedFormulaValues = [];
            foreach ($formulaCodes as $formulaCode) {
                self::assertInstanceOf(FormulaCode::class, $formulaCode);
                $collectedFormulaValues[] = $formulaCode->getValue();
            }
            sort($collectedFormulaValues);
            $possibleFormulaValues = $this->getExpectedFormulaValues($spellTraitValue);
            sort($possibleFormulaValues);
            self::assertEquals(
                $possibleFormulaValues,
                $collectedFormulaValues,
                'Expected different formulas for spell trait ' . $spellTraitValue
            );
        }
    }

    private static $impossibleFormulas = [
        SpellTraitCode::AFFECTING => [FormulaCode::BARRIER, FormulaCode::SMOKE, FormulaCode::ILLUSION, FormulaCode::METAMORPHOSIS, FormulaCode::FIRE, FormulaCode::PORTAL, FormulaCode::LIGHT, FormulaCode::FLOW_OF_TIME, FormulaCode::TSUNAMI_FROM_CLAY_AND_STONES, FormulaCode::HIT, FormulaCode::GREAT_MASSACRE, FormulaCode::DISCHARGE, FormulaCode::LOCK],
        SpellTraitCode::INVISIBLE => [FormulaCode::BARRIER, FormulaCode::SMOKE, FormulaCode::ILLUSION, FormulaCode::METAMORPHOSIS, FormulaCode::FIRE, FormulaCode::PORTAL, FormulaCode::LIGHT, FormulaCode::FLOW_OF_TIME, FormulaCode::TSUNAMI_FROM_CLAY_AND_STONES, FormulaCode::HIT, FormulaCode::GREAT_MASSACRE, FormulaCode::DISCHARGE, FormulaCode::LOCK],
        SpellTraitCode::SILENT => [FormulaCode::BARRIER, FormulaCode::SMOKE, FormulaCode::ILLUSION, FormulaCode::METAMORPHOSIS, FormulaCode::FIRE, FormulaCode::PORTAL, FormulaCode::LIGHT, FormulaCode::FLOW_OF_TIME, FormulaCode::TSUNAMI_FROM_CLAY_AND_STONES, FormulaCode::HIT, FormulaCode::GREAT_MASSACRE, FormulaCode::DISCHARGE, FormulaCode::LOCK],
        SpellTraitCode::ODORLESS => [FormulaCode::BARRIER, FormulaCode::SMOKE, FormulaCode::ILLUSION, FormulaCode::METAMORPHOSIS, FormulaCode::FIRE, FormulaCode::PORTAL, FormulaCode::LIGHT, FormulaCode::FLOW_OF_TIME, FormulaCode::TSUNAMI_FROM_CLAY_AND_STONES, FormulaCode::HIT, FormulaCode::GREAT_MASSACRE, FormulaCode::DISCHARGE, FormulaCode::LOCK],
        SpellTraitCode::CYCLIC => [FormulaCode::BARRIER, FormulaCode::SMOKE, FormulaCode::ILLUSION, FormulaCode::METAMORPHOSIS, FormulaCode::FIRE, FormulaCode::PORTAL, FormulaCode::LIGHT, FormulaCode::FLOW_OF_TIME, FormulaCode::TSUNAMI_FROM_CLAY_AND_STONES, FormulaCode::HIT, FormulaCode::GREAT_MASSACRE, FormulaCode::DISCHARGE, FormulaCode::LOCK],
        SpellTraitCode::MEMORY => [FormulaCode::BARRIER, FormulaCode::SMOKE, FormulaCode::ILLUSION, FormulaCode::METAMORPHOSIS, FormulaCode::FIRE, FormulaCode::PORTAL, FormulaCode::LIGHT, FormulaCode::FLOW_OF_TIME, FormulaCode::TSUNAMI_FROM_CLAY_AND_STONES, FormulaCode::HIT, FormulaCode::GREAT_MASSACRE, FormulaCode::DISCHARGE, FormulaCode::LOCK],
        SpellTraitCode::DEFORMATION => [FormulaCode::BARRIER, FormulaCode::SMOKE, FormulaCode::ILLUSION, FormulaCode::METAMORPHOSIS, FormulaCode::FIRE, FormulaCode::PORTAL, FormulaCode::LIGHT, FormulaCode::FLOW_OF_TIME, FormulaCode::TSUNAMI_FROM_CLAY_AND_STONES, FormulaCode::HIT, FormulaCode::GREAT_MASSACRE, FormulaCode::DISCHARGE, FormulaCode::LOCK],
        SpellTraitCode::BIDIRECTIONAL => [FormulaCode::BARRIER, FormulaCode::SMOKE, FormulaCode::ILLUSION, FormulaCode::METAMORPHOSIS, FormulaCode::FIRE, FormulaCode::PORTAL, FormulaCode::LIGHT, FormulaCode::FLOW_OF_TIME, FormulaCode::TSUNAMI_FROM_CLAY_AND_STONES, FormulaCode::HIT, FormulaCode::GREAT_MASSACRE, FormulaCode::DISCHARGE, FormulaCode::LOCK],
        SpellTraitCode::UNIDIRECTIONAL => [FormulaCode::SMOKE, FormulaCode::ILLUSION, FormulaCode::METAMORPHOSIS, FormulaCode::FIRE, FormulaCode::PORTAL, FormulaCode::LIGHT, FormulaCode::FLOW_OF_TIME, FormulaCode::TSUNAMI_FROM_CLAY_AND_STONES, FormulaCode::HIT, FormulaCode::GREAT_MASSACRE, FormulaCode::DISCHARGE, FormulaCode::LOCK],
        SpellTraitCode::INACRID => [FormulaCode::BARRIER, FormulaCode::ILLUSION, FormulaCode::METAMORPHOSIS, FormulaCode::FIRE, FormulaCode::PORTAL, FormulaCode::LIGHT, FormulaCode::FLOW_OF_TIME, FormulaCode::TSUNAMI_FROM_CLAY_AND_STONES, FormulaCode::HIT, FormulaCode::GREAT_MASSACRE, FormulaCode::DISCHARGE, FormulaCode::LOCK],
        SpellTraitCode::EVERY_SENSE => [FormulaCode::BARRIER, FormulaCode::SMOKE, FormulaCode::METAMORPHOSIS, FormulaCode::FIRE, FormulaCode::PORTAL, FormulaCode::LIGHT, FormulaCode::FLOW_OF_TIME, FormulaCode::TSUNAMI_FROM_CLAY_AND_STONES, FormulaCode::HIT, FormulaCode::GREAT_MASSACRE, FormulaCode::DISCHARGE, FormulaCode::LOCK],
        SpellTraitCode::SITUATIONAL => [FormulaCode::BARRIER, FormulaCode::SMOKE, FormulaCode::METAMORPHOSIS, FormulaCode::FIRE, FormulaCode::PORTAL, FormulaCode::LIGHT, FormulaCode::FLOW_OF_TIME, FormulaCode::TSUNAMI_FROM_CLAY_AND_STONES, FormulaCode::HIT, FormulaCode::GREAT_MASSACRE, FormulaCode::DISCHARGE, FormulaCode::LOCK],
        SpellTraitCode::SHAPESHIFT => [FormulaCode::BARRIER, FormulaCode::SMOKE, FormulaCode::ILLUSION, FormulaCode::FIRE, FormulaCode::PORTAL, FormulaCode::LIGHT, FormulaCode::FLOW_OF_TIME, FormulaCode::TSUNAMI_FROM_CLAY_AND_STONES, FormulaCode::HIT, FormulaCode::GREAT_MASSACRE, FormulaCode::DISCHARGE, FormulaCode::LOCK],
        SpellTraitCode::STATE_CHANGE => [FormulaCode::BARRIER, FormulaCode::SMOKE, FormulaCode::ILLUSION, FormulaCode::FIRE, FormulaCode::PORTAL, FormulaCode::LIGHT, FormulaCode::FLOW_OF_TIME, FormulaCode::TSUNAMI_FROM_CLAY_AND_STONES, FormulaCode::HIT, FormulaCode::GREAT_MASSACRE, FormulaCode::DISCHARGE, FormulaCode::LOCK],
        SpellTraitCode::NATURE_CHANGE => [FormulaCode::BARRIER, FormulaCode::SMOKE, FormulaCode::ILLUSION, FormulaCode::FIRE, FormulaCode::PORTAL, FormulaCode::LIGHT, FormulaCode::FLOW_OF_TIME, FormulaCode::TSUNAMI_FROM_CLAY_AND_STONES, FormulaCode::HIT, FormulaCode::GREAT_MASSACRE, FormulaCode::DISCHARGE, FormulaCode::LOCK],
        SpellTraitCode::NO_SMOKE => [FormulaCode::BARRIER, FormulaCode::SMOKE, FormulaCode::ILLUSION, FormulaCode::METAMORPHOSIS, FormulaCode::PORTAL, FormulaCode::LIGHT, FormulaCode::FLOW_OF_TIME, FormulaCode::TSUNAMI_FROM_CLAY_AND_STONES, FormulaCode::HIT, FormulaCode::GREAT_MASSACRE, FormulaCode::DISCHARGE, FormulaCode::LOCK],
        SpellTraitCode::TRANSPARENCY => [FormulaCode::BARRIER, FormulaCode::SMOKE, FormulaCode::ILLUSION, FormulaCode::METAMORPHOSIS, FormulaCode::FIRE, FormulaCode::LIGHT, FormulaCode::FLOW_OF_TIME, FormulaCode::TSUNAMI_FROM_CLAY_AND_STONES, FormulaCode::HIT, FormulaCode::GREAT_MASSACRE, FormulaCode::DISCHARGE, FormulaCode::LOCK],
        SpellTraitCode::MULTIPLE_ENTRY => [FormulaCode::BARRIER, FormulaCode::SMOKE, FormulaCode::ILLUSION, FormulaCode::METAMORPHOSIS, FormulaCode::FIRE, FormulaCode::LIGHT, FormulaCode::FLOW_OF_TIME, FormulaCode::TSUNAMI_FROM_CLAY_AND_STONES, FormulaCode::HIT, FormulaCode::GREAT_MASSACRE, FormulaCode::DISCHARGE, FormulaCode::LOCK],
        SpellTraitCode::OMNIPRESENT => [FormulaCode::BARRIER, FormulaCode::SMOKE, FormulaCode::ILLUSION, FormulaCode::METAMORPHOSIS, FormulaCode::FIRE, FormulaCode::PORTAL, FormulaCode::FLOW_OF_TIME, FormulaCode::TSUNAMI_FROM_CLAY_AND_STONES, FormulaCode::HIT, FormulaCode::GREAT_MASSACRE, FormulaCode::DISCHARGE, FormulaCode::LOCK],
        SpellTraitCode::ACTIVE => [FormulaCode::BARRIER, FormulaCode::SMOKE, FormulaCode::ILLUSION, FormulaCode::METAMORPHOSIS, FormulaCode::FIRE, FormulaCode::PORTAL, FormulaCode::LIGHT, FormulaCode::FLOW_OF_TIME, FormulaCode::TSUNAMI_FROM_CLAY_AND_STONES, FormulaCode::HIT, FormulaCode::GREAT_MASSACRE, FormulaCode::DISCHARGE],
    ];

    /**
     * @param string $spellTraitValue
     * @return array
     */
    private function getExpectedFormulaValues(string $spellTraitValue): array
    {
        $expectedFormulaValues = array_diff(FormulaCode::getPossibleValues(), self::$impossibleFormulas[$spellTraitValue]);
        sort($expectedFormulaValues);
        $formulaValuesFromFormulasTable = $this->getFormulaValuesFromFormulasTable($spellTraitValue);
        sort($formulaValuesFromFormulasTable);
        self::assertEquals(
            $expectedFormulaValues,
            $formulaValuesFromFormulasTable,
            "Expected different formulas for spell trait '{$spellTraitValue}'"
        );

        return $expectedFormulaValues;
    }

    /**
     * @param string $spellTraitValue
     * @return array
     */
    private function getFormulaValuesFromFormulasTable(string $spellTraitValue): array
    {
        $matchingFormulaValues = [];
        $formulasTable = new FormulasTable(Tables::getIt());
        foreach (FormulaCode::getPossibleValues() as $formulaValue) {
            $spellTraits = $formulasTable->getSpellTraits(FormulaCode::getIt($formulaValue));
            foreach ($spellTraits as $spellTrait) {
                if ($spellTrait->getSpellTraitCode()->getValue() === $spellTraitValue) {
                    $matchingFormulaValues[] = $formulaValue;
                    continue;
                }
            }
        }

        return $matchingFormulaValues;
    }

    /**
     * @test
     */
    public function I_can_get_sum_of_difficulty_changes()
    {
        self::assertEquals(
            new DifficultyChange(18),
            (new SpellTraitsTable(Tables::getIt()))->sumDifficultyChanges(
                [
                    SpellTraitCode::getIt(SpellTraitCode::DEFORMATION), // +3
                    SpellTraitCode::getIt(SpellTraitCode::ODORLESS), // +3
                    [
                        SpellTraitCode::getIt(SpellTraitCode::AFFECTING), // +6
                        [SpellTraitCode::getIt(SpellTraitCode::CYCLIC)], // +6
                    ],
                ]
            )
        );
    }
}