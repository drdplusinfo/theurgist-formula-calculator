<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells;

use DrdPlus\Codes\Theurgist\FormCode;
use DrdPlus\Codes\Theurgist\FormulaCode;
use DrdPlus\Codes\Theurgist\ModifierCode;
use DrdPlus\Codes\Theurgist\ProfileCode;
use DrdPlus\Codes\Theurgist\SpellTraitCode;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Spells\FormulasTable;
use DrdPlus\Tables\Theurgist\Spells\ModifiersTable;
use DrdPlus\Tables\Theurgist\Spells\ProfilesTable;
use DrdPlus\Tables\Theurgist\Spells\SpellTrait;
use DrdPlus\Tests\Tables\Theurgist\AbstractTheurgistTableTest;

class ModifiersTableTest extends AbstractTheurgistTableTest
{
    protected function getMandatoryParameters(): array
    {
        return [ModifiersTable::REALM, ModifiersTable::DIFFICULTY_CHANGE];
    }

    protected function getMainCodeClass(): string
    {
        return ModifierCode::class;
    }

    protected function getOptionalParameters(): array
    {
        return [
            ModifiersTable::REALMS_AFFECTION, ModifiersTable::CASTING_ROUNDS, ModifiersTable::RADIUS, ModifiersTable::EPICENTER_SHIFT,
            ModifiersTable::SPELL_POWER, ModifiersTable::SPELL_ATTACK, ModifiersTable::GRAFTS, ModifiersTable::SPELL_SPEED,
            ModifiersTable::NUMBER_OF_WAYPOINTS, ModifiersTable::INVISIBILITY, ModifiersTable::QUALITY, ModifiersTable::NUMBER_OF_CONDITIONS,
            ModifiersTable::RESISTANCE, ModifiersTable::NUMBER_OF_SITUATIONS, ModifiersTable::THRESHOLD, ModifiersTable::NOISE,
        ];
    }

    /**
     * @test
     */
    public function I_can_get_forms()
    {
        $modifiersTable = new ModifiersTable(Tables::getIt());
        foreach (ModifierCode::getPossibleValues() as $modifierValue) {
            $forms = $modifiersTable->getFormCodes(ModifierCode::getIt($modifierValue));
            $formValues = [];
            foreach ($forms as $form) {
                self::assertInstanceOf(FormCode::class, $form);
                $formValues[] = $form->getValue();
            }
            self::assertSame($formValues, array_unique($formValues));
            sort($formValues);
            $expectedFormValues = $this->getExpectedFormValues($modifierValue);
            sort($expectedFormValues);
            self::assertEquals($expectedFormValues, $formValues, "Expected different forms for '{$modifierValue}'");
        }
    }

    private static $excludedFormValues = [
        ModifierCode::COLOR => ['direct', 'indirect', 'volume', 'planar', 'beam', 'tangible', 'intangible', 'visible', 'invisible', 'by_formula'],
        ModifierCode::GATE => ['indirect', 'volume', 'beam', 'intangible', 'invisible', 'by_formula'],
        ModifierCode::EXPLOSION => ['direct', 'indirect', 'volume', 'planar', 'beam', 'tangible', 'intangible', 'visible', 'invisible', 'by_formula'],
        ModifierCode::FILTER => ['direct', 'indirect', 'volume', 'planar', 'beam', 'tangible', 'intangible', 'visible', 'invisible', 'by_formula'],
        ModifierCode::WATCHER => ['direct', 'indirect', 'volume', 'planar', 'beam', 'tangible', 'intangible', 'visible', 'invisible', 'by_formula'],
        ModifierCode::THUNDER => ['direct', 'planar', 'beam', 'tangible', 'visible', 'by_formula'],
        ModifierCode::INTERACTIVE_ILLUSION => ['direct', 'indirect', 'volume', 'planar', 'beam', 'tangible', 'intangible', 'visible', 'invisible', 'by_formula'],
        ModifierCode::HAMMER => ['direct', 'indirect', 'volume', 'planar', 'beam', 'tangible', 'intangible', 'visible', 'invisible', 'by_formula'],
        ModifierCode::CAMOUFLAGE => ['direct', 'indirect', 'volume', 'planar', 'beam', 'tangible', 'intangible', 'visible', 'invisible', 'by_formula'],
        ModifierCode::INVISIBILITY => ['direct', 'indirect', 'volume', 'planar', 'beam', 'tangible', 'intangible', 'visible', 'invisible', 'by_formula'],
        ModifierCode::MOVEMENT => ['direct', 'indirect', 'volume', 'planar', 'beam', 'tangible', 'intangible', 'visible', 'invisible', 'by_formula'],
        ModifierCode::BREACH => ['direct', 'indirect', 'volume', 'planar', 'beam', 'tangible', 'intangible', 'visible', 'invisible', 'by_formula'],
        ModifierCode::RECEPTOR => ['direct', 'indirect', 'volume', 'planar', 'beam', 'tangible', 'intangible', 'visible', 'invisible', 'by_formula'],
        ModifierCode::STEP_TO_FUTURE => ['indirect', 'volume', 'planar', 'beam', 'tangible', 'intangible', 'visible', 'invisible'],
        ModifierCode::STEP_TO_PAST => ['indirect', 'volume', 'planar', 'beam', 'tangible', 'intangible', 'visible', 'invisible'],
        ModifierCode::TRANSPOSITION => ['direct', 'planar', 'beam', 'tangible', 'visible', 'by_formula'],
        ModifierCode::RELEASE => ['direct', 'indirect', 'volume', 'planar', 'beam', 'tangible', 'intangible', 'visible', 'invisible', 'by_formula'],
        ModifierCode::FRAGRANCE => ['direct', 'indirect', 'volume', 'planar', 'beam', 'tangible', 'intangible', 'visible', 'invisible', 'by_formula'],
    ];

    /**
     * @param string $modifierValue
     * @return array
     */
    private function getExpectedFormValues(string $modifierValue): array
    {
        $excludedFormValues = self::$excludedFormValues[$modifierValue];

        return array_diff(FormCode::getPossibleValues(), $excludedFormValues);
    }

