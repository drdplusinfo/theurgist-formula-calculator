<?php declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells;

use DrdPlus\Codes\Theurgist\AffectionPeriodCode;
use DrdPlus\Codes\Theurgist\FormulaCode;
use DrdPlus\Codes\Theurgist\FormulaMutableParameterCode;
use DrdPlus\Codes\Theurgist\ModifierCode;
use DrdPlus\Codes\Theurgist\ModifierMutableParameterCode;
use DrdPlus\Tables\Measurements\Distance\Distance;
use DrdPlus\Tables\Measurements\Time\Time;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\AdditionByDifficulty;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\CastingRounds;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\DifficultyChange;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\EpicenterShift;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Evocation;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Difficulty;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameter;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellPower;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellRadius;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Realm;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\RealmsAffection;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellSpeed;
use DrdPlus\Tables\Theurgist\Spells\Formula;
use DrdPlus\Tables\Theurgist\Spells\FormulasTable;
use DrdPlus\Tables\Theurgist\Spells\Modifier;
use DrdPlus\Tables\Theurgist\Spells\SpellTrait;
use Granam\String\StringTools;
use Granam\Tests\Tools\TestWithMockery;
use Mockery\Exception\NoMatchingExpectationException;
use Mockery\MockInterface;

class FormulaTest extends TestWithMockery
{
    private static $parameterNamespace;

    /**
     * @throws \ReflectionException
     */
    protected function setUp(): void
    {
        if (self::$parameterNamespace === null) {
            self::$parameterNamespace = (new \ReflectionClass(SpellSpeed::class))->getNamespaceName();
        }
    }

    /**
     * @test
     */
    public function I_will_get_its_code_name_as_string_representation()
    {
        $formula = $this->createFormula(FormulaCode::getIt(FormulaCode::BARRIER), Tables::getIt());
        self::assertSame(FormulaCode::BARRIER, (string)$formula);
    }

    /**
     * @test
     */
    public function I_can_create_it_without_any_change_for_every_formula(): void
    {
        foreach (FormulaCode::getPossibleValues() as $formulaValue) {
            $formulaCode = FormulaCode::getIt($formulaValue);
            $formulasTable = $this->createFormulasTable();
            $formula = $this->createFormula($formulaCode, $this->createTables($formulasTable));
            self::assertSame($formulaCode, $formula->getFormulaCode());
            self::assertSame([], $formula->getModifiers());
            foreach (FormulaMutableParameterCode::getPossibleValues() as $mutableParameterName) {
                /** like instance of @see SpellSpeed */
                $baseParameter = $this->createExpectedParameter($mutableParameterName);
                $this->addBaseParameterGetter($mutableParameterName, $formulaCode, $formulasTable, $baseParameter);

                $this->addWithAdditionGetter(0, $baseParameter, $baseParameter);
                $this->addValueGetter($baseParameter, 123);
                /** like @see Formula::getCurrentSpellRadius() */
                $getCurrentParameter = StringTools::assembleGetterForName('current' . ucfirst($mutableParameterName));
                /** @var CastingParameter $currentParameter */
                $currentParameter = $formula->$getCurrentParameter();
                self::assertInstanceOf($this->getParameterClass($mutableParameterName), $currentParameter);
                self::assertSame(123, $currentParameter->getValue());
                /** @noinspection DisconnectedForeachInstructionInspection */
                self::assertSame($formulaValue, (string)$formulaCode);
            }
        }
    }

    private function createFormula(FormulaCode $formulaCode, Tables $tables, array $formulaSpellParameterValues = [], array $modifiers = []): Formula
    {
        return new Formula($formulaCode, $tables, $formulaSpellParameterValues, $modifiers, []);
    }

    /**
     * @param FormulasTable $formulasTable
     * @return Tables|MockInterface
     */
    private function createTables(FormulasTable $formulasTable): Tables
    {
        $tables = $this->mockery(Tables::class);
        $tables->shouldReceive('getFormulasTable')
            ->andReturn($formulasTable);
        $tables->makePartial();
        return $tables;
    }

    private function addValueGetter(MockInterface $object, $value): void
    {
        $object->shouldReceive('getValue')
            ->andReturn($value);
    }

    /**
     * @return MockInterface|FormulasTable
     */
    private function createFormulasTable()
    {
        return $this->mockery(FormulasTable::class);
    }

    /**
     * @param string $parameterName
     * @return CastingParameter|MockInterface
     */
    private function createExpectedParameter(string $parameterName): CastingParameter
    {
        $parameterClass = $this->getParameterClass($parameterName);

        return $this->mockery($parameterClass);
    }

    private function getParameterClass(string $parameterName): string
    {
        $parameterClassBasename = ucfirst(StringTools::assembleMethodName($parameterName));

        $baseParameterClass = self::$parameterNamespace . '\\' . $parameterClassBasename;
        self::assertTrue(class_exists($baseParameterClass), 'Can not find class ' . $baseParameterClass);

        return $baseParameterClass;
    }

    private function addBaseParameterGetter(
        string $parameterName,
        FormulaCode $formulaCode,
        MockInterface $formulasTable,
        CastingParameter $property = null
    ): void
    {
        $getProperty = StringTools::assembleGetterForName($parameterName);
        $formulasTable->shouldReceive($getProperty)
            ->with($formulaCode)
            ->andReturn($property);
    }

    private function addDefaultValueGetter(MockInterface $property, int $defaultValue = 0): void
    {
        $property->shouldReceive('getDefaultValue')
            ->andReturn($defaultValue);
    }

