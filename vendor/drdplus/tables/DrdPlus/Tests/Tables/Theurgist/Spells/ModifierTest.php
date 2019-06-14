<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells;

use DrdPlus\Tables\Tables;
use DrdPlus\Codes\Theurgist\ModifierCode;
use DrdPlus\Codes\Theurgist\ModifierMutableParameterCode;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\AdditionByDifficulty;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\CastingRounds;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\DifficultyChange;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameter;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Realm;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellSpeed;
use DrdPlus\Tables\Theurgist\Spells\Modifier;
use DrdPlus\Tables\Theurgist\Spells\ModifiersTable;
use DrdPlus\Tables\Theurgist\Spells\SpellTrait;
use Granam\String\StringTools;
use Granam\Tests\Tools\TestWithMockery;
use Mockery\Exception\NoMatchingExpectationException;
use Mockery\MockInterface;

class ModifierTest extends TestWithMockery
{
    private $parameterNamespace;

    protected function setUp()
    {
        $this->parameterNamespace = (new \ReflectionClass(SpellSpeed::class))->getNamespaceName();
    }

    /**
     * @test
     */
    public function I_can_create_it_without_any_change_for_every_modifier()
    {
        foreach (ModifierCode::getPossibleValues() as $modifierValue) {
            $modifierCode = ModifierCode::getIt($modifierValue);
            $modifiersTable = $this->createModifiersTable();
            $tables = $this->createTables($modifiersTable);
            $modifier = new Modifier($modifierCode, $tables, [], []);
            self::assertSame($modifierCode, $modifier->getModifierCode());
            self::assertSame($modifierValue, (string)$modifier);
            foreach (ModifierMutableParameterCode::getPossibleValues() as $mutableParameterName) {
                /** like instance of @see SpellSpeed */
                $baseParameter = $this->createExpectedParameter($mutableParameterName);
                $this->addBaseParameterGetter($mutableParameterName, $modifierCode, $modifiersTable, $baseParameter);
                /** like @see Modifier::getBaseSpellRadius */
                $getBaseParameter = StringTools::assembleGetterForName('base_' . $mutableParameterName);
                self::assertSame($baseParameter, $modifier->$getBaseParameter());

                /** like @see Modifier::getCurrentRadius() */
                $getCurrentParameter = StringTools::assembleGetterForName($mutableParameterName . '_with_addition');
                $this->addExpectedAdditionSetter(0, $baseParameter, $baseParameter);
                self::assertSame($baseParameter, $modifier->$getCurrentParameter());

                /** like @see Modifier::getSpellRadiusAddition */
                $getParameterAddition = StringTools::assembleGetterForName($mutableParameterName) . 'Addition';
                self::assertSame(0, $modifier->$getParameterAddition());
            }
        }
    }

    /**
     * @param ModifiersTable $modifiersTable
     * @return Tables|MockInterface
     */
    private function createTables(ModifiersTable $modifiersTable): Tables
    {
        $tables = $this->mockery(Tables::class);
        $tables->shouldReceive('getModifiersTable')
            ->andReturn($modifiersTable);
        return $tables;
    }

    /**
     * @return MockInterface|ModifiersTable
     */
    private function createModifiersTable()
    {
        return $this->mockery(ModifiersTable::class);
    }

    /**
     * @param string $parameterName
     * @param int $defaultValue
     * @return CastingParameter|MockInterface
     */
    private function createExpectedParameter(string $parameterName, int $defaultValue = null): CastingParameter
    {
        $parameterClass = $this->getParameterClass($parameterName);

        $parameter = $this->mockery($parameterClass);
        if ($defaultValue !== null) {
            $parameter->shouldReceive('getDefaultValue')
                ->andReturn($defaultValue);
        }

        return $parameter;
    }

    private function getParameterClass(string $parameterName): string
    {
        $parameterClassBasename = ucfirst(StringTools::assembleMethodName($parameterName));

        $baseParameterClass = $this->parameterNamespace . '\\' . $parameterClassBasename;
        self::assertTrue(class_exists($baseParameterClass));

        return $baseParameterClass;
    }

    private function addBaseParameterGetter(
        string $parameterName,
        ModifierCode $modifierCode,
        MockInterface $modifiersTable,
        CastingParameter $value = null
    )
    {
        $getProperty = StringTools::assembleGetterForName($parameterName);
        $modifiersTable->shouldReceive($getProperty)
            ->with($modifierCode)
            ->andReturn($value);
    }

    private function addExpectedAdditionSetter(
        int $addition,
        MockInterface $parameter,
        CastingParameter $modifiedParameter
    )
    {
        $parameter->shouldReceive('getWithAddition')
            ->with($addition)
            ->andReturn($modifiedParameter);
    }