    /**
     * @test
     */
    public function I_can_get_spell_trait_codes()
    {
        $modifiersTable = new ModifiersTable(Tables::getIt());
        foreach (ModifierCode::getPossibleValues() as $modifierValue) {
            $expectedSpellTraitCodes = $this->getExpectedSpellTraitCodes($modifiersTable, $modifierValue);
            $spellTraitCodes = $modifiersTable->getSpellTraitCodes(ModifierCode::getIt($modifierValue));
            self::assertEquals($expectedSpellTraitCodes, $spellTraitCodes);

            $spellTraitCodeValues = $this->extractSpellTraitCodeValues($spellTraitCodes);
            self::assertSame($spellTraitCodeValues, array_unique($spellTraitCodeValues));
            sort($spellTraitCodeValues);
            $expectedSpellTraitCodeValues = $this->getExpectedSpellTraitCodeValues($modifierValue);
            sort($expectedSpellTraitCodeValues);
            self::assertEquals($expectedSpellTraitCodeValues, $spellTraitCodeValues, "Expected different traits for '{$modifierValue}'");
        }
    }

    /**
     * @param array|SpellTraitCode[] $spellTraitCodes
     * @return array|string[]
     */
    private function extractSpellTraitCodeValues(array $spellTraitCodes)
    {
        $spellTraitCodeValues = [];
        foreach ($spellTraitCodes as $spellTraitCode) {
            self::assertInstanceOf(SpellTraitCode::class, $spellTraitCode);
            $spellTraitCodeValues[] = $spellTraitCode->getValue();
        }
        return $spellTraitCodeValues;
    }

    private function getExpectedSpellTraitCodes(ModifiersTable $modifiersTable, string $modifierValue): array
    {
        /** @var array|string[] $expectedTraitValues */
        $expectedTraitValues = $this->getValueFromTable($modifiersTable, $modifierValue, 'spell_traits');
        $expectedSpellTraitCodes = [];
        foreach ($expectedTraitValues as $expectedSpellTraitValue) {
            $expectedSpellTraitCodes[] = SpellTraitCode::getIt($expectedSpellTraitValue);
        }
        return $expectedSpellTraitCodes;
    }

    /**
     * @test
     */
    public function I_can_get_spell_traits()
    {
        $modifiersTable = new ModifiersTable(Tables::getIt());
        foreach (ModifierCode::getPossibleValues() as $modifierValue) {
            $expectedSpellTraits = $this->getExpectedSpellTraits($modifiersTable, $modifierValue);
            $spellTraits = $modifiersTable->getSpellTraits(ModifierCode::getIt($modifierValue));
            self::assertEquals($expectedSpellTraits, $spellTraits);
        }
    }

    private function getExpectedSpellTraits(ModifiersTable $modifiersTable, string $modifierValue): array
    {
        $expectedSpellTraitCodes = $this->getExpectedSpellTraitCodes($modifiersTable, $modifierValue);
        $expectedSpellTraits = [];
        foreach ($expectedSpellTraitCodes as $expectedSpellTraitCode) {
            $expectedSpellTraits[] = new SpellTrait($expectedSpellTraitCode, Tables::getIt());
        }
        return $expectedSpellTraits;
    }

    private static $excludedTraitValues = [
        ModifierCode::COLOR => [SpellTraitCode::AFFECTING, SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::CYCLIC, SpellTraitCode::MEMORY, SpellTraitCode::DEFORMATION, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::NO_SMOKE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::OMNIPRESENT, SpellTraitCode::ACTIVE],
        ModifierCode::GATE => [SpellTraitCode::AFFECTING, SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::CYCLIC, SpellTraitCode::MEMORY, SpellTraitCode::DEFORMATION, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::NO_SMOKE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::OMNIPRESENT, SpellTraitCode::ACTIVE],
        ModifierCode::EXPLOSION => [SpellTraitCode::AFFECTING, SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::CYCLIC, SpellTraitCode::MEMORY, SpellTraitCode::DEFORMATION, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::NO_SMOKE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::OMNIPRESENT, SpellTraitCode::ACTIVE],
        ModifierCode::FILTER => [SpellTraitCode::AFFECTING, SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::CYCLIC, SpellTraitCode::MEMORY, SpellTraitCode::DEFORMATION, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::NO_SMOKE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::OMNIPRESENT, SpellTraitCode::ACTIVE],
        ModifierCode::WATCHER => [SpellTraitCode::AFFECTING, SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::CYCLIC, SpellTraitCode::MEMORY, SpellTraitCode::DEFORMATION, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::NO_SMOKE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::OMNIPRESENT, SpellTraitCode::ACTIVE],
        ModifierCode::THUNDER => [SpellTraitCode::AFFECTING, SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::CYCLIC, SpellTraitCode::MEMORY, SpellTraitCode::DEFORMATION, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::NO_SMOKE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::OMNIPRESENT, SpellTraitCode::ACTIVE],
        ModifierCode::INTERACTIVE_ILLUSION => [SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::CYCLIC, SpellTraitCode::MEMORY, SpellTraitCode::DEFORMATION, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::NO_SMOKE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::OMNIPRESENT, SpellTraitCode::ACTIVE],
        ModifierCode::HAMMER => [SpellTraitCode::AFFECTING, SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::CYCLIC, SpellTraitCode::MEMORY, SpellTraitCode::DEFORMATION, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::NO_SMOKE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::OMNIPRESENT, SpellTraitCode::ACTIVE],
        ModifierCode::CAMOUFLAGE => [SpellTraitCode::AFFECTING, SpellTraitCode::CYCLIC, SpellTraitCode::MEMORY, SpellTraitCode::DEFORMATION, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::NO_SMOKE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::OMNIPRESENT, SpellTraitCode::ACTIVE],
        ModifierCode::INVISIBILITY => [SpellTraitCode::AFFECTING, SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::CYCLIC, SpellTraitCode::MEMORY, SpellTraitCode::DEFORMATION, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::NO_SMOKE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::OMNIPRESENT, SpellTraitCode::ACTIVE],
        ModifierCode::MOVEMENT => [SpellTraitCode::AFFECTING, SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::MEMORY, SpellTraitCode::DEFORMATION, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::NO_SMOKE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::OMNIPRESENT, SpellTraitCode::ACTIVE],
        ModifierCode::BREACH => [SpellTraitCode::AFFECTING, SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::CYCLIC, SpellTraitCode::MEMORY, SpellTraitCode::DEFORMATION, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::NO_SMOKE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::OMNIPRESENT, SpellTraitCode::ACTIVE],
        ModifierCode::RECEPTOR => [SpellTraitCode::AFFECTING, SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::CYCLIC, SpellTraitCode::MEMORY, SpellTraitCode::DEFORMATION, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::NO_SMOKE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::OMNIPRESENT, SpellTraitCode::ACTIVE],
        ModifierCode::STEP_TO_FUTURE => [SpellTraitCode::AFFECTING, SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::CYCLIC, SpellTraitCode::MEMORY, SpellTraitCode::DEFORMATION, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::NO_SMOKE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::OMNIPRESENT, SpellTraitCode::ACTIVE],
        ModifierCode::STEP_TO_PAST => [SpellTraitCode::AFFECTING, SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::CYCLIC, SpellTraitCode::DEFORMATION, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::NO_SMOKE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::OMNIPRESENT, SpellTraitCode::ACTIVE],
        ModifierCode::TRANSPOSITION => [SpellTraitCode::AFFECTING, SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::CYCLIC, SpellTraitCode::MEMORY, SpellTraitCode::DEFORMATION, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::NO_SMOKE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::OMNIPRESENT, SpellTraitCode::ACTIVE],
        ModifierCode::RELEASE => [SpellTraitCode::AFFECTING, SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::CYCLIC, SpellTraitCode::MEMORY, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::NO_SMOKE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::OMNIPRESENT, SpellTraitCode::ACTIVE],
        ModifierCode::FRAGRANCE => [SpellTraitCode::AFFECTING, SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::CYCLIC, SpellTraitCode::MEMORY, SpellTraitCode::DEFORMATION, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::NO_SMOKE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::OMNIPRESENT, SpellTraitCode::ACTIVE],
    ];