    private function addWithAdditionGetter(
        int $addition,
        MockInterface $parameter,
        CastingParameter $modifiedParameter
    ): void
    {
        $parameter->shouldReceive('getWithAddition')
            ->with($addition)
            ->andReturn($modifiedParameter);
    }

    /**
     * @test
     */
    public function I_can_not_affect_current_formula_parameter_by_gate_modifier(): void
    {
        $formulaCode = FormulaCode::getIt(FormulaCode::GREAT_MASSACRE);
        $formulasTable = $this->createFormulasTable();
        $byModifiersMutableParameterNames = array_intersect(
            FormulaMutableParameterCode::getPossibleValues(),
            ModifierMutableParameterCode::getPossibleValues()
        );
        self::assertNotEmpty($byModifiersMutableParameterNames);
        foreach ($byModifiersMutableParameterNames as $mutableParameterName) {
            /** like instance of @see SpellSpeed */
            $baseParameter = $this->createExpectedParameter($mutableParameterName);
            $this->addBaseParameterGetter($mutableParameterName, $formulaCode, $formulasTable, $baseParameter);

            $this->addWithAdditionGetter(0, $baseParameter, $baseParameter);
            $this->addValueGetter($baseParameter, 123);

            $modifier = $this->createModifier(ModifierCode::getIt(ModifierCode::GATE));
            $getParameterWithAddition = StringTools::assembleGetterForName($mutableParameterName . 'WithAddition');
            $modifier->shouldReceive($getParameterWithAddition)
                ->andReturn($castingParameter = $this->mockery(CastingParameter::class));
            $castingParameter->shouldReceive('getValue')
                ->andReturn(99999); // this should be skipped

            /** like @see Formula::getCurrentSpellRadius() */
            $formula = $this->createFormula(
                $formulaCode,
                $this->createTables($formulasTable),
                [],
                [$modifier]
            );

            $getCurrentParameter = StringTools::assembleGetterForName('current' . ucfirst($mutableParameterName));
            /** @var CastingParameter $currentParameter */
            $currentParameter = $formula->$getCurrentParameter();
            self::assertSame(123, $currentParameter->getValue());
        }
    }

    /**
     * @param ModifierCode $modifierCode
     * @return MockInterface|Modifier
     */
    private function createModifier(ModifierCode $modifierCode)
    {
        $modifier = $this->mockery(Modifier::class);
        $modifier->shouldReceive('getModifierCode')
            ->andReturn($modifierCode);
        return $modifier;
    }

    /**
     * @test
     */
    public function I_can_not_affect_formula_spell_power_by_thunder_modifier(): void
    {
        $formulaCode = FormulaCode::getIt(FormulaCode::GREAT_MASSACRE);
        $formulasTable = $this->createFormulasTable();
        /** like instance of @see SpellPower */
        $baseParameter = $this->createExpectedParameter(ModifierMutableParameterCode::SPELL_POWER);
        $this->addBaseParameterGetter(ModifierMutableParameterCode::SPELL_POWER, $formulaCode, $formulasTable, $baseParameter);

        $this->addWithAdditionGetter(0, $baseParameter, $baseParameter);
        $this->addValueGetter($baseParameter, 123);

        $thunderModifier = $this->createModifier(ModifierCode::getIt(ModifierCode::THUNDER));
        $getParameterWithAddition = StringTools::assembleGetterForName(ModifierMutableParameterCode::SPELL_POWER . 'WithAddition');
        $thunderModifier->shouldReceive($getParameterWithAddition)
            ->never();

        /** like @see Formula::getCurrentSpellRadius() */
        $formula = $this->createFormula(
            $formulaCode,
            $this->createTables($formulasTable),
            [],
            [$thunderModifier]
        );

        $getCurrentParameter = StringTools::assembleGetterForName('current' . ucfirst(ModifierMutableParameterCode::SPELL_POWER));
        /** @var CastingParameter $currentParameter */
        $currentParameter = $formula->$getCurrentParameter();
        self::assertSame(123, $currentParameter->getValue());
    }

    /**
     * @test
     */
    public function I_can_not_affect_formula_parameter_by_modifier_without_that_parameter_affection(): void
    {
        $formulaCode = FormulaCode::getIt(FormulaCode::GREAT_MASSACRE);
        $formulasTable = $this->createFormulasTable();
        /** like instance of @see SpellPower */
        $baseParameter = $this->createExpectedParameter(ModifierMutableParameterCode::SPELL_POWER);
        $this->addBaseParameterGetter(ModifierMutableParameterCode::SPELL_POWER, $formulaCode, $formulasTable, $baseParameter);

        $this->addWithAdditionGetter(0, $baseParameter, $baseParameter);
        $this->addValueGetter($baseParameter, 123);

        $modifier = $this->createModifier(ModifierCode::getIt(ModifierCode::TRANSPOSITION));
        $getParameterWithAddition = StringTools::assembleGetterForName(ModifierMutableParameterCode::SPELL_POWER . 'WithAddition');
        $modifier->shouldReceive($getParameterWithAddition)
            ->andReturnNull();

        /** like @see Formula::getCurrentSpellRadius() */
        $formula = $this->createFormula(
            $formulaCode,
            $this->createTables($formulasTable),
            [],
            [$modifier]
        );

        $getCurrentParameter = StringTools::assembleGetterForName('current' . ucfirst(ModifierMutableParameterCode::SPELL_POWER));
        /** @var CastingParameter $currentParameter */
        $currentParameter = $formula->$getCurrentParameter();
        self::assertSame(123, $currentParameter->getValue());
    }