    /**
     * @test
     */
    public function I_get_null_for_unused_modifiers_for_every_modifier()
    {
        foreach (ModifierCode::getPossibleValues() as $modifierValue) {
            $modifierCode = ModifierCode::getIt($modifierValue);
            $modifiersTable = $this->createModifiersTable();
            $tables = $this->createTables($modifiersTable);
            $modifier = new Modifier($modifierCode, $tables, [], []);
            self::assertSame($modifierCode, $modifier->getModifierCode());
            foreach (ModifierMutableParameterCode::getPossibleValues() as $mutableParameterName) {
                /*if ($mutableParameterName === ModifierMutableParameterCode::) {
                    continue; // can not be null, skipping
                }*/
                $this->addBaseParameterGetter($mutableParameterName, $modifierCode, $modifiersTable, null);
                /** like @see Modifier::getBaseSpellRadius */
                $getBaseParameter = StringTools::assembleGetterForName('base_' . $mutableParameterName);
                self::assertNull($modifier->$getBaseParameter());

                /** like @see Modifier::getCurrentRadius() */
                $getCurrentParameter = StringTools::assembleGetterForName($mutableParameterName . '_with_addition');
                self::assertNull($modifier->$getCurrentParameter());

                /** like @see Modifier::getSpellRadiusAddition */
                $getParameterAddition = StringTools::assembleGetterForName($mutableParameterName) . 'Addition';
                self::assertSame(0, $modifier->$getParameterAddition());
            }
        }
    }

    /**
     * @test
     */
    public function I_can_create_it_with_change_for_every_modifier()
    {
        $parameterChanges = [];
        foreach (ModifierMutableParameterCode::getPossibleValues() as $index => $parameterName) {
            $parameterChanges[$parameterName] = $index + 1; // 1...x
        }
        $spellTraits = [$this->createSpellTraitShell(), $this->createSpellTraitShell()];
        foreach (ModifierCode::getPossibleValues() as $modifierValue) {
            $modifierCode = ModifierCode::getIt($modifierValue);
            $modifiersTable = $this->createModifiersTable();
            $tables = $this->createTables($modifiersTable);
            $baseParameters = [];
            foreach (ModifierMutableParameterCode::getPossibleValues() as $parameterName) {
                /** like instance of @see SpellSpeed */
                $baseParameter = $this->createExpectedParameter($parameterName, -1 /* default value */);
                $this->addBaseParameterGetter($parameterName, $modifierCode, $modifiersTable, $baseParameter);
                $baseParameters[$parameterName] = $baseParameter;
            }
            $modifier = new Modifier($modifierCode, $tables, $parameterChanges, $spellTraits);
            self::assertSame($modifierCode, $modifier->getModifierCode());
            foreach (ModifierMutableParameterCode::getPossibleValues() as $parameterName) {
                $baseParameter = $baseParameters[$parameterName];
                $change = $parameterChanges[$parameterName] + 1 /* because of difference against default value -1 */
                ;
                /** like @see Modifier::getBaseSpellRadius */
                $getBaseParameter = StringTools::assembleGetterForName('base_' . $parameterName);
                self::assertSame($baseParameter, $modifier->$getBaseParameter());
                /** like @see Modifier::getCurrentRadius() */
                $getParameterWithAddition = StringTools::assembleGetterForName($parameterName . '_with_addition');
                $this->addExpectedAdditionSetter(
                    $change,
                    $baseParameter,
                    $changedParameter = $this->createExpectedParameter($parameterName)
                );
                self::assertSame($baseParameter, $modifier->$getBaseParameter());
                try {
                    self::assertSame($changedParameter, $modifier->$getParameterWithAddition());
                } catch (NoMatchingExpectationException $expectationException) {
                    self::fail("Parameter {$parameterName} uses wrong addition (expected change {$change}): " . $expectationException->getMessage());
                }

                /** like @see Modifier::getSpellRadiusAddition */
                $getParameterAddition = StringTools::assembleGetterForName($parameterName) . 'Addition';
                self::assertNotSame(0, $change);
                self::assertSame($change, $modifier->$getParameterAddition());
            }
        }
    }

    /**
     * @return MockInterface|SpellTrait
     */
    private function createSpellTraitShell()
    {
        return $this->mockery(SpellTrait::class);
    }

