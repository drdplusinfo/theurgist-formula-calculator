<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells;

use DrdPlus\Tables\Measurements\Time\Time;
use DrdPlus\Tables\Tables;
use DrdPlus\Codes\Theurgist\FormCode;
use DrdPlus\Codes\Theurgist\FormulaCode;
use DrdPlus\Codes\Theurgist\ModifierCode;
use DrdPlus\Codes\Theurgist\ProfileCode;
use DrdPlus\Codes\Theurgist\SpellTraitCode;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\CastingRounds;
use DrdPlus\Tables\Theurgist\Spells\FormulasTable;
use DrdPlus\Tables\Theurgist\Spells\ModifiersTable;
use DrdPlus\Tables\Theurgist\Spells\ProfilesTable;
use DrdPlus\Tables\Theurgist\Spells\SpellTrait;
use DrdPlus\Tests\Tables\Theurgist\AbstractTheurgistTableTest;

class FormulasTableTest extends AbstractTheurgistTableTest
{

    /**
     * @var ModifiersTable
     */
    private $modifiersTable;

    protected function setUp(): void
    {
        $this->modifiersTable = new ModifiersTable(Tables::getIt());
    }

    protected function getMainCodeClass(): string
    {
        return FormulaCode::class;
    }

    protected function getMandatoryParameters(): array
    {
        return [FormulasTable::REALM, FormulasTable::REALMS_AFFECTION, FormulasTable::EVOCATION, FormulasTable::DIFFICULTY, FormulasTable::SPELL_DURATION];
    }

    protected function getOptionalParameters(): array
    {
        return [
            FormulasTable::SPELL_RADIUS,
            FormulasTable::SPELL_POWER,
            FormulasTable::SPELL_ATTACK,
            FormulasTable::SIZE_CHANGE,
            FormulasTable::DETAIL_LEVEL,
            FormulasTable::SPELL_BRIGHTNESS,
            FormulasTable::SPELL_SPEED,
            FormulasTable::EPICENTER_SHIFT,
        ];
    }

    /**
     * @test
     */
    public function I_can_get_casting_rounds(): void
    {
        $formulasTable = new FormulasTable(Tables::getIt());
        foreach (FormulaCode::getPossibleValues() as $formulaValue) {
            $castingRounds = $formulasTable->getCastingRounds(FormulaCode::getIt($formulaValue));
            self::assertEquals(new CastingRounds([1, 0], Tables::getIt()), $castingRounds);
            self::assertEquals(
                new Time(1, Time::ROUND, Tables::getIt()->getTimeTable()),
                $castingRounds->getTimeBonus()->getTime(Time::ROUND)
            );
        }
    }

    /**
     * @test
     */
    public function I_can_get_forms(): void
    {
        $formulasTable = new FormulasTable(Tables::getIt());
        foreach (FormulaCode::getPossibleValues() as $formulaValue) {
            $forms = $formulasTable->getFormCodes(FormulaCode::getIt($formulaValue));
            $formValues = [];
            foreach ($forms as $form) {
                self::assertInstanceOf(FormCode::class, $form);
                $formValues[] = $form->getValue();
            }
            self::assertSame($formValues, array_unique($formValues));
            sort($formValues);
            $expectedFormValues = $this->getExpectedFormValues($formulaValue);
            sort($expectedFormValues);
            self::assertEquals($expectedFormValues, $formValues, "Expected different forms for '{$formulaValue}'");
        }
    }