    /**
     * @test
     */
    public function I_get_null_for_unused_modifiers_for_every_formula(): void
    {
        foreach (FormulaCode::getPossibleValues() as $formulaValue) {
            $formulaCode = FormulaCode::getIt($formulaValue);
            $formulasTable = $this->createFormulasTable();
            $formula = $this->createFormula($formulaCode, $this->createTables($formulasTable));
            self::assertSame([], $formula->getModifiers());
            self::assertSame($formulaCode, $formula->getFormulaCode());
            foreach (FormulaMutableParameterCode::getPossibleValues() as $mutableParameterName) {
                if ($mutableParameterName === FormulaMutableParameterCode::SPELL_DURATION) {
                    continue; // can not be null, skipping
                }
                $this->addBaseParameterGetter($mutableParameterName, $formulaCode, $formulasTable, null);

                /** like @see Formula::getCurrentSpellRadius() */
                $getCurrentParameter = StringTools::assembleGetterForName('current' . $mutableParameterName);
                self::assertNull($formula->$getCurrentParameter());
            }
        }
    }

    /**
     * @test
     * @throws \Exception
     */
    public function I_can_create_it_with_addition_for_every_formula(): void
    {
        $parametersWithValues = $this->getParametersWithValues();
        $parameterChanges = [];
        foreach (FormulaCode::getPossibleValues() as $formulaValue) {
            $formulaCode = FormulaCode::getIt($formulaValue);
            $formulasTable = $this->createFormulasTable();
            $baseParameters = [];
            foreach (FormulaMutableParameterCode::getPossibleValues() as $mutableParameterName) {
                /** like instance of @see SpellSpeed */
                $baseParameter = $this->createExpectedParameter($mutableParameterName);
                $this->addBaseParameterGetter($mutableParameterName, $formulaCode, $formulasTable, $baseParameter);
                $this->addDefaultValueGetter($baseParameter, $defaultValue = \random_int(-5, 5));
                $baseParameters[$mutableParameterName] = $baseParameter;
                $parameterChanges[$mutableParameterName] = $parametersWithValues[$mutableParameterName] - $defaultValue;
            }
            $formula = $this->createFormula($formulaCode, $this->createTables($formulasTable), $parametersWithValues);
            self::assertSame($formulaCode, $formula->getFormulaCode());
            foreach (FormulaMutableParameterCode::getPossibleValues() as $mutableParameterName) {
                $baseParameter = $baseParameters[$mutableParameterName];
                $change = $parameterChanges[$mutableParameterName];
                $this->addWithAdditionGetter(
                    $change,
                    $baseParameter,
                    $changedParameter = $this->createExpectedParameter($mutableParameterName)
                );
                $this->addValueGetter($changedParameter, 123);
                /** like @see Formula::getCurrentSpellRadius() */
                $getCurrentParameter = StringTools::assembleGetterForName('current' . $mutableParameterName);
                /** @var CastingParameter $currentParameter */
                $currentParameter = $formula->$getCurrentParameter();
                self::assertInstanceOf($this->getParameterClass($mutableParameterName), $currentParameter);
                self::assertSame(123, $currentParameter->getValue());
            }
        }
    }

    private function getParametersWithValues(): array
    {
        $parametersWithValues = [
            FormulaMutableParameterCode::SPELL_RADIUS => 1,
            FormulaMutableParameterCode::SPELL_DURATION => 2,
            FormulaMutableParameterCode::SPELL_POWER => 3,
            FormulaMutableParameterCode::SPELL_ATTACK => 4,
            FormulaMutableParameterCode::SIZE_CHANGE => 5,
            FormulaMutableParameterCode::DETAIL_LEVEL => 6,
            FormulaMutableParameterCode::SPELL_BRIGHTNESS => 7,
            FormulaMutableParameterCode::SPELL_SPEED => 8,
            FormulaMutableParameterCode::EPICENTER_SHIFT => 9,
        ];
        $missedParameters = array_diff(FormulaMutableParameterCode::getPossibleValues(), array_keys($parametersWithValues));
        self::assertCount(
            0,
            $missedParameters,
            'We have missed some mutable parameters: ' . implode(',', $missedParameters)
        );
        return $parametersWithValues;
    }

    /**
     * @test
     */
    public function I_get_basic_difficulty_change_without_any_parameter(): void
    {
        foreach (FormulaCode::getPossibleValues() as $formulaValue) {
            $formulaCode = FormulaCode::getIt($formulaValue);
            $formulasTable = $this->createFormulasTable();
            foreach (FormulaMutableParameterCode::getPossibleValues() as $mutableParameterName) {
                $baseParameter = null;
                if ($mutableParameterName === FormulaMutableParameterCode::SPELL_DURATION) {
                    // duration can not be null
                    $baseParameter = $this->createExpectedParameter(FormulaMutableParameterCode::SPELL_DURATION);
                    $this->addWithAdditionGetter(0, $baseParameter, $baseParameter);
                    $this->addAdditionByDifficultyGetter(0, $baseParameter);
                }
                $this->addBaseParameterGetter($mutableParameterName, $formulaCode, $formulasTable, $baseParameter);
            }
            $this->addDemonDifficultyGetter($formulasTable, $formulaCode, 0);
            $formula = $this->createFormula($formulaCode, $this->createTables($formulasTable));
            self::assertSame(
                $formulasTable->getDifficulty($formulaCode)->getWithDifficultyChange(0),
                $formula->getCurrentDifficulty()
            );
        }
    }