    /**
     * @param string $formulaValue
     * @return array
     */
    private function getExpectedSpellTraitCodeValues(string $formulaValue): array
    {
        $excludedTraitValues = self::$excludedTraitValues[$formulaValue];

        return array_diff(SpellTraitCode::getPossibleValues(), $excludedTraitValues);
    }

    /**
     * @test
     */
    public function I_can_get_formulas_for_modifier()
    {
        $modifiersTable = new ModifiersTable(Tables::getIt());
        foreach (ModifierCode::getPossibleValues() as $modifierValue) {
            $formulaCodes = $modifiersTable->getFormulaCodes(ModifierCode::getIt($modifierValue));
            self::assertTrue(is_array($formulaCodes));
            if (in_array($modifierValue, [ModifierCode::STEP_TO_PAST, ModifierCode::STEP_TO_FUTURE], true)) {
                self::assertEmpty($formulaCodes);
            } else {
                self::assertNotEmpty($formulaCodes, 'Expected some formulas for modifier ' . $modifierValue);
            }
            $collectedFormulaValues = [];
            foreach ($formulaCodes as $formulaCode) {
                self::assertInstanceOf(FormulaCode::class, $formulaCode);
                $collectedFormulaValues[] = $formulaCode->getValue();
            }
            sort($collectedFormulaValues);
            $expectedFormulaValues = $this->getExpectedFormulaValues($modifierValue);
            sort($expectedFormulaValues);
            self::assertEquals(
                $expectedFormulaValues,
                $collectedFormulaValues,
                'Expected different formulas for modifier ' . $modifierValue
                . (count($missing = array_diff($expectedFormulaValues, $collectedFormulaValues)) > 0
                    ? ', missing: ' . implode(', ', $missing)
                    : ''
                )
                . (count($redundant = array_diff($collectedFormulaValues, $expectedFormulaValues)) > 0
                    ? ', not expecting: ' . implode(', ', $redundant)
                    : ''
                )
            );
        }
    }

    /**
     * @param string $modifierValue
     * @return array|string[]
     */
    private function getExpectedFormulaValues(string $modifierValue): array
    {
        $expectedFormulaValues = [];
        $formulasTable = new FormulasTable(Tables::getIt());
        foreach (FormulaCode::getPossibleValues() as $formulaValue) {
            $modifierCodes = $formulasTable->getModifierCodes(FormulaCode::getIt($formulaValue));
            foreach ($modifierCodes as $modifierCode) {
                if ($modifierCode->getValue() === $modifierValue) {
                    $expectedFormulaValues[] = $formulaValue;
                    break;
                }
            }
        }

        return $expectedFormulaValues;
    }

    /**
     * @test
     */
    public function I_can_not_get_formulas_to_unknown_modifier()
    {
        $this->expectException(\DrdPlus\Tables\Theurgist\Spells\Exceptions\UnknownModifierToGetFormulasFor::class);
        $this->expectExceptionMessageRegExp('~black and white~');
        (new ModifiersTable(Tables::getIt()))->getFormulaCodes($this->createModifierCode('black and white'));
    }

    /**
     * @param string $value
     * @return \Mockery\MockInterface|ModifierCode
     */
    private function createModifierCode(string $value)
    {
        $modifierCode = $this->mockery(ModifierCode::class);
        $modifierCode->shouldReceive('getValue')
            ->andReturn($value);
        $modifierCode->shouldReceive('__toString')
            ->andReturn($value);

        return $modifierCode;
    }

    /**
     * @test
     */
    public function I_can_get_profiles_to_modifier()
    {
        $modifiersTable = new ModifiersTable(Tables::getIt());
        foreach (ModifierCode::getPossibleValues() as $modifierValue) {
            $profileCodes = $modifiersTable->getProfileCodes(ModifierCode::getIt($modifierValue));
            self::assertTrue(is_array($profileCodes));
            self::assertNotEmpty($profileCodes);
            $collectedProfileValues = [];
            foreach ($profileCodes as $profileCode) {
                self::assertInstanceOf(ProfileCode::class, $profileCode);
                $collectedProfileValues[] = $profileCode->getValue();
            }
            sort($collectedProfileValues);
            $expectedProfileValues = $this->getExpectedProfileValues($modifierValue);
            sort($expectedProfileValues);
            self::assertEquals(
                $expectedProfileValues,
                $collectedProfileValues,
                "Expected different profiles for profile '{$modifierValue}'"
                . (count($redundant = array_diff($collectedProfileValues, $expectedProfileValues)) > 0
                    ? ', not expecting: ' . implode(', ', $redundant)
                    : ''
                )
                . (count($missing = array_diff($expectedProfileValues, $collectedProfileValues)) > 0
                    ? ', missing: ' . implode(', ', $missing)
                    : ''
                )
            );
        }
    }