    private static $excludedFormValues = [
        FormulaCode::BARRIER => ['direct', 'volume', 'beam', 'intangible', 'invisible', 'by_formula'],
        FormulaCode::SMOKE => ['direct', 'planar', 'beam', 'intangible', 'invisible', 'by_formula'],
        FormulaCode::ILLUSION => ['direct', 'planar', 'beam', 'intangible', 'invisible', 'by_formula'],
        FormulaCode::METAMORPHOSIS => ['indirect', 'planar', 'beam', 'tangible', 'visible', 'by_formula'],
        FormulaCode::FIRE => ['direct', 'planar', 'beam', 'intangible', 'invisible', 'by_formula'],
        FormulaCode::PORTAL => ['direct', 'planar', 'beam', 'tangible', 'visible', 'by_formula'],
        FormulaCode::LIGHT => ['direct', 'planar', 'beam', 'intangible', 'invisible', 'by_formula'],
        FormulaCode::FLOW_OF_TIME => ['indirect', 'planar', 'beam', 'tangible', 'visible', 'by_formula'],
        FormulaCode::TSUNAMI_FROM_CLAY_AND_STONES => ['direct', 'planar', 'beam', 'tangible', 'visible', 'by_formula'],
        FormulaCode::HIT => ['direct', 'volume', 'planar', 'intangible', 'visible', 'by_formula'],
        FormulaCode::GREAT_MASSACRE => ['direct', 'planar', 'beam', 'intangible', 'invisible', 'by_formula'],
        FormulaCode::DISCHARGE => ['direct', 'volume', 'planar', 'intangible', 'invisible', 'by_formula'],
        FormulaCode::LOCK => ['indirect', 'volume', 'planar', 'tangible', 'visible', 'by_formula'],
    ];

    private function getExpectedFormValues(string $formulaValue): array
    {
        $excludedFormValues = self::$excludedFormValues[$formulaValue];

        return array_diff(FormCode::getPossibleValues(), $excludedFormValues);
    }

    /**
     * @test
     */
    public function I_can_get_spell_trait_codes(): void
    {
        $formulasTable = new FormulasTable(Tables::getIt());
        foreach (FormulaCode::getPossibleValues() as $formulaValue) {
            $spellTraits = $formulasTable->getSpellTraits(FormulaCode::getIt($formulaValue));
            /** @var array|string[] $expectedTraitValues */
            $expectedTraitValues = $this->getValueFromTable($formulasTable, $formulaValue, FormulasTable::SPELL_TRAITS);
            $expectedSpellTraits = [];
            foreach ($expectedTraitValues as $expectedTraitValue) {
                $expectedSpellTraits[] = new SpellTrait(SpellTraitCode::getIt($expectedTraitValue), Tables::getIt());
            }
            self::assertEquals($expectedSpellTraits, $spellTraits);

            $spellTraitCodeValues = [];
            foreach ($spellTraits as $spellTrait) {
                self::assertInstanceOf(SpellTrait::class, $spellTrait);
                $spellTraitCodeValues[] = $spellTrait->getSpellTraitCode()->getValue();
            }
            self::assertSame($spellTraitCodeValues, array_unique($spellTraitCodeValues));
            sort($spellTraitCodeValues);
            $expectedSpellTraitCodeValues = $this->getExpectedSpellTraitCodeValues($formulaValue);
            sort($expectedSpellTraitCodeValues);
            self::assertEquals($expectedSpellTraitCodeValues, $spellTraitCodeValues, "Expected different traits for '{$formulaValue}'");
        }
    }