    private function addDemonDifficultyGetter(
        MockInterface $formulaTable,
        FormulaCode $expectedFormulaCode,
        int $expectedDifficultyChange,
        Difficulty $formulaChangedDifficulty = null
    ): void
    {
        $formulaTable->shouldReceive('getDifficulty')
            ->with($expectedFormulaCode)
            ->andReturn($difficulty = $this->mockery(Difficulty::class));
        $difficulty->shouldReceive('getWithDifficultyChange')
            ->with($expectedDifficultyChange)
            ->andReturn($formulaChangedDifficulty ?? $this->mockery(Difficulty::class));
    }

    /**
     * @test
     */
    public function I_get_difficulty_change_with_every_parameter(): void
    {
        foreach (FormulaCode::getPossibleValues() as $formulaValue) {
            $formulaCode = FormulaCode::getIt($formulaValue);
            $formulasTable = $this->createFormulasTable();
            $parameterDifficulties = [];
            foreach (FormulaMutableParameterCode::getPossibleValues() as $mutableParameterName) {
                $parameter = $this->createExpectedParameter($mutableParameterName);
                $this->addBaseParameterGetter($mutableParameterName, $formulaCode, $formulasTable, $parameter);
                $changedParameter = $this->createExpectedParameter($mutableParameterName);
                $this->addWithAdditionGetter(0, $parameter, $changedParameter);
                /** @noinspection PhpUnhandledExceptionInspection */
                $parameterDifficulties[] = $difficultyChange = random_int(-10, 10);
                $this->addAdditionByDifficultyGetter($difficultyChange, $changedParameter);
            }
            $this->addDemonDifficultyGetter($formulasTable, $formulaCode, 123 + 456 + 789 + 789 + 159 + array_sum($parameterDifficulties));
            $formula = new Formula(
                $formulaCode,
                $this->createTables($formulasTable),
                [],
                [$modifier1 = $this->createModifierWithDifficulty(123), [$modifier2 = $this->createModifierWithDifficulty(456)]],
                [$this->getSpellTrait(789), [$this->getSpellTrait(789), [$this->getSpellTrait(159)]]]
            );
            self::assertSame([$modifier1, $modifier2], $formula->getModifiers());
            try {
                self::assertNotEquals($formulasTable->getDifficulty($formulaCode), $formula->getCurrentDifficulty());
                self::assertEquals(
                    $formulasTable->getDifficulty($formulaCode)->getWithDifficultyChange(
                        123 + 456 + 789 + 789 + 159 + array_sum($parameterDifficulties)
                    ),
                    $formula->getCurrentDifficulty()
                );
            } catch (NoMatchingExpectationException $expectationException) {
                self::fail(
                    'Expected difficulty ' . (123 + 456 + 789 + 789 + 159 + array_sum($parameterDifficulties))
                    . ': ' . $expectationException->getMessage()
                );
            }
        }
    }

    /**
     * @param int $difficultyChangeValue
     * @return MockInterface|Modifier
     */
    private function createModifierWithDifficulty(int $difficultyChangeValue)
    {
        $modifier = $this->mockery(Modifier::class);
        $modifier->shouldReceive('getDifficultyChange')
            ->andReturn($difficultyChange = $this->mockery(DifficultyChange::class));
        $difficultyChange->shouldReceive('getValue')
            ->andReturn($difficultyChangeValue);

        return $modifier;
    }

    /**
     * @param int $difficultyChangeValue
     * @return MockInterface|SpellTrait
     */
    private function getSpellTrait(int $difficultyChangeValue)
    {
        $spellTrait = $this->mockery(SpellTrait::class);
        $spellTrait->shouldReceive('getDifficultyChange')
            ->andReturn($difficultyChange = $this->mockery(DifficultyChange::class));
        $difficultyChange->shouldReceive('getValue')
            ->andReturn($difficultyChangeValue);

        return $spellTrait;
    }

    private function addAdditionByDifficultyGetter(int $difficultyChange, MockInterface $parameter): void
    {
        $parameter->shouldReceive('getAdditionByDifficulty')
            ->andReturn($additionByDifficulty = $this->mockery(AdditionByDifficulty::class));
        $additionByDifficulty->shouldReceive('getCurrentDifficultyIncrement')
            ->andReturn($difficultyChange);
    }

    /**
     * @test
     */
    public function I_can_get_final_casting_rounds_affected_by_modifiers(): void
    {
        $formulasTable = $this->createFormulasTable();
        $formula = $this->createFormula(
            FormulaCode::getIt(FormulaCode::PORTAL),
            $this->createTables($formulasTable),
            [],
            [$this->createModifierWithCastingRounds(1), [$this->createModifierWithCastingRounds(2), [$this->createModifierWithCastingRounds(3), $this->createModifierWithCastingRounds(4)]]]
        );
        $formulasTable->shouldReceive('getCastingRounds')
            ->andReturn($this->createCastingRounds(123));
        $finalCastingRounds = $formula->getCurrentCastingRounds();
        self::assertInstanceOf(CastingRounds::class, $finalCastingRounds);
        self::assertSame(123 + 1 + 2 + 3 + 4, $finalCastingRounds->getValue());
        self::assertEquals(
            new Time(123 + 1 + 2 + 3 + 4, Time::ROUND, Tables::getIt()->getTimeTable()),
            $finalCastingRounds->getTime()
        );
        self::assertEquals(
            (new Time(123 + 1 + 2 + 3 + 4, Time::ROUND, Tables::getIt()->getTimeTable()))->getBonus(),
            $finalCastingRounds->getTimeBonus()
        );
    }