    private static $impossibleProfiles = [
        ModifierCode::COLOR => [ProfileCode::BARRIER_VENUS, ProfileCode::BARRIER_MARS, ProfileCode::SPARK_VENUS, ProfileCode::RELEASE_VENUS, ProfileCode::RELEASE_MARS, ProfileCode::SCENT_VENUS, ProfileCode::SCENT_MARS, ProfileCode::ILLUSION_VENUS, ProfileCode::ILLUSION_MARS, ProfileCode::RECEPTOR_VENUS, ProfileCode::RECEPTOR_MARS, ProfileCode::BREACH_VENUS, ProfileCode::BREACH_MARS, ProfileCode::FIRE_VENUS, ProfileCode::FIRE_MARS, ProfileCode::GATE_VENUS, ProfileCode::GATE_MARS, ProfileCode::MOVEMENT_VENUS, ProfileCode::MOVEMENT_MARS, ProfileCode::TRANSPOSITION_VENUS, ProfileCode::TRANSPOSITION_MARS, ProfileCode::DISCHARGE_VENUS, ProfileCode::DISCHARGE_MARS, ProfileCode::WATCHER_VENUS, ProfileCode::WATCHER_MARS, ProfileCode::LOOK_VENUS, ProfileCode::TIME_VENUS, ProfileCode::TIME_MARS],
        ModifierCode::GATE => [ProfileCode::BARRIER_VENUS, ProfileCode::BARRIER_MARS, ProfileCode::SPARK_MARS, ProfileCode::RELEASE_MARS, ProfileCode::SCENT_VENUS, ProfileCode::SCENT_MARS, ProfileCode::ILLUSION_VENUS, ProfileCode::ILLUSION_MARS, ProfileCode::RECEPTOR_VENUS, ProfileCode::RECEPTOR_MARS, ProfileCode::BREACH_MARS, ProfileCode::FIRE_VENUS, ProfileCode::FIRE_MARS, ProfileCode::GATE_VENUS, ProfileCode::MOVEMENT_VENUS, ProfileCode::MOVEMENT_MARS, ProfileCode::TRANSPOSITION_VENUS, ProfileCode::TRANSPOSITION_MARS, ProfileCode::DISCHARGE_VENUS, ProfileCode::DISCHARGE_MARS, ProfileCode::WATCHER_MARS, ProfileCode::LOOK_VENUS, ProfileCode::LOOK_MARS, ProfileCode::TIME_MARS],
        ModifierCode::EXPLOSION => [ProfileCode::BARRIER_VENUS, ProfileCode::BARRIER_MARS, ProfileCode::SPARK_VENUS, ProfileCode::SPARK_MARS, ProfileCode::RELEASE_VENUS, ProfileCode::RELEASE_MARS, ProfileCode::SCENT_VENUS, ProfileCode::SCENT_MARS, ProfileCode::ILLUSION_VENUS, ProfileCode::ILLUSION_MARS, ProfileCode::RECEPTOR_VENUS, ProfileCode::RECEPTOR_MARS, ProfileCode::BREACH_VENUS, ProfileCode::BREACH_MARS, ProfileCode::FIRE_VENUS, ProfileCode::GATE_VENUS, ProfileCode::GATE_MARS, ProfileCode::MOVEMENT_VENUS, ProfileCode::MOVEMENT_MARS, ProfileCode::TRANSPOSITION_VENUS, ProfileCode::TRANSPOSITION_MARS, ProfileCode::DISCHARGE_VENUS, ProfileCode::DISCHARGE_MARS, ProfileCode::WATCHER_VENUS, ProfileCode::WATCHER_MARS, ProfileCode::LOOK_MARS, ProfileCode::TIME_VENUS, ProfileCode::TIME_MARS],
        ModifierCode::FILTER => [ProfileCode::BARRIER_VENUS, ProfileCode::SPARK_VENUS, ProfileCode::SPARK_MARS, ProfileCode::RELEASE_VENUS, ProfileCode::RELEASE_MARS, ProfileCode::SCENT_VENUS, ProfileCode::SCENT_MARS, ProfileCode::ILLUSION_VENUS, ProfileCode::ILLUSION_MARS, ProfileCode::RECEPTOR_VENUS, ProfileCode::RECEPTOR_MARS, ProfileCode::BREACH_VENUS, ProfileCode::BREACH_MARS, ProfileCode::FIRE_VENUS, ProfileCode::FIRE_MARS, ProfileCode::GATE_VENUS, ProfileCode::GATE_MARS, ProfileCode::MOVEMENT_VENUS, ProfileCode::MOVEMENT_MARS, ProfileCode::TRANSPOSITION_VENUS, ProfileCode::TRANSPOSITION_MARS, ProfileCode::DISCHARGE_VENUS, ProfileCode::DISCHARGE_MARS, ProfileCode::WATCHER_VENUS, ProfileCode::WATCHER_MARS, ProfileCode::LOOK_VENUS, ProfileCode::LOOK_MARS, ProfileCode::TIME_VENUS, ProfileCode::TIME_MARS],
        ModifierCode::WATCHER => [ProfileCode::BARRIER_VENUS, ProfileCode::BARRIER_MARS, ProfileCode::SPARK_VENUS, ProfileCode::SPARK_MARS, ProfileCode::RELEASE_VENUS, ProfileCode::RELEASE_MARS, ProfileCode::SCENT_VENUS, ProfileCode::SCENT_MARS, ProfileCode::ILLUSION_VENUS, ProfileCode::ILLUSION_MARS, ProfileCode::RECEPTOR_VENUS, ProfileCode::RECEPTOR_MARS, ProfileCode::BREACH_VENUS, ProfileCode::BREACH_MARS, ProfileCode::FIRE_VENUS, ProfileCode::FIRE_MARS, ProfileCode::GATE_VENUS, ProfileCode::GATE_MARS, ProfileCode::MOVEMENT_VENUS, ProfileCode::MOVEMENT_MARS, ProfileCode::TRANSPOSITION_VENUS, ProfileCode::TRANSPOSITION_MARS, ProfileCode::DISCHARGE_VENUS, ProfileCode::DISCHARGE_MARS, ProfileCode::WATCHER_VENUS, ProfileCode::LOOK_VENUS, ProfileCode::LOOK_MARS, ProfileCode::TIME_VENUS, ProfileCode::TIME_MARS],
        ModifierCode::THUNDER => [ProfileCode::BARRIER_VENUS, ProfileCode::BARRIER_MARS, ProfileCode::SPARK_VENUS, ProfileCode::SPARK_MARS, ProfileCode::RELEASE_VENUS, ProfileCode::RELEASE_MARS, ProfileCode::SCENT_VENUS, ProfileCode::SCENT_MARS, ProfileCode::ILLUSION_VENUS, ProfileCode::ILLUSION_MARS, ProfileCode::RECEPTOR_VENUS, ProfileCode::RECEPTOR_MARS, ProfileCode::BREACH_VENUS, ProfileCode::BREACH_MARS, ProfileCode::FIRE_VENUS, ProfileCode::FIRE_MARS, ProfileCode::GATE_VENUS, ProfileCode::GATE_MARS, ProfileCode::MOVEMENT_VENUS, ProfileCode::MOVEMENT_MARS, ProfileCode::TRANSPOSITION_VENUS, ProfileCode::TRANSPOSITION_MARS, ProfileCode::DISCHARGE_VENUS, ProfileCode::WATCHER_VENUS, ProfileCode::WATCHER_MARS, ProfileCode::LOOK_VENUS, ProfileCode::LOOK_MARS, ProfileCode::TIME_VENUS, ProfileCode::TIME_MARS],
        ModifierCode::INTERACTIVE_ILLUSION => [ProfileCode::BARRIER_VENUS, ProfileCode::BARRIER_MARS, ProfileCode::SPARK_VENUS, ProfileCode::SPARK_MARS, ProfileCode::RELEASE_VENUS, ProfileCode::RELEASE_MARS, ProfileCode::SCENT_VENUS, ProfileCode::SCENT_MARS, ProfileCode::ILLUSION_VENUS, ProfileCode::RECEPTOR_VENUS, ProfileCode::RECEPTOR_MARS, ProfileCode::BREACH_VENUS, ProfileCode::BREACH_MARS, ProfileCode::FIRE_VENUS, ProfileCode::FIRE_MARS, ProfileCode::GATE_VENUS, ProfileCode::GATE_MARS, ProfileCode::MOVEMENT_VENUS, ProfileCode::MOVEMENT_MARS, ProfileCode::TRANSPOSITION_VENUS, ProfileCode::TRANSPOSITION_MARS, ProfileCode::DISCHARGE_VENUS, ProfileCode::DISCHARGE_MARS, ProfileCode::WATCHER_VENUS, ProfileCode::WATCHER_MARS, ProfileCode::LOOK_VENUS, ProfileCode::LOOK_MARS, ProfileCode::TIME_VENUS, ProfileCode::TIME_MARS],
        ModifierCode::HAMMER => [ProfileCode::BARRIER_VENUS, ProfileCode::BARRIER_MARS, ProfileCode::SPARK_VENUS, ProfileCode::SPARK_MARS, ProfileCode::RELEASE_VENUS, ProfileCode::RELEASE_MARS, ProfileCode::SCENT_VENUS, ProfileCode::SCENT_MARS, ProfileCode::ILLUSION_VENUS, ProfileCode::ILLUSION_MARS, ProfileCode::RECEPTOR_VENUS, ProfileCode::RECEPTOR_MARS, ProfileCode::BREACH_VENUS, ProfileCode::BREACH_MARS, ProfileCode::FIRE_VENUS, ProfileCode::FIRE_MARS, ProfileCode::GATE_VENUS, ProfileCode::GATE_MARS, ProfileCode::MOVEMENT_VENUS, ProfileCode::MOVEMENT_MARS, ProfileCode::TRANSPOSITION_VENUS, ProfileCode::TRANSPOSITION_MARS, ProfileCode::DISCHARGE_VENUS, ProfileCode::WATCHER_MARS, ProfileCode::LOOK_VENUS, ProfileCode::LOOK_MARS, ProfileCode::TIME_VENUS, ProfileCode::TIME_MARS],
        ModifierCode::CAMOUFLAGE => [ProfileCode::BARRIER_VENUS, ProfileCode::SPARK_VENUS, ProfileCode::SPARK_MARS, ProfileCode::RELEASE_VENUS, ProfileCode::RELEASE_MARS, ProfileCode::SCENT_VENUS, ProfileCode::SCENT_MARS, ProfileCode::ILLUSION_VENUS, ProfileCode::ILLUSION_MARS, ProfileCode::RECEPTOR_VENUS, ProfileCode::RECEPTOR_MARS, ProfileCode::BREACH_VENUS, ProfileCode::BREACH_MARS, ProfileCode::FIRE_VENUS, ProfileCode::FIRE_MARS, ProfileCode::GATE_VENUS, ProfileCode::GATE_MARS, ProfileCode::MOVEMENT_VENUS, ProfileCode::MOVEMENT_MARS, ProfileCode::TRANSPOSITION_VENUS, ProfileCode::TRANSPOSITION_MARS, ProfileCode::DISCHARGE_VENUS, ProfileCode::DISCHARGE_MARS, ProfileCode::WATCHER_VENUS, ProfileCode::WATCHER_MARS, ProfileCode::LOOK_VENUS, ProfileCode::LOOK_MARS, ProfileCode::TIME_VENUS, ProfileCode::TIME_MARS],
        ModifierCode::INVISIBILITY => [ProfileCode::BARRIER_VENUS, ProfileCode::BARRIER_MARS, ProfileCode::SPARK_VENUS, ProfileCode::RELEASE_VENUS, ProfileCode::RELEASE_MARS, ProfileCode::SCENT_VENUS, ProfileCode::SCENT_MARS, ProfileCode::ILLUSION_VENUS, ProfileCode::ILLUSION_MARS, ProfileCode::RECEPTOR_VENUS, ProfileCode::RECEPTOR_MARS, ProfileCode::BREACH_VENUS, ProfileCode::BREACH_MARS, ProfileCode::FIRE_VENUS, ProfileCode::FIRE_MARS, ProfileCode::GATE_VENUS, ProfileCode::GATE_MARS, ProfileCode::MOVEMENT_VENUS, ProfileCode::MOVEMENT_MARS, ProfileCode::TRANSPOSITION_VENUS, ProfileCode::TRANSPOSITION_MARS, ProfileCode::DISCHARGE_VENUS, ProfileCode::DISCHARGE_MARS, ProfileCode::WATCHER_VENUS, ProfileCode::WATCHER_MARS, ProfileCode::LOOK_VENUS, ProfileCode::TIME_VENUS, ProfileCode::TIME_MARS],
        ModifierCode::MOVEMENT => [ProfileCode::BARRIER_VENUS, ProfileCode::BARRIER_MARS, ProfileCode::SPARK_VENUS, ProfileCode::RELEASE_VENUS, ProfileCode::RELEASE_MARS, ProfileCode::SCENT_VENUS, ProfileCode::SCENT_MARS, ProfileCode::ILLUSION_VENUS, ProfileCode::ILLUSION_MARS, ProfileCode::RECEPTOR_VENUS, ProfileCode::RECEPTOR_MARS, ProfileCode::BREACH_VENUS, ProfileCode::BREACH_MARS, ProfileCode::FIRE_VENUS, ProfileCode::FIRE_MARS, ProfileCode::GATE_VENUS, ProfileCode::GATE_MARS, ProfileCode::MOVEMENT_VENUS, ProfileCode::TRANSPOSITION_VENUS, ProfileCode::TRANSPOSITION_MARS, ProfileCode::DISCHARGE_VENUS, ProfileCode::DISCHARGE_MARS, ProfileCode::WATCHER_VENUS, ProfileCode::WATCHER_MARS, ProfileCode::LOOK_VENUS, ProfileCode::LOOK_MARS, ProfileCode::TIME_VENUS, ProfileCode::TIME_MARS],
        ModifierCode::BREACH => [ProfileCode::BARRIER_VENUS, ProfileCode::BARRIER_MARS, ProfileCode::SPARK_VENUS, ProfileCode::SPARK_MARS, ProfileCode::RELEASE_VENUS, ProfileCode::RELEASE_MARS, ProfileCode::SCENT_VENUS, ProfileCode::SCENT_MARS, ProfileCode::ILLUSION_VENUS, ProfileCode::ILLUSION_MARS, ProfileCode::RECEPTOR_VENUS, ProfileCode::RECEPTOR_MARS, ProfileCode::BREACH_VENUS, ProfileCode::FIRE_VENUS, ProfileCode::FIRE_MARS, ProfileCode::GATE_VENUS, ProfileCode::GATE_MARS, ProfileCode::MOVEMENT_VENUS, ProfileCode::MOVEMENT_MARS, ProfileCode::TRANSPOSITION_VENUS, ProfileCode::TRANSPOSITION_MARS, ProfileCode::DISCHARGE_VENUS, ProfileCode::DISCHARGE_MARS, ProfileCode::WATCHER_VENUS, ProfileCode::WATCHER_MARS, ProfileCode::LOOK_VENUS, ProfileCode::LOOK_MARS, ProfileCode::TIME_VENUS, ProfileCode::TIME_MARS],
        ModifierCode::RECEPTOR => [ProfileCode::BARRIER_VENUS, ProfileCode::BARRIER_MARS, ProfileCode::SPARK_VENUS, ProfileCode::SPARK_MARS, ProfileCode::RELEASE_VENUS, ProfileCode::RELEASE_MARS, ProfileCode::SCENT_VENUS, ProfileCode::SCENT_MARS, ProfileCode::ILLUSION_VENUS, ProfileCode::ILLUSION_MARS, ProfileCode::RECEPTOR_VENUS, ProfileCode::BREACH_VENUS, ProfileCode::BREACH_MARS, ProfileCode::FIRE_VENUS, ProfileCode::FIRE_MARS, ProfileCode::GATE_VENUS, ProfileCode::GATE_MARS, ProfileCode::MOVEMENT_VENUS, ProfileCode::MOVEMENT_MARS, ProfileCode::TRANSPOSITION_VENUS, ProfileCode::TRANSPOSITION_MARS, ProfileCode::DISCHARGE_VENUS, ProfileCode::DISCHARGE_MARS, ProfileCode::WATCHER_VENUS, ProfileCode::WATCHER_MARS, ProfileCode::LOOK_VENUS, ProfileCode::LOOK_MARS, ProfileCode::TIME_VENUS, ProfileCode::TIME_MARS],
        ModifierCode::STEP_TO_FUTURE => [ProfileCode::BARRIER_VENUS, ProfileCode::BARRIER_MARS, ProfileCode::SPARK_VENUS, ProfileCode::SPARK_MARS, ProfileCode::RELEASE_VENUS, ProfileCode::RELEASE_MARS, ProfileCode::SCENT_VENUS, ProfileCode::SCENT_MARS, ProfileCode::ILLUSION_VENUS, ProfileCode::ILLUSION_MARS, ProfileCode::RECEPTOR_VENUS, ProfileCode::RECEPTOR_MARS, ProfileCode::BREACH_VENUS, ProfileCode::BREACH_MARS, ProfileCode::FIRE_VENUS, ProfileCode::FIRE_MARS, ProfileCode::GATE_VENUS, ProfileCode::GATE_MARS, ProfileCode::MOVEMENT_VENUS, ProfileCode::MOVEMENT_MARS, ProfileCode::TRANSPOSITION_VENUS, ProfileCode::TRANSPOSITION_MARS, ProfileCode::DISCHARGE_VENUS, ProfileCode::DISCHARGE_MARS, ProfileCode::WATCHER_VENUS, ProfileCode::WATCHER_MARS, ProfileCode::LOOK_VENUS, ProfileCode::LOOK_MARS, ProfileCode::TIME_VENUS,],
        ModifierCode::STEP_TO_PAST => [ProfileCode::BARRIER_VENUS, ProfileCode::BARRIER_MARS, ProfileCode::SPARK_VENUS, ProfileCode::SPARK_MARS, ProfileCode::RELEASE_VENUS, ProfileCode::RELEASE_MARS, ProfileCode::SCENT_VENUS, ProfileCode::SCENT_MARS, ProfileCode::ILLUSION_VENUS, ProfileCode::ILLUSION_MARS, ProfileCode::RECEPTOR_VENUS, ProfileCode::RECEPTOR_MARS, ProfileCode::BREACH_VENUS, ProfileCode::BREACH_MARS, ProfileCode::FIRE_VENUS, ProfileCode::FIRE_MARS, ProfileCode::GATE_VENUS, ProfileCode::GATE_MARS, ProfileCode::MOVEMENT_VENUS, ProfileCode::MOVEMENT_MARS, ProfileCode::TRANSPOSITION_VENUS, ProfileCode::TRANSPOSITION_MARS, ProfileCode::DISCHARGE_VENUS, ProfileCode::DISCHARGE_MARS, ProfileCode::WATCHER_VENUS, ProfileCode::WATCHER_MARS, ProfileCode::LOOK_VENUS, ProfileCode::LOOK_MARS, ProfileCode::TIME_VENUS,],
        ModifierCode::TRANSPOSITION => [ProfileCode::BARRIER_VENUS, ProfileCode::BARRIER_MARS, ProfileCode::SPARK_VENUS, ProfileCode::RELEASE_VENUS, ProfileCode::RELEASE_MARS, ProfileCode::SCENT_VENUS, ProfileCode::SCENT_MARS, ProfileCode::ILLUSION_VENUS, ProfileCode::ILLUSION_MARS, ProfileCode::RECEPTOR_VENUS, ProfileCode::RECEPTOR_MARS, ProfileCode::BREACH_VENUS, ProfileCode::BREACH_MARS, ProfileCode::FIRE_VENUS, ProfileCode::FIRE_MARS, ProfileCode::GATE_VENUS, ProfileCode::GATE_MARS, ProfileCode::MOVEMENT_VENUS, ProfileCode::MOVEMENT_MARS, ProfileCode::DISCHARGE_VENUS, ProfileCode::DISCHARGE_MARS, ProfileCode::WATCHER_VENUS, ProfileCode::WATCHER_MARS, ProfileCode::LOOK_VENUS, ProfileCode::LOOK_MARS, ProfileCode::TIME_VENUS, ProfileCode::TIME_MARS],
        ModifierCode::RELEASE => [ProfileCode::BARRIER_VENUS, ProfileCode::BARRIER_MARS, ProfileCode::SPARK_VENUS, ProfileCode::SPARK_MARS, ProfileCode::RELEASE_VENUS, ProfileCode::SCENT_VENUS, ProfileCode::SCENT_MARS, ProfileCode::ILLUSION_VENUS, ProfileCode::ILLUSION_MARS, ProfileCode::RECEPTOR_VENUS, ProfileCode::RECEPTOR_MARS, ProfileCode::BREACH_VENUS, ProfileCode::BREACH_MARS, ProfileCode::FIRE_VENUS, ProfileCode::FIRE_MARS, ProfileCode::GATE_VENUS, ProfileCode::GATE_MARS, ProfileCode::MOVEMENT_VENUS, ProfileCode::MOVEMENT_MARS, ProfileCode::TRANSPOSITION_VENUS, ProfileCode::TRANSPOSITION_MARS, ProfileCode::DISCHARGE_VENUS, ProfileCode::DISCHARGE_MARS, ProfileCode::WATCHER_VENUS, ProfileCode::WATCHER_MARS, ProfileCode::LOOK_VENUS, ProfileCode::LOOK_MARS, ProfileCode::TIME_VENUS, ProfileCode::TIME_MARS],
        ModifierCode::FRAGRANCE => [ProfileCode::BARRIER_VENUS, ProfileCode::BARRIER_MARS, ProfileCode::SPARK_VENUS, ProfileCode::SPARK_MARS, ProfileCode::RELEASE_VENUS, ProfileCode::RELEASE_MARS, ProfileCode::SCENT_VENUS, ProfileCode::ILLUSION_VENUS, ProfileCode::ILLUSION_MARS, ProfileCode::RECEPTOR_MARS, ProfileCode::BREACH_VENUS, ProfileCode::BREACH_MARS, ProfileCode::FIRE_VENUS, ProfileCode::FIRE_MARS, ProfileCode::GATE_VENUS, ProfileCode::GATE_MARS, ProfileCode::MOVEMENT_VENUS, ProfileCode::MOVEMENT_MARS, ProfileCode::TRANSPOSITION_VENUS, ProfileCode::TRANSPOSITION_MARS, ProfileCode::DISCHARGE_VENUS, ProfileCode::DISCHARGE_MARS, ProfileCode::WATCHER_VENUS, ProfileCode::WATCHER_MARS, ProfileCode::LOOK_VENUS, ProfileCode::LOOK_MARS, ProfileCode::TIME_VENUS, ProfileCode::TIME_MARS],
    ];