    private static $excludedTraitValues = [
        FormulaCode::BARRIER => [SpellTraitCode::AFFECTING, SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::CYCLIC, SpellTraitCode::MEMORY, SpellTraitCode::DEFORMATION, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::NO_SMOKE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::OMNIPRESENT, SpellTraitCode::ACTIVE],
        FormulaCode::SMOKE => [SpellTraitCode::AFFECTING, SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::CYCLIC, SpellTraitCode::MEMORY, SpellTraitCode::DEFORMATION, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::NO_SMOKE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::OMNIPRESENT, SpellTraitCode::ACTIVE],
        FormulaCode::ILLUSION => [SpellTraitCode::AFFECTING, SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::CYCLIC, SpellTraitCode::MEMORY, SpellTraitCode::DEFORMATION, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::NO_SMOKE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::OMNIPRESENT, SpellTraitCode::ACTIVE],
        FormulaCode::METAMORPHOSIS => [SpellTraitCode::AFFECTING, SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::CYCLIC, SpellTraitCode::MEMORY, SpellTraitCode::DEFORMATION, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::NO_SMOKE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::OMNIPRESENT, SpellTraitCode::ACTIVE],
        FormulaCode::FIRE => [SpellTraitCode::AFFECTING, SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::CYCLIC, SpellTraitCode::MEMORY, SpellTraitCode::DEFORMATION, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::OMNIPRESENT, SpellTraitCode::ACTIVE],
        FormulaCode::PORTAL => [SpellTraitCode::AFFECTING, SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::CYCLIC, SpellTraitCode::MEMORY, SpellTraitCode::DEFORMATION, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::NO_SMOKE, SpellTraitCode::OMNIPRESENT, SpellTraitCode::ACTIVE],
        FormulaCode::LIGHT => [SpellTraitCode::AFFECTING, SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::CYCLIC, SpellTraitCode::MEMORY, SpellTraitCode::DEFORMATION, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::NO_SMOKE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::ACTIVE],
        FormulaCode::FLOW_OF_TIME => [SpellTraitCode::AFFECTING, SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::CYCLIC, SpellTraitCode::MEMORY, SpellTraitCode::DEFORMATION, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::NO_SMOKE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::OMNIPRESENT, SpellTraitCode::ACTIVE],
        FormulaCode::TSUNAMI_FROM_CLAY_AND_STONES => [SpellTraitCode::AFFECTING, SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::CYCLIC, SpellTraitCode::MEMORY, SpellTraitCode::DEFORMATION, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::NO_SMOKE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::OMNIPRESENT, SpellTraitCode::ACTIVE],
        FormulaCode::HIT => [SpellTraitCode::AFFECTING, SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::CYCLIC, SpellTraitCode::MEMORY, SpellTraitCode::DEFORMATION, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::NO_SMOKE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::OMNIPRESENT, SpellTraitCode::ACTIVE],
        FormulaCode::GREAT_MASSACRE => [SpellTraitCode::AFFECTING, SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::CYCLIC, SpellTraitCode::MEMORY, SpellTraitCode::DEFORMATION, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::NO_SMOKE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::OMNIPRESENT, SpellTraitCode::ACTIVE],
        FormulaCode::DISCHARGE => [SpellTraitCode::AFFECTING, SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::CYCLIC, SpellTraitCode::MEMORY, SpellTraitCode::DEFORMATION, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::NO_SMOKE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::OMNIPRESENT, SpellTraitCode::ACTIVE],
        FormulaCode::LOCK => [SpellTraitCode::AFFECTING, SpellTraitCode::INVISIBLE, SpellTraitCode::SILENT, SpellTraitCode::ODORLESS, SpellTraitCode::CYCLIC, SpellTraitCode::MEMORY, SpellTraitCode::DEFORMATION, SpellTraitCode::UNIDIRECTIONAL, SpellTraitCode::BIDIRECTIONAL, SpellTraitCode::INACRID, SpellTraitCode::EVERY_SENSE, SpellTraitCode::SITUATIONAL, SpellTraitCode::SHAPESHIFT, SpellTraitCode::STATE_CHANGE, SpellTraitCode::NATURE_CHANGE, SpellTraitCode::NO_SMOKE, SpellTraitCode::TRANSPARENCY, SpellTraitCode::MULTIPLE_ENTRY, SpellTraitCode::OMNIPRESENT],
    ];

    private function getExpectedSpellTraitCodeValues(string $formulaValue): array
    {
        $excludedTraitValues = self::$excludedTraitValues[$formulaValue];

        return array_diff(SpellTraitCode::getPossibleValues(), $excludedTraitValues);
    }

    /**
     * @test
     */
    public function I_can_get_modifiers_for_formula(): void
    {
        $formulasTable = new FormulasTable(Tables::getIt());
        foreach (FormulaCode::getPossibleValues() as $formulaValue) {
            $modifierCodes = $formulasTable->getModifierCodes(FormulaCode::getIt($formulaValue));
            self::assertIsArray($modifierCodes);
            self::assertNotEmpty($modifierCodes);
            $collectedModifierValues = [];
            /** @var ModifierCode $modifierCode */
            foreach ($modifierCodes as $modifierCode) {
                self::assertInstanceOf(ModifierCode::class, $modifierCode);
                $collectedModifierValues[] = $modifierCode->getValue();
            }
            sort($collectedModifierValues);
            $possibleModifierValues = $this->getExpectedModifierValues($formulaValue);
            sort($possibleModifierValues);
            self::assertEquals(
                $possibleModifierValues,
                $collectedModifierValues,
                'Expected different modifiers for formula ' . $formulaValue
            );

            $matchingModifierValues = $this->getModifiersFromProfilesTable($formulaValue);
            sort($matchingModifierValues);
            self::assertEquals(
                $matchingModifierValues,
                $collectedModifierValues,
                'Expected different modifiers for formula ' . $formulaValue
            );
        }
    }