    /**
     * @param int $castingRoundsValue
     * @return MockInterface|Modifier
     */
    private function createModifierWithCastingRounds(int $castingRoundsValue)
    {
        $modifier = $this->mockery(Modifier::class);
        $modifier->shouldReceive('getCastingRounds')
            ->andReturn($this->createCastingRounds($castingRoundsValue));
        return $modifier;
    }

    /**
     * @param int $value
     * @return MockInterface|CastingRounds
     */
    private function createCastingRounds(int $value)
    {
        $castingRounds = $this->mockery(CastingRounds::class);
        $castingRounds->shouldReceive('getValue')
            ->andReturn($value);
        return $castingRounds;
    }

    /**
     * @test
     */
    public function I_can_get_current_evocation()
    {
        $formulasTable = $this->createFormulasTable();
        $formula = $this->createFormula($formulaCode = FormulaCode::getIt(FormulaCode::DISCHARGE), $this->createTables($formulasTable));
        $formulasTable->shouldReceive('getEvocation')
            ->with($formulaCode)
            ->andReturn($evocation = $this->mockery(Evocation::class));
        self::assertSame($evocation, $formula->getCurrentEvocation());
    }

    /**
     * @param string $periodName
     * @param int $formulaAffectionValue
     * @return MockInterface|RealmsAffection
     */
    private function createRealmsAffection(string $periodName, int $formulaAffectionValue)
    {
        $realmsAffection = $this->mockery(RealmsAffection::class);
        $realmsAffection->shouldReceive('getAffectionPeriodCode')
            ->andReturn($affectionPeriod = $this->mockery(AffectionPeriodCode::class));
        $affectionPeriod->shouldReceive('getValue')
            ->andReturn($periodName);
        $realmsAffection->shouldReceive('getValue')
            ->andReturn($formulaAffectionValue);

        return $realmsAffection;
    }

    /**
     * @test
     */
    public function I_can_get_current_realms_affection()
    {
        $formulasTable = $this->createFormulasTable();
        $formula = $this->createFormula(
            $formulaCode = FormulaCode::getIt(FormulaCode::ILLUSION),
            $this->createTables($formulasTable),
            [],
            [$this->createModifierWithRealmsAffection(-5, AffectionPeriodCode::DAILY),
                [
                    $this->createModifierWithRealmsAffection(-2, AffectionPeriodCode::DAILY),
                    $this->createModifierWithRealmsAffection(-8, AffectionPeriodCode::MONTHLY),
                    $this->createModifierWithoutRealmsAffection(),
                    $this->createModifierWithRealmsAffection(-1, AffectionPeriodCode::YEARLY),
                ],
            ]
        );
        $formulasTable->shouldReceive('getRealmsAffection')
            ->with($formulaCode)
            ->andReturn($this->createRealmsAffection(AffectionPeriodCode::YEARLY, -11)); // base realm affection
        $expected = [
            AffectionPeriodCode::DAILY => new RealmsAffection([-7, AffectionPeriodCode::DAILY]),
            AffectionPeriodCode::MONTHLY => new RealmsAffection([-8, AffectionPeriodCode::MONTHLY]),
            AffectionPeriodCode::YEARLY => new RealmsAffection([-12, AffectionPeriodCode::YEARLY]),
        ];
        ksort($expected);
        $current = $formula->getCurrentRealmsAffections();
        ksort($current);
        self::assertEquals($expected, $current);
    }

    /**
     * @param int $realmsAffectionValue
     * @param string $affectionPeriodValue
     * @return MockInterface|Modifier
     */
    private function createModifierWithRealmsAffection(int $realmsAffectionValue, string $affectionPeriodValue)
    {
        $modifier = $this->mockery(Modifier::class);
        $modifier->shouldReceive('getRealmsAffection')
            ->andReturn($realmsAffection = $this->mockery(RealmsAffection::class));
        $realmsAffection->shouldReceive('getAffectionPeriodCode')
            ->andReturn($affectionPeriod = $this->mockery(AffectionPeriodCode::class));
        $affectionPeriod->shouldReceive('getValue')
            ->andReturn($affectionPeriodValue);
        $realmsAffection->shouldReceive('getValue')
            ->andReturn($realmsAffectionValue);

        return $modifier;
    }

    /**
     * @return MockInterface|Modifier
     */
    private function createModifierWithoutRealmsAffection()
    {
        $modifier = $this->mockery(Modifier::class);
        $modifier->shouldReceive('getRealmsAffection')
            ->andReturn(null);

        return $modifier;
    }