    /**
     * @param string $modifierValue
     * @return array|string[]
     */
    private function getExpectedProfileValues(string $modifierValue): array
    {
        $expectedProfileValues = array_diff(ProfileCode::getPossibleValues(), self::$impossibleProfiles[$modifierValue]);
        sort($expectedProfileValues);
        $profileValuesFromProfilesTable = $this->getProfileValuesFromProfilesTable($modifierValue);
        sort($profileValuesFromProfilesTable);
        self::assertSame($expectedProfileValues, $profileValuesFromProfilesTable);

        return $expectedProfileValues;
    }

    /**
     * @param string $modifierValue
     * @return array
     */
    private function getProfileValuesFromProfilesTable(string $modifierValue): array
    {
        $expectedProfileValues = [];
        $profilesTable = new ProfilesTable();
        foreach (ProfileCode::getPossibleValues() as $profileValue) {
            $modifierCodes = $profilesTable->getModifierCodes(ProfileCode::getIt($profileValue));
            foreach ($modifierCodes as $modifierCode) {
                if ($modifierCode->getValue() === $modifierValue) {
                    $expectedProfileValues[] = $this->reverseProfileGender($profileValue);
                    break;
                }
            }
        }

        return $expectedProfileValues;
    }

    /**
     * @test
     */
    public function I_can_not_get_profiles_to_unknown_modifiers()
    {
        $this->expectException(\DrdPlus\Tables\Theurgist\Spells\Exceptions\UnknownModifierToGetProfilesFor::class);
        $this->expectExceptionMessageRegExp('~magnified~');
        (new ModifiersTable(Tables::getIt()))->getProfileCodes($this->createModifierCode('magnified'));
    }