    private static $impossibleModifiers = [
        FormulaCode::BARRIER => [ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::BREACH, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::FRAGRANCE],
        FormulaCode::SMOKE => [ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::CAMOUFLAGE, ModifierCode::BREACH, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST],
        FormulaCode::ILLUSION => [ModifierCode::COLOR, ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::THUNDER, ModifierCode::HAMMER, ModifierCode::CAMOUFLAGE, ModifierCode::INVISIBILITY, ModifierCode::MOVEMENT, ModifierCode::BREACH, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::TRANSPOSITION, ModifierCode::RELEASE, ModifierCode::FRAGRANCE],
        FormulaCode::METAMORPHOSIS => [ModifierCode::COLOR, ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::CAMOUFLAGE, ModifierCode::INVISIBILITY, ModifierCode::MOVEMENT, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::TRANSPOSITION, ModifierCode::FRAGRANCE],
        FormulaCode::FIRE => [ModifierCode::GATE, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::CAMOUFLAGE, ModifierCode::BREACH, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::FRAGRANCE],
        FormulaCode::PORTAL => [ModifierCode::COLOR, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::CAMOUFLAGE, ModifierCode::INVISIBILITY, ModifierCode::MOVEMENT, ModifierCode::BREACH, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::TRANSPOSITION, ModifierCode::RELEASE, ModifierCode::FRAGRANCE],
        FormulaCode::LIGHT => [ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::CAMOUFLAGE, ModifierCode::BREACH, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::FRAGRANCE],
        FormulaCode::FLOW_OF_TIME => [ModifierCode::COLOR, ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::CAMOUFLAGE, ModifierCode::INVISIBILITY, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::FRAGRANCE],
        FormulaCode::TSUNAMI_FROM_CLAY_AND_STONES => [ModifierCode::COLOR, ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::CAMOUFLAGE, ModifierCode::INVISIBILITY, ModifierCode::MOVEMENT, ModifierCode::BREACH, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::FRAGRANCE],
        FormulaCode::HIT => [ModifierCode::COLOR, ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::CAMOUFLAGE, ModifierCode::INVISIBILITY, ModifierCode::MOVEMENT, ModifierCode::BREACH, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::RELEASE, ModifierCode::FRAGRANCE],
        FormulaCode::GREAT_MASSACRE => [ModifierCode::COLOR, ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::CAMOUFLAGE, ModifierCode::INVISIBILITY, ModifierCode::BREACH, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::FRAGRANCE],
        FormulaCode::DISCHARGE => [ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::WATCHER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::CAMOUFLAGE, ModifierCode::BREACH, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::FRAGRANCE],
        FormulaCode::LOCK => [ModifierCode::COLOR, ModifierCode::GATE, ModifierCode::EXPLOSION, ModifierCode::FILTER, ModifierCode::THUNDER, ModifierCode::INTERACTIVE_ILLUSION, ModifierCode::HAMMER, ModifierCode::CAMOUFLAGE, ModifierCode::INVISIBILITY, ModifierCode::MOVEMENT, ModifierCode::RECEPTOR, ModifierCode::STEP_TO_FUTURE, ModifierCode::STEP_TO_PAST, ModifierCode::RELEASE, ModifierCode::FRAGRANCE],
    ];

    /**
     * @param string $formulaValue
     * @return array|string[]
     */
    private function getExpectedModifierValues(string $formulaValue): array
    {
        $expectedModifierValues = array_diff(ModifierCode::getPossibleValues(), self::$impossibleModifiers[$formulaValue]);
        sort($expectedModifierValues);
        $modifierValuesFromModifiersTable = $this->getModifierValuesFromModifiersTable($formulaValue);
        sort($modifierValuesFromModifiersTable);
        self::assertSame($expectedModifierValues, $modifierValuesFromModifiersTable);

        return $expectedModifierValues;
    }

