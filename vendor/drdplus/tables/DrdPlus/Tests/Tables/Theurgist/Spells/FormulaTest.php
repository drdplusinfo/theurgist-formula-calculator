<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells;

use DrdPlus\Codes\Theurgist\AffectionPeriodCode;
use DrdPlus\Codes\Theurgist\FormulaCode;
use DrdPlus\Codes\Theurgist\FormulaMutableSpellParameterCode;
use DrdPlus\Codes\Theurgist\ModifierCode;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\AdditionByDifficulty;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\CastingRounds;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\DifficultyChange;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Evocation;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Difficulty;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameter;
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
    private $parameterNamespace;

    /**
     * @throws \ReflectionException
     */
    protected function setUp(): void
    {
        $this->parameterNamespace = (new \ReflectionClass(SpellSpeed::class))->getNamespaceName();
    }

    /**
     * @test
     */
    public function I_can_create_it_without_any_change_for_every_formula(): void
    {
        foreach (FormulaCode::getPossibleValues() as $formulaValue) {
            $formulaCode = FormulaCode::getIt($formulaValue);
            $formulasTable = $this->createFormulasTable();
            $formula = new Formula($formulaCode, $this->createTables($formulasTable));
            self::assertSame($formulaCode, $formula->getFormulaCode());
            self::assertSame([], $formula->getModifiers());
            foreach (FormulaMutableSpellParameterCode::getPossibleValues() as $mutableParameterName) {
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

    /**
     * @param FormulasTable $formulasTable
     * @return Tables|MockInterface
     */
    private function createTables(FormulasTable $formulasTable): Tables
    {
        $tables = $this->mockery(Tables::class);
        $tables->shouldReceive('getFormulasTable')
            ->andReturn($formulasTable);
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

        $baseParameterClass = $this->parameterNamespace . '\\' . $parameterClassBasename;
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
    public function I_get_null_for_unused_modifiers_for_every_formula(): void
    {
        foreach (FormulaCode::getPossibleValues() as $formulaValue) {
            $formulaCode = FormulaCode::getIt($formulaValue);
            $formulasTable = $this->createFormulasTable();
            $formula = new Formula($formulaCode, $this->createTables($formulasTable));
            self::assertSame([], $formula->getModifiers());
            self::assertSame($formulaCode, $formula->getFormulaCode());
            foreach (FormulaMutableSpellParameterCode::getPossibleValues() as $mutableParameterName) {
                if ($mutableParameterName === FormulaMutableSpellParameterCode::SPELL_DURATION) {
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
        $parameterValues = [
            FormulaMutableSpellParameterCode::SPELL_RADIUS => 1,
            FormulaMutableSpellParameterCode::SPELL_DURATION => 2,
            FormulaMutableSpellParameterCode::SPELL_POWER => 3,
            FormulaMutableSpellParameterCode::SPELL_ATTACK => 4,
            FormulaMutableSpellParameterCode::SIZE_CHANGE => 5,
            FormulaMutableSpellParameterCode::DETAIL_LEVEL => 6,
            FormulaMutableSpellParameterCode::SPELL_BRIGHTNESS => 7,
            FormulaMutableSpellParameterCode::SPELL_SPEED => 8,
            FormulaMutableSpellParameterCode::EPICENTER_SHIFT => 9,
        ];
        $parameterChanges = [];
        foreach (FormulaCode::getPossibleValues() as $formulaValue) {
            $formulaCode = FormulaCode::getIt($formulaValue);
            $formulasTable = $this->createFormulasTable();
            $baseParameters = [];
            foreach (FormulaMutableSpellParameterCode::getPossibleValues() as $mutableParameterName) {
                /** like instance of @see SpellSpeed */
                $baseParameter = $this->createExpectedParameter($mutableParameterName);
                $this->addBaseParameterGetter($mutableParameterName, $formulaCode, $formulasTable, $baseParameter);
                $this->addDefaultValueGetter($baseParameter, $defaultValue = \random_int(-5, 5));
                $baseParameters[$mutableParameterName] = $baseParameter;
                $parameterChanges[$mutableParameterName] = $parameterValues[$mutableParameterName] - $defaultValue;
            }
            $formula = new Formula($formulaCode, $this->createTables($formulasTable), $parameterValues);
            self::assertSame($formulaCode, $formula->getFormulaCode());
            foreach (FormulaMutableSpellParameterCode::getPossibleValues() as $mutableParameterName) {
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

    /**
     * @test
     */
    public function I_get_basic_difficulty_change_without_any_parameter(): void
    {
        foreach (FormulaCode::getPossibleValues() as $formulaValue) {
            $formulaCode = FormulaCode::getIt($formulaValue);
            $formulasTable = $this->createFormulasTable();
            foreach (FormulaMutableSpellParameterCode::getPossibleValues() as $mutableParameterName) {
                $baseParameter = null;
                if ($mutableParameterName === FormulaMutableSpellParameterCode::SPELL_DURATION) {
                    // duration can not be null
                    $baseParameter = $this->createExpectedParameter(FormulaMutableSpellParameterCode::SPELL_DURATION);
                    $this->addWithAdditionGetter(0, $baseParameter, $baseParameter);
                    $this->addAdditionByDifficultyGetter(0, $baseParameter);
                }
                $this->addBaseParameterGetter($mutableParameterName, $formulaCode, $formulasTable, $baseParameter);
            }
            $this->addFormulaDifficultyGetter($formulasTable, $formulaCode, 0);
            $formula = new Formula($formulaCode, $this->createTables($formulasTable));
            self::assertSame(
                $formulasTable->getDifficulty($formulaCode)->createWithChange(0),
                $formula->getCurrentDifficulty()
            );
        }
    }

    private function addFormulaDifficultyGetter(
        MockInterface $formulaTable,
        FormulaCode $expectedFormulaCode,
        int $expectedDifficultyChange,
        Difficulty $formulaChangedDifficulty = null
    ): void
    {
        $formulaTable->shouldReceive('getDifficulty')
            ->with($expectedFormulaCode)
            ->andReturn($formulaDifficulty = $this->mockery(Difficulty::class));
        $formulaDifficulty->shouldReceive('createWithChange')
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
            foreach (FormulaMutableSpellParameterCode::getPossibleValues() as $mutableParameterName) {
                $parameter = $this->createExpectedParameter($mutableParameterName);
                $this->addBaseParameterGetter($mutableParameterName, $formulaCode, $formulasTable, $parameter);
                $changedParameter = $this->createExpectedParameter($mutableParameterName);
                $this->addWithAdditionGetter(0, $parameter, $changedParameter);
                /** @noinspection PhpUnhandledExceptionInspection */
                $parameterDifficulties[] = $difficultyChange = random_int(-10, 10);
                $this->addAdditionByDifficultyGetter($difficultyChange, $changedParameter);
            }
            $this->addFormulaDifficultyGetter($formulasTable, $formulaCode, 123 + 456 + 789 + 789 + 159 + array_sum($parameterDifficulties));
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
                    $formulasTable->getDifficulty($formulaCode)->createWithChange(
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
        $formula = new Formula(
            FormulaCode::getIt(FormulaCode::PORTAL),
            $this->createTables($formulasTable),
            [],
            [$this->createModifier(1), [$this->createModifier(2), [$this->createModifier(3), $this->createModifier(4)]]]
        );
        $formulasTable->shouldReceive('getCastingRounds')
            ->andReturn($this->createCastingRounds(123));
        $finalCastingRounds = $formula->getCurrentCastingRounds();
        self::assertInstanceOf(CastingRounds::class, $finalCastingRounds);
        self::assertSame(123 + 1 + 2 + 3 + 4, $finalCastingRounds->getValue());
    }

    /**
     * @param int $castingRoundsValue
     * @return MockInterface|Modifier
     */
    private function createModifier(int $castingRoundsValue)
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
        $formula = new Formula($formulaCode = FormulaCode::getIt(FormulaCode::DISCHARGE), $this->createTables($formulasTable));
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
        $formula = new Formula(
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
        foreach (FormulaMutableSpellParameterCode::getPossibleValues() as $mutableParameterName) {
            $baseParameter = null;
            if ($mutableParameterName === FormulaMutableSpellParameterCode::SPELL_DURATION) {
                // duration can not be null
                $baseParameter = $this->createExpectedParameter(FormulaMutableSpellParameterCode::SPELL_DURATION);
                $this->addWithAdditionGetter(0, $baseParameter, $baseParameter);
                $this->addAdditionByDifficultyGetter(0, $baseParameter);
            }
            $this->addBaseParameterGetter($mutableParameterName, $formulaCode, $formulasTable, $baseParameter);
        }
        $changedDifficulty = $this->createDifficulty();
        $this->addFormulaDifficultyGetter(
            $formulasTable,
            $formulaCode,
            0,
            $changedDifficulty
        );
        $this->addCurrentRealmsIncrementGetter($changedDifficulty, 123);
        $this->addRealmGetter($formulasTable, $formulaCode, 123, $formulaRealm = $this->mockery(Realm::class));
        $formulaWithoutModifiers = new Formula($formulaCode, $this->createTables($formulasTable));
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
        $formula = new Formula(
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
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\Exceptions\InvalidValueForFormulaParameter
     * @expectedExceptionMessageRegExp ~0\.1~
     */
    public function I_can_not_create_it_with_non_integer_addition()
    {
        try {
            $formulaCode = FormulaCode::getIt(FormulaCode::PORTAL);
            $formulasTable = $this->createFormulasTable();
            /** like instance of @see SpellSpeed */
            $parameter = $this->createExpectedParameter(FormulaMutableSpellParameterCode::SPELL_DURATION);
            $this->addBaseParameterGetter(FormulaMutableSpellParameterCode::SPELL_DURATION, $formulaCode, $formulasTable, $parameter);
            $this->addDefaultValueGetter($parameter, 123);
            new Formula(
                $formulaCode,
                $this->createTables($formulasTable),
                [FormulaMutableSpellParameterCode::SPELL_DURATION => 0.0]
            );
        } catch (\Exception $exception) {
            self::fail('No exception expected so far: ' . $exception->getMessage() . '; ' . $exception->getTraceAsString());
        }
        try {
            $formulaCode = FormulaCode::getIt(FormulaCode::PORTAL);
            $formulasTable = $this->createFormulasTable();
            $parameter = $this->createExpectedParameter(FormulaMutableSpellParameterCode::SPELL_DURATION);
            $this->addBaseParameterGetter(
                FormulaMutableSpellParameterCode::SPELL_DURATION,
                $formulaCode,
                $formulasTable,
                $parameter
            );
            $this->addDefaultValueGetter($parameter, 456);
            new Formula(
                $formulaCode,
                $this->createTables($formulasTable),
                [FormulaMutableSpellParameterCode::SPELL_DURATION => '5.000']
            );
        } catch (\Exception $exception) {
            self::fail('No exception expected so far: ' . $exception->getMessage() . '; ' . $exception->getTraceAsString());
        }
        new Formula(
            FormulaCode::getIt(FormulaCode::PORTAL),
            $this->createTables($this->createFormulasTable()),
            [FormulaMutableSpellParameterCode::SPELL_DURATION => 0.1]
        );
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\Exceptions\UselessValueForUnusedSpellParameter
     * @expectedExceptionMessageRegExp ~4~
     */
    public function I_can_not_add_non_zero_addition_to_unused_parameter()
    {
        try {
            $formulasTable = $this->createFormulasTable();
            $brightness = $this->createExpectedParameter(FormulaMutableSpellParameterCode::SPELL_BRIGHTNESS);
            $this->addBaseParameterGetter(
                FormulaMutableSpellParameterCode::SPELL_BRIGHTNESS,
                FormulaCode::getIt(FormulaCode::LIGHT),
                $formulasTable,
                $brightness
            );
            $this->addDefaultValueGetter($brightness, 1);
            new Formula(
                FormulaCode::getIt(FormulaCode::LIGHT),
                $this->createTables($formulasTable),
                [FormulaMutableSpellParameterCode::SPELL_BRIGHTNESS => 4]
            );
        } catch (\Exception $exception) {
            self::fail('No exception expected so far: ' . $exception->getMessage() . '; ' . $exception->getTraceAsString());
        }
        $formulasTable = $this->createFormulasTable();
        $this->addBaseParameterGetter(
            FormulaMutableSpellParameterCode::SPELL_BRIGHTNESS,
            FormulaCode::getIt(FormulaCode::LIGHT),
            $formulasTable,
            null // unused
        );
        new Formula(
            FormulaCode::getIt(FormulaCode::LIGHT),
            $this->createTables($formulasTable),
            [FormulaMutableSpellParameterCode::SPELL_BRIGHTNESS => 4]
        );
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\Exceptions\UnknownFormulaParameter
     * @expectedExceptionMessageRegExp ~divine~
     */
    public function I_can_not_create_it_with_addition_of_unknown_addition()
    {
        new Formula(
            FormulaCode::getIt(FormulaCode::PORTAL),
            $this->createTables($this->createFormulasTable()),
            ['divine' => 0]
        );
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\Exceptions\InvalidModifier
     * @expectedExceptionMessageRegExp ~DateTime~
     */
    public function I_can_not_create_it_with_invalid_modifier()
    {
        new Formula(
            FormulaCode::getIt(FormulaCode::PORTAL),
            $this->createTables($this->createFormulasTable()),
            [],
            [new \DateTime()]
        );
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\Exceptions\InvalidSpellTrait
     * @expectedExceptionMessageRegExp ~stdClass~
     */
    public function I_can_not_create_it_with_invalid_spell_trait()
    {
        new Formula(
            FormulaCode::getIt(FormulaCode::PORTAL),
            $this->createTables($this->createFormulasTable()),
            [],
            [],
            [new \stdClass()]
        );
    }

}