    /**
     * @test
     */
    public function I_can_get_parent_modifiers()
    {
        $modifiersTable = new ModifiersTable(Tables::getIt());
        $fromParentMatchingProfiles = [];
        foreach (ModifierCode::getPossibleValues() as $modifierValue) {
            $profileValues = $this->getExpectedProfileValues($modifierValue);
            $fromParentToModifierMatchingProfiles = [];
            $modifier = ModifierCode::getIt($modifierValue);
            $parentModifiers = $modifiersTable->getParentModifierCodes($modifier);
            foreach ($parentModifiers as $parentModifier) {
                self::assertContains($modifier, $modifiersTable->getChildModifierCodes($parentModifier));
                $parentProfileValues = $this->getExpectedProfileValues($parentModifier->getValue());
                $matchingProfile = null;
                foreach ($parentProfileValues as $parentProfileValue) {
                    if (in_array($this->reverseProfileGender($parentProfileValue), $profileValues, true)) {
                        if ($matchingProfile !== null && $modifierValue !== ModifierCode::TRANSPOSITION) {
                            throw new \LogicException(
                                "For modifier '{$modifierValue}' has been already found matching parent profile '{$matchingProfile}'"
                                . " so can not use '{$parentProfileValue}'"
                            );
                        }
                        if ($parentProfileValue === ProfileCode::TRANSPOSITION_MARS) {
                            continue;
                        }
                        $matchingProfile = $parentProfileValue;
                    }
                }
                if ($matchingProfile === null) {
                    throw new \LogicException(
                        "No connecting profile has been found for modifier '{$modifierValue}' and its parent modifier '{$parentModifier}'"
                    );
                }
                if (array_key_exists($parentModifier->getValue(), $fromParentToModifierMatchingProfiles)) {
                    throw new \LogicException(
                        "Modifier '{$modifierValue}' is already connected with '{$parentModifier}' via profile "
                        . $fromParentToModifierMatchingProfiles[$parentModifier->getValue()] . "', so can not connect it also via '{$matchingProfile}'"
                    );
                }
                $fromParentToModifierMatchingProfiles[$parentModifier->getValue()] = $matchingProfile;
            }
            foreach ($fromParentToModifierMatchingProfiles as $fromParentToModifierMatchingProfile) {
                $fromParentMatchingProfiles[] = $fromParentToModifierMatchingProfile;
            }
        }
        foreach ($fromParentMatchingProfiles as $matchingProfile) {
            self::assertStringContainsString(
                '_venus',
                $matchingProfile,
                'Only venus profiles can be used on parent modifier side for connection (and mars on child side)'
            );
        }
    }