    /**
     * @test
     */
    public function I_get_final_realm(): void
    {
        $formulaCode = FormulaCode::getIt(FormulaCode::PORTAL);
        $formulasTable = $this->createFormulasTable();
        foreach (FormulaMutableParameterCode::getPossibleValues() as $mutableParameterName) {
            $baseParameter = null;
            if ($mutableParameterName === FormulaMutableParameterCode::SPELL_DURATION) {
                // duration can not be null
                $baseParameter = $this->createExpectedParameter(FormulaMutableParameterCode::SPELL_DURATION);
                $this->addWithAdditionGetter(0, $baseParameter, $baseParameter);
                $this->addAdditionByDifficultyGetter(0, $baseParameter);
            }
            $this->addBaseParameterGetter($mutableParameterName, $formulaCode, $formulasTable, $baseParameter);
        }
        $changedDifficulty = $this->createDifficulty();
        $this->addDemonDifficultyGetter(
            $formulasTable,
            $formulaCode,
            0,
            $changedDifficulty
        );
        $this->addCurrentRealmsIncrementGetter($changedDifficulty, 123);
        $this->addRealmGetter($formulasTable, $formulaCode, 123, $formulaRealm = $this->mockery(Realm::class));
        $formulaWithoutModifiers = $this->createFormula($formulaCode, $this->createTables($formulasTable));
        self::assertSame($formulaRealm, $formulaWithoutModifiers->getRequiredRealm());

        $lowModifiers = [$this->createModifierWithRequiredRealm(0), $this->createModifierWithRequiredRealm(122)];
        $formulaWithLowModifiers = new Formula($formulaCode, $this->createTables($formulasTable), [], $lowModifiers, []);
        $formulaRealm->shouldReceive('getValue')
            ->andReturn(123);
        self::assertSame($formulaRealm, $formulaWithLowModifiers->getRequiredRealm());

        $highModifiers = [
            [$this->createModifierWithRequiredRealm(123)],
            $this->createModifierWithRequiredRealm(124, $highestRealm = $this->mockery(Realm::class)),
        ];
        $formulaWithHighModifiers = new Formula($formulaCode, $this->createTables($formulasTable), [], $highModifiers, []);
        /**
         * @var Realm $formulaRealm
         * @var Realm $highestRealm
         */
        self::assertGreaterThan($formulaRealm->getValue(), $highestRealm->getValue());
        self::assertEquals($highestRealm, $formulaWithHighModifiers->getRequiredRealm());
    }

    /**
     * @return Difficulty|MockInterface
     */
    private function createDifficulty(): Difficulty
    {
        return $this->mockery(Difficulty::class);
    }

    private function addCurrentRealmsIncrementGetter(MockInterface $formulaDifficulty, int $currentRealmsIncrement): void
    {
        $formulaDifficulty->shouldReceive('getCurrentRealmsIncrement')
            ->andReturn($currentRealmsIncrement);
    }

    private function addRealmGetter(
        MockInterface $formulasTable,
        FormulaCode $formulaCode,
        int $expectedRealmsIncrement,
        $finalRealm
    )
    {
        $formulasTable->shouldReceive('getRealm')
            ->with($formulaCode)
            ->andReturn($realm = $this->mockery(Realm::class));
        $realm->shouldReceive('add')
            ->with($expectedRealmsIncrement)
            ->andReturn($finalRealm);
    }

    /**
     * @param int $value
     * @param MockInterface|null $realm
     * @pram MockInterface|null $realm
     * @return MockInterface|Modifier
     */
    private function createModifierWithRequiredRealm(int $value, MockInterface $realm = null)
    {
        $modifier = $this->mockery(Modifier::class);
        $modifier->shouldReceive('getRequiredRealm')
            ->andReturn($realm ?? $realm = $this->mockery(Realm::class));
        $realm->shouldReceive('getValue')
            ->andReturn($value);
        $modifier->shouldReceive('getDifficultyChange')
            ->andReturn($difficultyChange = $this->mockery(DifficultyChange::class));
        $difficultyChange->shouldReceive('getValue')
            ->andReturn(0);

        return $modifier;
    }

    /**
     * @test
     */
    public function I_can_get_current_radius(): void
    {
        $formulasTable = $this->createFormulasTable();
        $formula = $this->createFormula(
            FormulaCode::getIt(FormulaCode::PORTAL),
            $this->createTables($formulasTable),
            [],
            [
                $this->createModifierWithRadius(1, ModifierCode::getIt(ModifierCode::FILTER)),
                [
                    $this->createModifierWithRadius(2, ModifierCode::getIt(ModifierCode::MOVEMENT)),
                    [
                        $this->createModifierWithRadius(3, ModifierCode::getIt(ModifierCode::COLOR)),
                        $this->createModifierWithRadius(4, ModifierCode::getIt(ModifierCode::INVISIBILITY)),
                    ],
                ],
            ]
        );
        $formulasTable->shouldReceive('getSpellRadius')
            ->andReturn($radius = $this->createRadius(123 /* whatever */));
        $this->addWithAdditionGetter(0, $radius, $radiusWithAddition = $this->createRadius(456));
        $currentRadius = $formula->getCurrentSpellRadius();
        self::assertInstanceOf(SpellRadius::class, $currentRadius);
        self::assertSame(456 + 1 + 2 + 3 + 4, $currentRadius->getValue());
    }

    /**
     * @param int $radiusValue
     * @param ModifierCode $modifierCode
     * @return MockInterface|Modifier
     */
    private function createModifierWithRadius(int $radiusValue, ModifierCode $modifierCode)
    {
        $modifier = $this->mockery(Modifier::class);
        $modifier->shouldReceive('getSpellRadiusWithAddition')
            ->andReturn($this->createRadius($radiusValue));
        $modifier->shouldReceive('getModifierCode')
            ->andReturn($modifierCode);

        return $modifier;
    }

    /**
     * @param int $value
     * @return MockInterface|SpellRadius
     */
    private function createRadius(int $value)
    {
        $radius = $this->mockery(SpellRadius::class);
        $radius->shouldReceive('getValue')
            ->andReturn($value);

        return $radius;
    }