    /**
     * @param string $formulaValue
     * @return array
     */
    private function getModifierValuesFromModifiersTable(string $formulaValue): array
    {
        $modifierValues = [];
        foreach (ModifierCode::getPossibleValues() as $modifierValue) {
            $formulaCodes = $this->modifiersTable->getFormulaCodes(ModifierCode::getIt($modifierValue));
            foreach ($formulaCodes as $formulaCode) {
                if ($formulaCode->getValue() === $formulaValue) {
                    $modifierValues[] = $modifierValue;
                    break;
                }
            }
        }

        return $modifierValues;
    }

    /**
     * @param string $formulaValue
     * @return array
     */
    private function getModifiersFromProfilesTable(string $formulaValue): array
    {
        $matchingProfileValues = $this->getProfilesByProfileTable($formulaValue);
        $profilesTable = new ProfilesTable();
        $matchingModifierValues = [];
        foreach ($matchingProfileValues as $matchingProfileValue) {
            foreach ($profilesTable->getModifierCodes(ProfileCode::getIt($matchingProfileValue)) as $modifierCode) {
                $matchingModifierValues[] = $modifierCode->getValue();
            }
        }

        return array_unique($matchingModifierValues);
    }

    /**
     * @test
     */
    public function I_can_not_get_modifiers_to_unknown_formula(): void
    {
        $this->expectException(\DrdPlus\Tables\Theurgist\Spells\Exceptions\UnknownFormulaToGetModifiersFor::class);
        $this->expectExceptionMessageRegExp('~Abraka dabra~');
        (new FormulasTable(Tables::getIt()))
            ->getModifierCodes($this->createFormulaCode('Abraka dabra'));
    }

    /**
     * @param string $value
     * @return \Mockery\MockInterface|FormulaCode
     */
    private function createFormulaCode(string $value): FormulaCode
    {
        $formulaCode = $this->mockery(FormulaCode::class);
        $formulaCode->shouldReceive('getValue')
            ->andReturn($value);
        $formulaCode->shouldReceive('__toString')
            ->andReturn($value);

        return $formulaCode;
    }

    /**
     * @test
     */
    public function I_can_get_profiles_for_formula(): void
    {
        $formulasTable = new FormulasTable(Tables::getIt());
        foreach (FormulaCode::getPossibleValues() as $formulaValue) {
            $profileCodes = $formulasTable->getProfileCodes(FormulaCode::getIt($formulaValue));
            self::assertIsArray($profileCodes);
            self::assertNotEmpty($profileCodes);
            $profileValues = [];
            foreach ($profileCodes as $profileCode) {
                self::assertInstanceOf(ProfileCode::class, $profileCode);
                $profileValues[] = $profileCode->getValue();
            }
            sort($profileValues);
            $expectedProfiles = $this->getExpectedProfilesFor($formulaValue);
            sort($expectedProfiles);
            self::assertEquals(
                $expectedProfiles,
                $profileValues,
                "Expected different profiles for formula '{$formulaValue}'"
            );
            $profilesByProfileTable = $this->getProfilesByProfileTable($formulaValue);
            sort($profilesByProfileTable);
            self::assertEquals(
                $profilesByProfileTable,
                $profileValues,
                "Expected different profiles for formula '{$formulaValue}'"
            );
        }
    }