    /**
     * @test
     */
    public function I_can_get_child_modifiers()
    {
        $modifiersTable = new ModifiersTable(Tables::getIt());
        $fromChildrenMatchingProfiles = [];
        foreach (ModifierCode::getPossibleValues() as $modifierValue) {
            $profileValues = $this->getExpectedProfileValues($modifierValue);
            $fromChildToModifierMatchingProfiles = [];
            $modifier = ModifierCode::getIt($modifierValue);
            $childModifiers = $modifiersTable->getChildModifierCodes($modifier);
            foreach ($childModifiers as $childModifier) {
                self::assertContains($modifier, $modifiersTable->getParentModifierCodes($childModifier));
                $childProfileValues = $this->getExpectedProfileValues($childModifier->getValue());
                $matchingProfile = null;
                foreach ($childProfileValues as $childProfileValue) {
                    if (in_array($this->reverseProfileGender($childProfileValue), $profileValues, true)) {
                        if ($matchingProfile !== null && $modifierValue !== ModifierCode::TRANSPOSITION) {
                            throw new \LogicException(
                                "For modifier '{$modifierValue}' has been already found matching child profile '{$matchingProfile}'"
                                . " so can not use '{$childProfileValue}'"
                            );
                        }
                        if ($childProfileValue === ProfileCode::TRANSPOSITION_VENUS) {
                            continue;
                        }
                        $matchingProfile = $childProfileValue;
                    }
                }
                if ($matchingProfile === null) {
                    throw new \LogicException(
                        "No connecting profile has been found for modifier '{$modifierValue}' and its child modifier '{$childModifier}'"
                    );
                }
                if (array_key_exists($childModifier->getValue(), $fromChildToModifierMatchingProfiles)) {
                    throw new \LogicException(
                        "Modifier '{$modifierValue}' is already connected with '{$childModifier}' via profile "
                        . $fromChildToModifierMatchingProfiles[$childModifier->getValue()] . "', so can not connect it also via '{$matchingProfile}'"
                    );
                }
                $fromChildToModifierMatchingProfiles[$childModifier->getValue()] = $matchingProfile;
            }
            foreach ($fromChildToModifierMatchingProfiles as $fromChildToModifierMatchingProfile) {
                $fromChildrenMatchingProfiles[] = $fromChildToModifierMatchingProfile;
            }
        }
        foreach ($fromChildrenMatchingProfiles as $matchingProfile) {
            self::assertStringContainsString(
                '_mars',
                $matchingProfile,
                'Only mars profiles can be used by child modifiers to connect to parent (and venus on parent side)'
            );
        }
    }

    /**
     * @test
     */
    public function I_can_not_get_parent_modifiers_for_unknown_modifier()
    {
        $this->expectException(\DrdPlus\Tables\Theurgist\Spells\Exceptions\UnknownModifierToGetParentModifiersFor::class);
        $this->expectExceptionMessageRegExp('~dancing~');
        $modifiersTable = new ModifiersTable(Tables::getIt());
        $modifiersTable->getParentModifierCodes($this->createModifierCode('dancing'));
    }

    /**
     * @test
     */
    public function I_can_not_get_child_modifiers_for_unknown_modifier()
    {
        $this->expectException(\DrdPlus\Tables\Theurgist\Spells\Exceptions\UnknownModifierToGetChildModifiersFor::class);
        $this->expectExceptionMessageRegExp('~lazy~');
        $modifiersTable = new ModifiersTable(Tables::getIt());
        $modifiersTable->getChildModifierCodes($this->createModifierCode('lazy'));
    }

}