    /**
     * @test
     */
    public function I_can_get_current_spell_power_without_power_addition()
    {
        $formulaCode = FormulaCode::getIt(FormulaCode::BARRIER);
        $formulasTable = $this->createFormulasTable();
        $formulasTable->shouldReceive('getSpellPower')
            ->with($formulaCode)
            ->andReturnNull(); // no spell power directly
        $modifiers = [$this->createModifierWithSpellPowerAddition(123, ModifierCode::getIt(ModifierCode::BREACH))];
        $formula = $this->createFormula($formulaCode, $this->createTables($formulasTable), [], $modifiers);
        self::assertSame(123, $formula->getCurrentSpellPower()->getValue());
    }

    /**
     * @param int $spellPowerAddition
     * @param ModifierCode $modifierCode
     * @return MockInterface|Modifier
     */
    private function createModifierWithSpellPowerAddition(int $spellPowerAddition, ModifierCode $modifierCode)
    {
        $modifier = $this->mockery(Modifier::class);
        $modifier->shouldReceive('getSpellPowerWithAddition')
            ->andReturn($this->createSpellPower($spellPowerAddition));
        $modifier->shouldReceive('getModifierCode')
            ->andReturn($modifierCode);
        return $modifier;
    }

    /**
     * @param int $spellPowerValue
     * @return MockInterface|SpellPower
     */
    private function createSpellPower(int $spellPowerValue)
    {
        $spellPower = $this->mockery(SpellPower::class);
        $spellPower->shouldReceive('getValue')
            ->andReturn($spellPowerValue);
        return $spellPower;
    }

    /**
     * @test
     */
    public function I_can_get_current_spell_speed_without_power_addition()
    {
        $formulaCode = FormulaCode::getIt(FormulaCode::BARRIER);
        $formulasTable = $this->createFormulasTable();
        $formulasTable->shouldReceive('getSpellSpeed')
            ->with($formulaCode)
            ->andReturnNull(); // no spell power directly
        $modifiers = [$this->createModifierWithSpellSpeedAddition(123, ModifierCode::getIt(ModifierCode::BREACH))];
        $formula = $this->createFormula($formulaCode, $this->createTables($formulasTable), [], $modifiers);
        self::assertSame(123, $formula->getCurrentSpellSpeed()->getValue());
    }

    /**
     * @param int $spellSpeedAddition
     * @param ModifierCode $modifierCode
     * @return MockInterface|Modifier
     */
    private function createModifierWithSpellSpeedAddition(int $spellSpeedAddition, ModifierCode $modifierCode)
    {
        $modifier = $this->mockery(Modifier::class);
        $modifier->shouldReceive('getSpellSpeedWithAddition')
            ->andReturn($this->createSpellSpeed($spellSpeedAddition));
        $modifier->shouldReceive('getModifierCode')
            ->andReturn($modifierCode);
        return $modifier;
    }

    /**
     * @param int $spellSpeedValue
     * @return MockInterface|SpellSpeed
     */
    private function createSpellSpeed(int $spellSpeedValue)
    {
        $spellSpeed = $this->mockery(SpellSpeed::class);
        $spellSpeed->shouldReceive('getValue')
            ->andReturn($spellSpeedValue);
        return $spellSpeed;
    }

    /**
     * @test
     */
    public function I_can_get_current_epicenter_shift_changed_by_modifiers_only()
    {
        $formulaCode = FormulaCode::getIt(FormulaCode::TSUNAMI_FROM_CLAY_AND_STONES);
        $formulasTable = $this->createFormulasTable();
        $formulasTable->shouldReceive('getEpicenterShift')
            ->with($formulaCode)
            ->andReturnNull(); // no epicenter shift directly
        $modifiers = [new Modifier(ModifierCode::getIt(ModifierCode::TRANSPOSITION), Tables::getIt(), [], [])];
        $formula = $this->createFormula($formulaCode, $this->createTables($formulasTable), [], $modifiers);
        $expectedEpicenterShiftDistance = new Distance(1, Distance::METER, Tables::getIt()->getDistanceTable());
        self::assertSame(
            $expectedEpicenterShiftDistance->getMeters(),
            $formula->getCurrentEpicenterShift()->getDistance()->getMeters()
        );
        self::assertSame(
            $expectedEpicenterShiftDistance->getBonus()->getValue(),
            $formula->getCurrentEpicenterShift()->getDistanceBonus()->getValue()
        );
    }

    /**
     * @test
     */
    public function I_can_get_current_epicenter_shift_changed_both_by_formula_and_modifiers()
    {
        $formulaCode = FormulaCode::getIt(FormulaCode::GREAT_MASSACRE);
        $formulasTable = $this->createFormulasTable();
        $formulasTable->shouldReceive('getEpicenterShift')
            ->with($formulaCode)
            ->andReturn(new EpicenterShift([20 /* distance bonus 20 = 10 meters */, 0], Tables::getIt()));
        $modifiers = [
            new Modifier(
                ModifierCode::getIt(ModifierCode::TRANSPOSITION),
                Tables::getIt(),
                [ModifierMutableParameterCode::EPICENTER_SHIFT => 30 /* = 32 meters */], []
            ),
        ];
        $formula = $this->createFormula($formulaCode, $this->createTables($formulasTable), [], $modifiers);
        $expectedEpicenterShiftDistance = new Distance(42, Distance::METER, Tables::getIt()->getDistanceTable());
        self::assertSame(
            $expectedEpicenterShiftDistance->getMeters(),
            $formula->getCurrentEpicenterShift()->getDistance()->getMeters()
        );
    }