    private static $impossibleVenusProfiles = [
        FormulaCode::BARRIER => [ProfileCode::SCENT_VENUS, ProfileCode::ILLUSION_VENUS, ProfileCode::RECEPTOR_VENUS, ProfileCode::BREACH_VENUS, ProfileCode::FIRE_VENUS, ProfileCode::GATE_VENUS, ProfileCode::MOVEMENT_VENUS, ProfileCode::TRANSPOSITION_VENUS, ProfileCode::DISCHARGE_VENUS, ProfileCode::WATCHER_VENUS, ProfileCode::LOOK_VENUS, ProfileCode::TIME_VENUS],
        FormulaCode::SMOKE => [ProfileCode::BARRIER_VENUS, ProfileCode::ILLUSION_VENUS, ProfileCode::RECEPTOR_VENUS, ProfileCode::BREACH_VENUS, ProfileCode::FIRE_VENUS, ProfileCode::GATE_VENUS, ProfileCode::MOVEMENT_VENUS, ProfileCode::TRANSPOSITION_VENUS, ProfileCode::DISCHARGE_VENUS, ProfileCode::WATCHER_VENUS, ProfileCode::LOOK_VENUS, ProfileCode::TIME_VENUS],
        FormulaCode::ILLUSION => [ProfileCode::BARRIER_VENUS, ProfileCode::SPARK_VENUS, ProfileCode::RELEASE_VENUS, ProfileCode::SCENT_VENUS, ProfileCode::BREACH_VENUS, ProfileCode::FIRE_VENUS, ProfileCode::GATE_VENUS, ProfileCode::MOVEMENT_VENUS, ProfileCode::TRANSPOSITION_VENUS, ProfileCode::DISCHARGE_VENUS, ProfileCode::LOOK_VENUS, ProfileCode::TIME_VENUS],
        FormulaCode::METAMORPHOSIS => [ProfileCode::BARRIER_VENUS, ProfileCode::SPARK_VENUS, ProfileCode::SCENT_VENUS, ProfileCode::ILLUSION_VENUS, ProfileCode::FIRE_VENUS, ProfileCode::GATE_VENUS, ProfileCode::MOVEMENT_VENUS, ProfileCode::TRANSPOSITION_VENUS, ProfileCode::DISCHARGE_VENUS, ProfileCode::WATCHER_VENUS, ProfileCode::LOOK_VENUS, ProfileCode::TIME_VENUS],
        FormulaCode::FIRE => [ProfileCode::BARRIER_VENUS, ProfileCode::SCENT_VENUS, ProfileCode::ILLUSION_VENUS, ProfileCode::RECEPTOR_VENUS, ProfileCode::BREACH_VENUS, ProfileCode::GATE_VENUS, ProfileCode::MOVEMENT_VENUS, ProfileCode::TRANSPOSITION_VENUS, ProfileCode::DISCHARGE_VENUS, ProfileCode::WATCHER_VENUS, ProfileCode::LOOK_VENUS, ProfileCode::TIME_VENUS],
        FormulaCode::PORTAL => [ProfileCode::BARRIER_VENUS, ProfileCode::SPARK_VENUS, ProfileCode::RELEASE_VENUS, ProfileCode::SCENT_VENUS, ProfileCode::ILLUSION_VENUS, ProfileCode::RECEPTOR_VENUS, ProfileCode::BREACH_VENUS, ProfileCode::FIRE_VENUS, ProfileCode::MOVEMENT_VENUS, ProfileCode::TRANSPOSITION_VENUS, ProfileCode::DISCHARGE_VENUS, ProfileCode::WATCHER_VENUS, ProfileCode::LOOK_VENUS, ProfileCode::TIME_VENUS],
        FormulaCode::LIGHT => [ProfileCode::BARRIER_VENUS, ProfileCode::SCENT_VENUS, ProfileCode::ILLUSION_VENUS, ProfileCode::RECEPTOR_VENUS, ProfileCode::BREACH_VENUS, ProfileCode::FIRE_VENUS, ProfileCode::GATE_VENUS, ProfileCode::MOVEMENT_VENUS, ProfileCode::TRANSPOSITION_VENUS, ProfileCode::DISCHARGE_VENUS, ProfileCode::WATCHER_VENUS, ProfileCode::LOOK_VENUS, ProfileCode::TIME_VENUS],
        FormulaCode::FLOW_OF_TIME => [ProfileCode::BARRIER_VENUS, ProfileCode::SPARK_VENUS, ProfileCode::SCENT_VENUS, ProfileCode::ILLUSION_VENUS, ProfileCode::RECEPTOR_VENUS, ProfileCode::FIRE_VENUS, ProfileCode::GATE_VENUS, ProfileCode::DISCHARGE_VENUS, ProfileCode::WATCHER_VENUS, ProfileCode::LOOK_VENUS, ProfileCode::TIME_VENUS],
        FormulaCode::TSUNAMI_FROM_CLAY_AND_STONES => [ProfileCode::BARRIER_VENUS, ProfileCode::SPARK_VENUS, ProfileCode::SCENT_VENUS, ProfileCode::ILLUSION_VENUS, ProfileCode::RECEPTOR_VENUS, ProfileCode::BREACH_VENUS, ProfileCode::FIRE_VENUS, ProfileCode::GATE_VENUS, ProfileCode::MOVEMENT_VENUS, ProfileCode::DISCHARGE_VENUS, ProfileCode::WATCHER_VENUS, ProfileCode::LOOK_VENUS, ProfileCode::TIME_VENUS],
        FormulaCode::HIT => [ProfileCode::BARRIER_VENUS, ProfileCode::SPARK_VENUS, ProfileCode::RELEASE_VENUS, ProfileCode::SCENT_VENUS, ProfileCode::ILLUSION_VENUS, ProfileCode::RECEPTOR_VENUS, ProfileCode::BREACH_VENUS, ProfileCode::FIRE_VENUS, ProfileCode::GATE_VENUS, ProfileCode::MOVEMENT_VENUS, ProfileCode::DISCHARGE_VENUS, ProfileCode::WATCHER_VENUS, ProfileCode::LOOK_VENUS, ProfileCode::TIME_VENUS],
        FormulaCode::GREAT_MASSACRE => [ProfileCode::BARRIER_VENUS, ProfileCode::SPARK_VENUS, ProfileCode::SCENT_VENUS, ProfileCode::ILLUSION_VENUS, ProfileCode::RECEPTOR_VENUS, ProfileCode::BREACH_VENUS, ProfileCode::FIRE_VENUS, ProfileCode::GATE_VENUS, ProfileCode::DISCHARGE_VENUS, ProfileCode::WATCHER_VENUS, ProfileCode::LOOK_VENUS, ProfileCode::TIME_VENUS],
        FormulaCode::DISCHARGE => [ProfileCode::BARRIER_VENUS, ProfileCode::SCENT_VENUS, ProfileCode::ILLUSION_VENUS, ProfileCode::RECEPTOR_VENUS, ProfileCode::BREACH_VENUS, ProfileCode::FIRE_VENUS, ProfileCode::GATE_VENUS, ProfileCode::MOVEMENT_VENUS, ProfileCode::TRANSPOSITION_VENUS, ProfileCode::WATCHER_VENUS, ProfileCode::LOOK_VENUS, ProfileCode::TIME_VENUS],
        FormulaCode::LOCK => [ProfileCode::BARRIER_VENUS, ProfileCode::SPARK_VENUS, ProfileCode::RELEASE_VENUS, ProfileCode::SCENT_VENUS, ProfileCode::ILLUSION_VENUS, ProfileCode::RECEPTOR_VENUS, ProfileCode::FIRE_VENUS, ProfileCode::GATE_VENUS, ProfileCode::MOVEMENT_VENUS, ProfileCode::DISCHARGE_VENUS, ProfileCode::LOOK_VENUS, ProfileCode::TIME_VENUS],
    ];