    /**
     * @test
     */
    public function I_get_basic_difficulty_change_without_any_parameter()
    {
        foreach (ModifierCode::getPossibleValues() as $modifierValue) {
            $modifierCode = ModifierCode::getIt($modifierValue);
            $modifiersTable = $this->createModifiersTable();
            $tables = $this->createTables($modifiersTable);
            foreach (ModifierMutableParameterCode::getPossibleValues() as $mutableParameterName) {
                $this->addBaseParameterGetter($mutableParameterName, $modifierCode, $modifiersTable, null);
            }
            $difficultyChange = $this->createDifficultyChange(0);
            $this->addDifficultyChangeGetter($difficultyChange, $modifierCode, $modifiersTable);
            $modifier = new Modifier($modifierCode, $tables, [], []);
            self::assertSame($difficultyChange, $modifier->getDifficultyChange());
        }
    }

    /**
     * @param int $expectedAdd
     * @return MockInterface|DifficultyChange
     */
    private function createDifficultyChange(int $expectedAdd)
    {
        $difficultyChange = $this->mockery(DifficultyChange::class);
        $difficultyChange->shouldReceive('add')
            ->with($expectedAdd)
            ->once()
            ->andReturn($difficultyChange);

        return $difficultyChange;
    }

    private function addDifficultyChangeGetter(
        MockInterface $difficultyChange,
        ModifierCode $modifierCode,
        MockInterface $modifierTable
    )
    {
        $modifierTable->shouldReceive('getDifficultyChange')
            ->with($modifierCode)
            ->andReturn($difficultyChange);
    }

    /**
     * @test
     */
    public function I_get_basic_difficulty_change_with_every_parameter()
    {
        foreach (ModifierCode::getPossibleValues() as $modifierValue) {
            $modifierCode = ModifierCode::getIt($modifierValue);
            $modifiersTable = $this->createModifiersTable();
            $tables = $this->createTables($modifiersTable);
            $parameterDifficulties = [];
            foreach (ModifierMutableParameterCode::getPossibleValues() as $mutableParameterName) {
                $parameter = $this->createExpectedParameter($mutableParameterName);
                $this->addBaseParameterGetter($mutableParameterName, $modifierCode, $modifiersTable, $parameter);
                $changedParameter = $this->createExpectedParameter($mutableParameterName);
                $this->addExpectedAdditionSetter(0, $parameter, $changedParameter);
                /** @noinspection PhpUnhandledExceptionInspection */
                $parameterDifficulties[] = $difficultyChange = random_int(-10, 10);
                $this->addAdditionByDifficultyGetter($difficultyChange, $changedParameter);
            }
            $spellTraits = [$this->createSpellTrait(123), [$this->createSpellTrait(456)]];
            $difficultyChangeValue = 123 + 456 + array_sum($parameterDifficulties);
            $difficultyChange = $this->createDifficultyChange($difficultyChangeValue);
            $this->addDifficultyChangeGetter($difficultyChange, $modifierCode, $modifiersTable);
            $modifier = new Modifier($modifierCode, $tables, [], $spellTraits);
            try {
                self::assertSame($difficultyChange, $modifier->getDifficultyChange());
            } catch (NoMatchingExpectationException $expectationException) {
                self::fail(
                    'Expected difficulty sum ' . $difficultyChangeValue . ' for ' . $modifierValue
                    . ': ' . $expectationException->getMessage()
                );
            }
        }
    }

    /**
     * @param int $difficultyChangeValue
     * @return MockInterface|SpellTrait
     */
    private function createSpellTrait(int $difficultyChangeValue)
    {
        $spellTrait = $this->mockery(SpellTrait::class);
        $spellTrait->shouldReceive('getDifficultyChange')
            ->andReturn($difficultyChange = $this->mockery(DifficultyChange::class));
        $difficultyChange->shouldReceive('getValue')
            ->andReturn($difficultyChangeValue);

        return $spellTrait;
    }

    private function addAdditionByDifficultyGetter(int $difficultyChange, MockInterface $parameter)
    {
        $parameter->shouldReceive('getAdditionByDifficulty')
            ->andReturn($additionByDifficulty = $this->mockery(AdditionByDifficulty::class));
        $additionByDifficulty->shouldReceive('getCurrentDifficultyIncrement')
            ->andReturn($difficultyChange);
    }

    /**
     * @test
     */
    public function I_can_get_casting_rounds()
    {
        $modifiersTable = $this->createModifiersTable();
        $tables = $this->createTables($modifiersTable);
        $modifier = new Modifier(
            $modifierCode = ModifierCode::getIt(ModifierCode::COLOR),
            $tables,
            [],
            []
        );
        $modifiersTable->shouldReceive('getCastingRounds')
            ->with($modifierCode)
            ->andReturn($castingRounds = $this->mockery(CastingRounds::class));
        self::assertSame($castingRounds, $modifier->getCastingRounds());
    }