    /**
     * @test
     */
    public function I_can_not_create_it_with_non_integer_addition()
    {
        $this->expectException(\DrdPlus\Tables\Theurgist\Spells\Exceptions\InvalidValueForFormulaParameter::class);
        $this->expectExceptionMessageRegExp('~0\.1~');
        try {
            $formulaCode = FormulaCode::getIt(FormulaCode::PORTAL);
            $formulasTable = $this->createFormulasTable();
            /** like instance of @see SpellSpeed */
            $parameter = $this->createExpectedParameter(FormulaMutableParameterCode::SPELL_DURATION);
            $this->addBaseParameterGetter(FormulaMutableParameterCode::SPELL_DURATION, $formulaCode, $formulasTable, $parameter);
            $this->addDefaultValueGetter($parameter, 123);
            $this->createFormula(
                $formulaCode,
                $this->createTables($formulasTable),
                [FormulaMutableParameterCode::SPELL_DURATION => 0.0]
            );
        } catch (\Exception $exception) {
            self::fail('No exception expected so far: ' . $exception->getMessage() . '; ' . $exception->getTraceAsString());
        }
        try {
            $formulaCode = FormulaCode::getIt(FormulaCode::PORTAL);
            $formulasTable = $this->createFormulasTable();
            $parameter = $this->createExpectedParameter(FormulaMutableParameterCode::SPELL_DURATION);
            $this->addBaseParameterGetter(
                FormulaMutableParameterCode::SPELL_DURATION,
                $formulaCode,
                $formulasTable,
                $parameter
            );
            $this->addDefaultValueGetter($parameter, 456);
            $this->createFormula(
                $formulaCode,
                $this->createTables($formulasTable),
                [FormulaMutableParameterCode::SPELL_DURATION => '5.000']
            );
        } catch (\Exception $exception) {
            self::fail('No exception expected so far: ' . $exception->getMessage() . '; ' . $exception->getTraceAsString());
        }
        $this->createFormula(
            FormulaCode::getIt(FormulaCode::PORTAL),
            $this->createTables($this->createFormulasTable()),
            [FormulaMutableParameterCode::SPELL_DURATION => 0.1]
        );
    }

    /**
     * @test
     */
    public function I_can_not_add_non_zero_addition_to_unused_parameter()
    {
        $this->expectException(\DrdPlus\Tables\Theurgist\Spells\Exceptions\UnknownFormulaParameter::class);
        $this->expectExceptionMessageRegExp('~4~');
        try {
            $formulasTable = $this->createFormulasTable();
            $brightness = $this->createExpectedParameter(FormulaMutableParameterCode::SPELL_BRIGHTNESS);
            $this->addBaseParameterGetter(
                FormulaMutableParameterCode::SPELL_BRIGHTNESS,
                FormulaCode::getIt(FormulaCode::LIGHT),
                $formulasTable,
                $brightness
            );
            $this->addDefaultValueGetter($brightness, 1);
            $this->createFormula(
                FormulaCode::getIt(FormulaCode::LIGHT),
                $this->createTables($formulasTable),
                [FormulaMutableParameterCode::SPELL_BRIGHTNESS => 4]
            );
        } catch (\Exception $exception) {
            self::fail('No exception expected so far: ' . $exception->getMessage() . '; ' . $exception->getTraceAsString());
        }
        $formulasTable = $this->createFormulasTable();
        $this->addBaseParameterGetter(
            FormulaMutableParameterCode::SPELL_BRIGHTNESS,
            FormulaCode::getIt(FormulaCode::LIGHT),
            $formulasTable,
            null // unused
        );
        $this->createFormula(
            FormulaCode::getIt(FormulaCode::LIGHT),
            $this->createTables($formulasTable),
            [FormulaMutableParameterCode::SPELL_BRIGHTNESS => 4]
        );
    }

    /**
     * @test
     */
    public function I_can_not_create_it_with_addition_of_unknown_addition()
    {
        $this->expectException(\DrdPlus\Tables\Theurgist\Spells\Exceptions\UnknownFormulaParameter::class);
        $this->expectExceptionMessageRegExp('~divine~');
        $this->createFormula(
            FormulaCode::getIt(FormulaCode::PORTAL),
            $this->createTables($this->createFormulasTable()),
            ['divine' => 0]
        );
    }

    /**
     * @test
     */
    public function I_can_not_create_it_with_invalid_modifier()
    {
        $this->expectException(\DrdPlus\Tables\Theurgist\Spells\Exceptions\InvalidModifier::class);
        $this->expectExceptionMessageRegExp('~DateTime~');
        $this->createFormula(
            FormulaCode::getIt(FormulaCode::PORTAL),
            $this->createTables($this->createFormulasTable()),
            [],
            [new \DateTime()]
        );
    }

    /**
     * @test
     */
    public function I_can_not_create_it_with_invalid_spell_trait()
    {
        $this->expectException(\DrdPlus\Tables\Theurgist\Spells\Exceptions\InvalidSpellTrait::class);
        $this->expectExceptionMessageRegExp('~stdClass~');
        new Formula(
            FormulaCode::getIt(FormulaCode::PORTAL),
            $this->createTables($this->createFormulasTable()),
            [],
            [],
            [new \stdClass()]
        );
    }

}