    /**
     * @param string $formulaValue
     * @return array|string[]
     */
    private function getExpectedProfilesFor(string $formulaValue): array
    {
        return array_diff(
            ProfileCode::getPossibleValues(),
            self::$impossibleVenusProfiles[$formulaValue],
            self::getMarsProfiles()
        );
    }

    /**
     * @return array|string[]
     */
    private static function getMarsProfiles(): array
    {
        return array_filter(
            ProfileCode::getPossibleValues(),
            function (string $profileValue) {
                return strpos($profileValue, '_mars') !== false;
            }
        );
    }

    /**
     * @param string $formulaValue
     * @return array|string[]
     */
    private function getProfilesByProfileTable(string $formulaValue): array
    {
        $profilesTable = new ProfilesTable();
        $matchingProfiles = [];
        foreach (ProfileCode::getPossibleValues() as $profileValue) {
            foreach ($profilesTable->getFormulaCodes(ProfileCode::getIt($profileValue)) as $formulaCode) {
                if ($formulaCode->getValue() === $formulaValue) {
                    $oppositeProfile = $this->reverseProfileGender($profileValue);
                    $matchingProfiles[] = $oppositeProfile;
                    break;
                }
            }
        }

        return array_unique($matchingProfiles);
    }

    /**
     * @test
     */
    public function I_can_not_get_profiles_to_unknown_formula(): void
    {
        $this->expectException(\DrdPlus\Tables\Theurgist\Spells\Exceptions\UnknownFormulaToGetProfilesFor::class);
        $this->expectExceptionMessageRegExp('~Charge!~');
        (new FormulasTable(Tables::getIt()))->getProfileCodes($this->createFormulaCode('Charge!'));
    }
}