    /**
     * @test
     */
    public function I_can_get_required_realm_for_modifier()
    {
        $modifiersTable = $this->createModifiersTable();
        $tables = $this->createTables($modifiersTable);
        $modifier = new Modifier(
            $modifierCode = ModifierCode::getIt(ModifierCode::TRANSPOSITION),
            $tables,
            [],
            []
        );
        $modifiersTable->shouldReceive('getRealm')
            ->with($modifierCode)
            ->andReturn($realm = $this->mockery(Realm::class));
        self::assertSame($realm, $modifier->getRequiredRealm());
    }

    /**
     * @test
     */
    public function I_can_get_realms_affection()
    {
        foreach (ModifierCode::getPossibleValues() as $modifierValue) {
            $modifier = new Modifier(
                $modifierCode = ModifierCode::getIt($modifierValue),
                Tables::getIt(),
                [],
                []
            );
            self::assertEquals(
                Tables::getIt()->getModifiersTable()->getRealmsAffection($modifierCode),
                $modifier->getRealmsAffection()
            );
        }
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\Exceptions\InvalidValueForModifierParameter
     * @expectedExceptionMessageRegExp ~0\.1~
     */
    public function I_can_not_create_it_with_non_integer_addition()
    {
        $modifiersTable = $this->createModifiersTable();
        $tables = $this->createTables($modifiersTable);
        $this->addBaseParameterGetter(
            $parameterName = ModifierMutableParameterCode::EPICENTER_SHIFT,
            $code = ModifierCode::getIt(ModifierCode::INVISIBILITY),
            $modifiersTable,
            $this->createExpectedParameter($parameterName, 123 /* whatever */)
        );
        try {
            new Modifier($code, $tables, [$parameterName => 0.0], []);
        } catch (\Exception $exception) {
            self::fail('No exception expected so far: ' . $exception->getMessage() . '; ' . $exception->getTraceAsString());
        }
        try {
            $modifiersTable = $this->createModifiersTable();
            $tables = $this->createTables($modifiersTable);
            $this->addBaseParameterGetter(
                $parameterName = ModifierMutableParameterCode::SPELL_RADIUS,
                $code = ModifierCode::getIt(ModifierCode::GATE),
                $modifiersTable,
                $this->createExpectedParameter($parameterName, 432 /* whatever */)
            );
            new Modifier($code, $tables, [$parameterName => '5.000'], []);
        } catch (\Exception $exception) {
            self::fail('No exception expected so far: ' . $exception->getMessage() . '; ' . $exception->getTraceAsString());
        }
        new Modifier(
            ModifierCode::getIt(ModifierCode::TRANSPOSITION),
            $tables,
            [ModifierMutableParameterCode::GRAFTS => 0.1],
            []
        );
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\Exceptions\UnknownModifierParameter
     * @expectedExceptionMessageRegExp ~4~
     */
    public function I_can_not_add_non_zero_addition_to_unused_parameter()
    {
        $modifiersTable = $this->createModifiersTable();
        $tables = $this->createTables($modifiersTable);
        try {
            $this->addBaseParameterGetter(
                $parameterName = ModifierMutableParameterCode::SPELL_ATTACK,
                $modifierCode = ModifierCode::getIt(ModifierCode::INTERACTIVE_ILLUSION),
                $modifiersTable,
                $this->createExpectedParameter($parameterName, 9 /* whatever */)
            );
            new Modifier(
                $modifierCode,
                $tables,
                [$parameterName => 4],
                []
            );
        } catch (\Exception $exception) {
            self::fail('No exception expected so far: ' . $exception->getMessage() . '; ' . $exception->getTraceAsString());
        }
        $modifiersTable = $this->createModifiersTable();
        $tables = $this->createTables($modifiersTable);
        $this->addBaseParameterGetter(
            $parameterName = ModifierMutableParameterCode::RESISTANCE,
            $modifierCode = ModifierCode::getIt(ModifierCode::COLOR),
            $modifiersTable,
            null // unused
        );
        new Modifier($modifierCode, $tables, [$parameterName => 4], []);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\Exceptions\UnknownModifierParameter
     * @expectedExceptionMessageRegExp ~useless~
     */
    public function I_can_not_create_it_with_addition_of_unknown_parameter()
    {
        new Modifier(ModifierCode::getIt(ModifierCode::TRANSPOSITION), Tables::getIt(), ['useless' => 0], []);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Theurgist\Spells\Exceptions\InvalidSpellTrait
     * @expectedExceptionMessageRegExp ~DateTime~
     */
    public function I_can_create_it_with_non_spell_trait_as_spell_trait()
    {
        new Modifier(ModifierCode::getIt(ModifierCode::TRANSPOSITION), Tables::getIt(), [], [new \DateTime()]);
    }
}