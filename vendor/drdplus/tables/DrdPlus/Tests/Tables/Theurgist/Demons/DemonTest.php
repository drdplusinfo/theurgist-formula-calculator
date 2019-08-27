<?php declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist\Demons;

use DrdPlus\Codes\Theurgist\AffectionPeriodCode;
use DrdPlus\Codes\Theurgist\DemonBodyCode;
use DrdPlus\Codes\Theurgist\DemonCode;
use DrdPlus\Codes\Theurgist\DemonKindCode;
use DrdPlus\Codes\Theurgist\DemonMutableParameterCode;
use DrdPlus\Codes\Theurgist\DemonTraitCode;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Demons\Demon;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonKnack;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonStrength;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonWill;
use DrdPlus\Tables\Theurgist\Demons\DemonsTable;
use DrdPlus\Tables\Theurgist\Demons\DemonTrait;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\AdditionByDifficulty;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Difficulty;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Evocation;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameter;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Realm;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\RealmsAddition;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\RealmsAffection;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellSpeed;
use Granam\String\StringTools;
use Granam\Tests\Tools\TestWithMockery;
use Mockery\MockInterface;

class DemonTest extends TestWithMockery
{
    private static $demonParameterNamespace;
    private static $spellParameterNamespace;

    /**
     * @throws \ReflectionException
     */
    protected function setUp(): void
    {
        if (self::$demonParameterNamespace === null) {
            self::$demonParameterNamespace = (new \ReflectionClass(DemonStrength::class))->getNamespaceName();
        }
        if (self::$spellParameterNamespace === null) {
            self::$spellParameterNamespace = (new \ReflectionClass(SpellSpeed::class))->getNamespaceName();
        }
    }

    /**
     * @test
     */
    public function I_can_create_it_without_any_change_of_a_demon()
    {
        $demonCode = DemonCode::getIt(DemonCode::IMP);
        $demonsTable = $this->createDemonsTable();
        $demon = $this->createDemon($demonCode, $this->createTables($demonsTable));
        self::assertSame($demonCode, $demon->getDemonCode());
        $parameterValue = 123;
        foreach (DemonMutableParameterCode::getPossibleValues() as $mutableParameterName) {
            /** like instance of @see DemonStrength */
            $baseParameter = $this->createParameter($mutableParameterName);
            $this->addParameterGetter($mutableParameterName, $demonCode, $demonsTable, $baseParameter);

            $this->addWithAdditionGetter(0, $baseParameter, $baseParameter);
            $this->addValueGetter($baseParameter, $parameterValue);
            /** like @see Demon::getCurrentDemonCapacity() */
            $getCurrentParameter = StringTools::assembleGetterForName('current' . ucfirst($mutableParameterName));
            /** @var CastingParameter $currentParameter */
            $currentParameter = $demon->$getCurrentParameter();
            self::assertInstanceOf($this->getDemonParameterClass($mutableParameterName), $currentParameter);
            self::assertSame($parameterValue, $currentParameter->getValue());
            $parameterValue++; // just some change
        }
    }

    private function createDemon(DemonCode $demonCode, Tables $tables, array $demonParameterValues = [], array $demonTraits = []): Demon
    {
        return new Demon($demonCode, $tables, $demonParameterValues, $demonTraits);
    }

    /**
     * @param DemonsTable $demonsTable
     * @return Tables|MockInterface
     */
    private function createTables(DemonsTable $demonsTable): Tables
    {
        $tables = $this->mockery(Tables::class);
        $tables->shouldReceive('getDemonsTable')
            ->andReturn($demonsTable);
        $tables->makePartial();
        return $tables;
    }

    private function addValueGetter(MockInterface $object, $value)
    {
        $object->shouldReceive('getValue')
            ->andReturn($value);
    }

    /**
     * @return MockInterface|DemonsTable
     */
    private function createDemonsTable()
    {
        return $this->mockery(DemonsTable::class);
    }

    /**
     * @param string $parameterName
     * @return CastingParameter|MockInterface
     */
    private function createParameter(string $parameterName): CastingParameter
    {
        $parameterClass = $this->getDemonParameterClass($parameterName);

        return $this->mockery($parameterClass);
    }

    private function getDemonParameterClass(string $demonParameterName): string
    {
        $parameterClassBasename = ucfirst(StringTools::assembleMethodName($demonParameterName));
        $namespace = strpos($demonParameterName, 'demon') !== false
            ? self::$demonParameterNamespace
            : self::$spellParameterNamespace;

        $baseParameterClass = $namespace . '\\' . $parameterClassBasename;
        self::assertTrue(class_exists($baseParameterClass), 'Can not find class ' . $baseParameterClass);

        return $baseParameterClass;
    }

    private function addParameterGetter(
        string $parameterName,
        DemonCode $demonCode,
        MockInterface $demonsTable,
        CastingParameter $parameter = null
    )
    {
        $getProperty = StringTools::assembleGetterForName($parameterName);
        $demonsTable->shouldReceive($getProperty)
            ->with($demonCode)
            ->andReturn($parameter);
    }

    private function addDefaultValueGetter(MockInterface $property, int $defaultValue = 0)
    {
        $property->shouldReceive('getDefaultValue')
            ->andReturn($defaultValue);
    }

    private function addWithAdditionGetter(
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
    public function I_get_null_for_unused_parameters_of_a_demon()
    {
        $demonCode = DemonCode::getIt(DemonCode::DEMON_OF_MOVEMENT);
        $demonsTable = $this->createDemonsTable();
        $demon = $this->createDemon($demonCode, $this->createTables($demonsTable));
        self::assertSame([], $demon->getDemonTraits());
        self::assertSame($demonCode, $demon->getDemonCode());
        foreach (DemonMutableParameterCode::getPossibleValues() as $mutableParameterName) {
            $this->addParameterGetter($mutableParameterName, $demonCode, $demonsTable, null);
            /** like @see Demon::getCurrentDemonCapacity() */
            $getCurrentParameter = StringTools::assembleGetterForName('current' . $mutableParameterName);
            self::assertNull($demon->$getCurrentParameter());
        }
    }

    /**
     * @test
     */
    public function I_can_ask_demon_if_has_unlimited_endurance()
    {
        $demonCode = DemonCode::getIt(DemonCode::DEMON_OF_MOVEMENT);
        $demonsTable = $this->createDemonsTable();
        $nonUnlimitedEnduranceDemonTraits = [];
        foreach (DemonTraitCode::getPossibleValues() as $demonTraitCodeValue) {
            if ($demonTraitCodeValue === DemonTraitCode::UNLIMITED_ENDURANCE) {
                continue;
            }
            $nonUnlimitedEnduranceDemonTraits[] = $this->createDemonTrait(DemonTraitCode::getIt($demonTraitCodeValue));
        }

        $demon = $this->createDemon($demonCode, $this->createTables($demonsTable), [], $nonUnlimitedEnduranceDemonTraits);
        self::assertFalse($demon->hasUnlimitedEndurance(), 'Unlimited endurance has not been expected');

        $demon = $this->createDemon(
            $demonCode,
            $this->createTables($demonsTable),
            [],
            [$this->createDemonTrait(DemonTraitCode::getIt(DemonTraitCode::UNLIMITED_ENDURANCE))]
        );
        self::assertTrue($demon->hasUnlimitedEndurance(), 'Unlimited endurance has been expected');
    }

    /**
     * @test
     * @throws \Exception
     */
    public function I_can_create_it_with_addition_for_every_demon()
    {
        $parameterValues = [
            DemonMutableParameterCode::DEMON_CAPACITY => 1,
            DemonMutableParameterCode::DEMON_ENDURANCE => 2,
            DemonMutableParameterCode::DEMON_ACTIVATION_DURATION => 3,
            DemonMutableParameterCode::DEMON_QUALITY => 4,
            DemonMutableParameterCode::DEMON_RADIUS => 5,
            DemonMutableParameterCode::DEMON_AREA => 6,
            DemonMutableParameterCode::DEMON_INVISIBILITY => 7,
            DemonMutableParameterCode::DEMON_ARMOR => 8,
            DemonMutableParameterCode::SPELL_SPEED => 9,
            DemonMutableParameterCode::DEMON_STRENGTH => 10,
            DemonMutableParameterCode::DEMON_AGILITY => 11,
            DemonMutableParameterCode::DEMON_KNACK => 12,
        ];
        $missedParameters = array_diff(DemonMutableParameterCode::getPossibleValues(), array_keys($parameterValues));
        self::assertCount(
            0,
            $missedParameters,
            'We have missed some mutable parameters: ' . implode(',', $missedParameters)
        );
        $parameterChanges = [];
        foreach (DemonCode::getPossibleValues() as $demonValue) {
            $demonCode = DemonCode::getIt($demonValue);
            $demonsTable = $this->createDemonsTable();
            $baseParameters = [];
            foreach (DemonMutableParameterCode::getPossibleValues() as $mutableParameterName) {
                /** like instance of @see SpellSpeed */
                $baseParameter = $this->createParameter($mutableParameterName);
                $this->addParameterGetter($mutableParameterName, $demonCode, $demonsTable, $baseParameter);
                $this->addDefaultValueGetter($baseParameter, $defaultValue = \random_int(-5, 5));
                $baseParameters[$mutableParameterName] = $baseParameter;
                $parameterChanges[$mutableParameterName] = $parameterValues[$mutableParameterName] - $defaultValue;
            }
            $demon = $this->createDemon($demonCode, $this->createTables($demonsTable), $parameterValues);
            self::assertSame($demonCode, $demon->getDemonCode());
            foreach (DemonMutableParameterCode::getPossibleValues() as $mutableParameterName) {
                $baseParameter = $baseParameters[$mutableParameterName];
                $change = $parameterChanges[$mutableParameterName];
                $this->addWithAdditionGetter(
                    $change,
                    $baseParameter,
                    $changedParameter = $this->createParameter($mutableParameterName)
                );
                $this->addValueGetter($changedParameter, 123);
                /** like @see Demon::getCurrentSpellRadius() */
                $getCurrentParameter = StringTools::assembleGetterForName('current' . $mutableParameterName);
                /** @var CastingParameter $currentParameter */
                $currentParameter = $demon->$getCurrentParameter();
                self::assertInstanceOf($this->getDemonParameterClass($mutableParameterName), $currentParameter);
                self::assertSame(123, $currentParameter->getValue());
            }
        }
    }

    /**
     * @test
     */
    public function I_get_basic_difficulty_change_without_any_parameter()
    {
        $demonCode = DemonCode::getIt(DemonCode::CRON);
        $demonsTable = $this->createDemonsTable();
        $this->addEmptyParameterGetters($demonsTable, $demonCode);

        $currentDifficulty = $this->createDifficulty();
        $this->addDifficultyGetter($demonsTable, $demonCode, $currentDifficulty);

        $demon = $this->createDemon($demonCode, $this->createTables($demonsTable));

        self::assertSame($currentDifficulty, $demon->getCurrentDifficulty());
    }

    private function addEmptyParameterGetters(MockInterface $demonsTable, DemonCode $demonCode)
    {
        foreach (DemonMutableParameterCode::getPossibleValues() as $mutableParameterName) {
            $this->addParameterGetter($mutableParameterName, $demonCode, $demonsTable, null /* no parameter */);
        }
    }

    private function addDifficultyGetter(MockInterface $demonsTable, DemonCode $demonCode, Difficulty $difficulty)
    {
        $demonsTable->shouldReceive('getDifficulty')
            ->with($demonCode)
            ->andReturn($difficulty);
    }

    /**
     * @test
     */
    public function I_can_get_current_evocation()
    {
        $demonsTable = $this->createDemonsTable();
        $demon = $this->createDemon($demonCode = DemonCode::getIt(DemonCode::DEADY), $this->createTables($demonsTable));
        $demonsTable->shouldReceive('getEvocation')
            ->with($demonCode)
            ->andReturn($evocation = $this->mockery(Evocation::class));
        self::assertSame($evocation, $demon->getCurrentEvocation());
    }

    /**
     * @test
     */
    public function I_can_not_add_non_zero_addition_to_unused_parameter()
    {
        $this->expectException(\DrdPlus\Tables\Theurgist\Demons\Exceptions\UnknownDemonParameter::class);
        $this->expectExceptionMessageRegExp('~4~');
        $addParameterGetter = function (MockInterface $demonsTable, ?DemonKnack $demonKnack) {
            $this->addParameterGetter(
                DemonMutableParameterCode::DEMON_KNACK,
                DemonCode::getIt(DemonCode::DEADY),
                $demonsTable,
                $demonKnack
            );
        };
        $createDemon = function (DemonsTable $demonsTable) {
            return new Demon(
                DemonCode::getIt(DemonCode::DEADY),
                $this->createTables($demonsTable),
                [DemonMutableParameterCode::DEMON_KNACK => 4],
                []
            );
        };
        try {
            $demonsTable = $this->createDemonsTable();
            $demonKnack = $this->createParameter(DemonMutableParameterCode::DEMON_KNACK);
            /** @var DemonKnack $demonKnack */
            $addParameterGetter($demonsTable, $demonKnack);
            $this->addDefaultValueGetter($demonKnack, 1);
            $createDemon($demonsTable);
        } catch (\Exception $exception) {
            self::fail('No exception expected so far: ' . $exception->getMessage() . '; ' . $exception->getTraceAsString());
        }
        $demonsTable = $this->createDemonsTable();
        $addParameterGetter($demonsTable, null /* unused */);
        $createDemon($demonsTable);
    }

    /**
     * @test
     */
    public function I_can_not_create_it_with_unknown_parameter()
    {
        $this->expectException(\DrdPlus\Tables\Theurgist\Demons\Exceptions\UnknownDemonParameter::class);
        $this->expectExceptionMessageRegExp('~fat~');
        new Demon(
            DemonCode::getIt(DemonCode::GOLEM),
            $this->createTables($this->createDemonsTable()),
            ['fat' => 0],
            []
        );
    }

    /**
     * @test
     */
    public function I_can_not_create_it_with_invalid_demon_trait()
    {
        $this->expectException(\DrdPlus\Tables\Theurgist\Demons\Exceptions\InvalidDemonTrait::class);
        $this->expectExceptionMessageRegExp('~stdClass~');
        new Demon(
            DemonCode::getIt(DemonCode::IMP),
            $this->createTables($this->createDemonsTable()),
            [],
            [new \stdClass()]
        );
    }

    /**
     * @test
     */
    public function I_can_not_create_it_with_invalid_mutable_parameter()
    {
        $this->expectException(\DrdPlus\Tables\Theurgist\Demons\Exceptions\InvalidValueForDemonParameter::class);
        $this->expectExceptionMessageRegExp('~indefinite~');
        new Demon(DemonCode::getIt(DemonCode::WARDEN), Tables::getIt(), [DemonMutableParameterCode::DEMON_CAPACITY => 'indefinite'], []);
    }

    /**
     * @test
     */
    public function I_get_current_difficulty_affected_by_parameters()
    {
        $demonCode = DemonCode::getIt(DemonCode::DEMON_GAMBLER);
        $demonsTable = $this->createDemonsTable();
        $currentDifficultyIncrement = 1;
        $expectedDifficultyChange = 0;
        foreach (DemonMutableParameterCode::getPossibleValues() as $mutableParameterName) {
            $parameter = $this->createParameter($mutableParameterName);
            $parameter->shouldReceive('getWithAddition')
                ->with(0)
                ->andReturn($parameter); // no change here
            $parameter->shouldReceive('getAdditionByDifficulty')
                ->andReturn($additionByDifficulty = $this->createAdditionByDifficulty($currentDifficultyIncrement));
            $this->addParameterGetter($mutableParameterName, $demonCode, $demonsTable, $parameter);
            $expectedDifficultyChange += $currentDifficultyIncrement;
            $currentDifficultyIncrement++; // just some change
        }
        $difficulty = $this->createDifficulty();
        $this->addDemonChangedDifficultyGetter($demonsTable, $demonCode, $expectedDifficultyChange, $difficulty);
        $demon = $this->createDemon($demonCode, $this->createTables($demonsTable));
        self::assertSame(
            $difficulty,
            $demon->getCurrentDifficulty(),
            "Expected difficulty change $expectedDifficultyChange"
        );
    }

    private function addDemonChangedDifficultyGetter(
        MockInterface $demonTable,
        DemonCode $expectedDemonCode,
        int $expectedDifficultyChange,
        Difficulty $demonChangedDifficulty = null
    )
    {
        $demonTable->shouldReceive('getDifficulty')
            ->with($expectedDemonCode)
            ->andReturn($demonDifficulty = $this->mockery(Difficulty::class));
        $demonDifficulty->shouldReceive('getWithDifficultyChange')
            ->with($expectedDifficultyChange)
            ->andReturn($demonChangedDifficulty ?? $this->mockery(Difficulty::class));
    }

    /**
     * @param int|null $currentRealmsIncrement
     * @return Difficulty|MockInterface
     */
    private function createDifficulty(int $currentRealmsIncrement = null): Difficulty
    {
        $difficulty = $this->mockery(Difficulty::class);
        if ($currentRealmsIncrement !== null) {
            $difficulty->shouldReceive('getCurrentRealmsIncrement')
                ->andReturn($currentRealmsIncrement);
        }
        return $difficulty;
    }

    /**
     * @param int|null $currentDifficultyIncrement
     * @return AdditionByDifficulty|MockInterface
     */
    private function createAdditionByDifficulty(int $currentDifficultyIncrement = null): AdditionByDifficulty
    {
        $additionByDifficulty = $this->mockery(AdditionByDifficulty::class);
        if ($currentDifficultyIncrement !== null) {
            $additionByDifficulty->shouldReceive('getCurrentDifficultyIncrement')
                ->andReturn($currentDifficultyIncrement);
        }
        return $additionByDifficulty;
    }

    /**
     * @param int $realmsChange
     * @param string|null $affectionPeriodCodeValue
     * @return RealmsAffection|MockInterface
     */
    private function createRealmsAffection(int $realmsChange, string $affectionPeriodCodeValue = null): RealmsAffection
    {
        $realmsAffection = $this->mockery(RealmsAffection::class);
        $realmsAffection->shouldReceive('getValue')
            ->andReturn($realmsChange);
        if ($affectionPeriodCodeValue !== null) {
            $realmsAffection->shouldReceive('getAffectionPeriodCode')
                ->andReturn($affectionPeriodCode = $this->mockery(AffectionPeriodCode::class));
            $affectionPeriodCode->shouldReceive('getValue')
                ->andReturn($affectionPeriodCodeValue);
        }
        return $realmsAffection;
    }

    /**
     * @param DemonTraitCode $demonTraitCode
     * @return DemonTrait|MockInterface
     */
    private function createDemonTrait(DemonTraitCode $demonTraitCode): DemonTrait
    {
        $demonTrait = $this->mockery(DemonTrait::class);
        $demonTrait->shouldReceive('getDemonTraitCode')
            ->andReturn($demonTraitCode);

        return $demonTrait;
    }

    /**
     * @test
     */
    public function I_get_required_realm_of_demon_without_traits()
    {
        $demonCode = DemonCode::getIt(DemonCode::WARDEN);
        $demonsTable = $this->createDemonsTable();
        $this->addEmptyParameterGetters($demonsTable, $demonCode);

        $realmsIncrementByDifficulty = 123456;
        $this->addDifficultyGetterWithRealmsIncrement($demonsTable, $demonCode, $realmsIncrementByDifficulty);
        $requiredRealm = $this->createRealm();
        $this->addRealmGetter($demonsTable, $demonCode, $realmsIncrementByDifficulty, $requiredRealm);
        $demon = new Demon($demonCode, $this->createTables($demonsTable), [], []);
        self::assertSame(
            $requiredRealm,
            $demon->getRequiredRealm(),
            "Expected different realm"
        );
    }

    /**
     * @return Realm|MockInterface
     */
    private function createRealm(): Realm
    {
        return $this->mockery(Realm::class);
    }

    private function addDifficultyGetterWithRealmsIncrement(MockInterface $demonsTable, DemonCode $demonCode, int $realmsIncrement)
    {
        $demonsTable->shouldReceive('getDifficulty')
            ->with($demonCode)
            ->andReturn($difficulty = $this->createDifficulty());
        $difficulty->shouldReceive('getCurrentRealmsIncrement')
            ->andReturn($realmsIncrement);
    }

    private function addRealmGetter(
        MockInterface $demonsTable,
        DemonCode $demonCode,
        int $expectedRealmsIncrement,
        Realm $requiredRealm
    )
    {
        $demonsTable->shouldReceive('getRealm')
            ->with($demonCode)
            ->andReturn($realm = $this->createRealm());
        $realm->shouldReceive('add')
            ->with($expectedRealmsIncrement)
            ->andReturn($requiredRealm);
    }

    /**
     * @test
     */
    public function I_get_effective_realm_affected_by_parameters()
    {
        $demonCode = DemonCode::getIt(DemonCode::GOLEM);
        $demonsTable = $this->createDemonsTable();
        $currentDifficultyIncrement = 1;
        $expectedDifficultyChange = 0;
        foreach (DemonMutableParameterCode::getPossibleValues() as $mutableParameterName) {
            $parameter = $this->createParameter($mutableParameterName);
            $parameter->shouldReceive('getWithAddition')
                ->with(0)
                ->andReturn($parameter); // no change here
            $parameter->shouldReceive('getAdditionByDifficulty')
                ->andReturn($additionByDifficulty = $this->createAdditionByDifficulty($currentDifficultyIncrement));
            $this->addParameterGetter($mutableParameterName, $demonCode, $demonsTable, $parameter);
            $expectedDifficultyChange += $currentDifficultyIncrement;
            $currentDifficultyIncrement++; // just some change
        }

        $expectedDifficulty = $this->createDifficulty();
        $this->addDemonChangedDifficultyGetter($demonsTable, $demonCode, $expectedDifficultyChange, $expectedDifficulty);

        $expectedDifficulty->shouldReceive('getCurrentRealmsIncrement')
            ->andReturn(555);

        $this->addRealmGetter($demonsTable, $demonCode, 555, $expectedEffectiveRealm = $this->createRealm());

        $demon = $this->createDemon($demonCode, $this->createTables($demonsTable));
        self::assertSame(
            $expectedEffectiveRealm,
            $demon->getEffectiveRealm(),
            'Expected different effective realm'
        );
    }

    /**
     * @test
     */
    public function I_get_required_realm_of_demon_with_traits()
    {
        $demonCode = DemonCode::getIt(DemonCode::WARDEN);
        $demonsTable = $this->createDemonsTable();

        $this->addEmptyParameterGetters($demonsTable, $demonCode);

        $realmsIncrementByDifficulty = 123456;
        $this->addDifficultyGetterWithRealmsIncrement($demonsTable, $demonCode, $realmsIncrementByDifficulty);
        $demonRequiredRealm = $this->createRealm();
        $demonRequiredRealm->shouldReceive('getValue')
            ->andReturn($demonRequiredRealmValue = 654321);
        $this->addRealmGetter($demonsTable, $demonCode, $realmsIncrementByDifficulty, $demonRequiredRealm);

        $demonTraits = [];
        $demonTraitRealmsAdditionValue = $demonRequiredRealmValue + 1; // just a little bit higher
        $demonTraitRealmsAdditionSum = 0;
        foreach (DemonTraitCode::getPossibleValues() as $demonTraitName) {
            $demonTraitCode = DemonTraitCode::getIt($demonTraitName);
            $demonTrait = $this->createDemonTrait($demonTraitCode);
            $demonTrait->shouldReceive('getRealmsAddition')
                ->atLeast()->once()
                ->andReturn($this->createRealmsAddition($demonTraitRealmsAdditionValue));
            $demonTraits[] = $demonTrait;
            $demonTraitRealmsAdditionSum += $demonTraitRealmsAdditionValue;
            $demonTraitRealmsAdditionValue++; // just a little increment to test using highest realm
        }

        $demonRequiredRealm->shouldReceive('add')
            ->with($demonTraitRealmsAdditionSum)
            ->andReturn($expectedRequiredRealm = $this->createRealm());

        $demon = new Demon($demonCode, $this->createTables($demonsTable), [], $demonTraits);
        self::assertSame(
            $expectedRequiredRealm,
            $demon->getRequiredRealm(),
            'Expected different realm'
        );
    }

    /**
     * @param int $value
     * @return RealmsAddition|MockInterface
     */
    private function createRealmsAddition(int $value): RealmsAddition
    {
        $realmsAddition = $this->mockery(RealmsAddition::class);
        $realmsAddition->shouldReceive('getValue')
            ->andReturn($value);
        return $realmsAddition;
    }

    /**
     * @test
     */
    public function I_can_get_demon_body_code()
    {
        $demonCode = DemonCode::getIt(DemonCode::WARDEN);
        $demonsTable = $this->createDemonsTable();
        $demonsTable->shouldReceive('getDemonBodyCode')
            ->with($demonCode)
            ->andReturn($demonBodyCode = $this->mockery(DemonBodyCode::class));
        $tables = $this->createTables($demonsTable);
        $demon = new Demon($demonCode, $tables, [], []);
        self::assertSame($demonBodyCode, $demon->getDemonBodyCode());
    }

    /**
     * @test
     */
    public function I_can_get_demon_kind_code()
    {
        $demonCode = DemonCode::getIt(DemonCode::DEMON_OF_MOVEMENT);
        $demonsTable = $this->createDemonsTable();
        $demonsTable->shouldReceive('getDemonKindCode')
            ->with($demonCode)
            ->andReturn($demonKindCode = $this->mockery(DemonKindCode::class));
        $tables = $this->createTables($demonsTable);
        $demon = new Demon($demonCode, $tables, [], []);
        self::assertSame($demonKindCode, $demon->getDemonKindCode());
    }

    /**
     * @test
     */
    public function I_can_get_demon_will()
    {
        $demonCode = DemonCode::getIt(DemonCode::DEMON_OF_MOVEMENT);
        $demonsTable = $this->createDemonsTable();
        $demonsTable->shouldReceive('getDemonWill')
            ->with($demonCode)
            ->andReturn($demonWill = $this->mockery(DemonWill::class));
        $tables = $this->createTables($demonsTable);
        $demon = new Demon($demonCode, $tables, [], []);
        self::assertSame($demonWill, $demon->getBaseDemonWill());
        self::assertSame($demonWill, $demon->getCurrentDemonWill());
    }

    /**
     * @test
     */
    public function I_can_get_base_realms_affection()
    {
        $demonCode = DemonCode::getIt(DemonCode::DEMON_OF_MOVEMENT);
        $demonsTable = $this->createDemonsTable();
        $demonsTable->shouldReceive('getRealmsAffection')
            ->with($demonCode)
            ->andReturn($realmsAffection = $this->mockery(RealmsAffection::class));
        $tables = $this->createTables($demonsTable);
        $demon = new Demon($demonCode, $tables, [], []);
        self::assertSame($realmsAffection, $demon->getBaseRealmsAffection());
    }

    /**
     * @test
     */
    public function I_can_get_current_realms_affection()
    {
        $demonCode = DemonCode::getIt(DemonCode::DEMON_OF_MOVEMENT);
        $demonsTable = $this->createDemonsTable();
        $demonsTable->shouldReceive('getRealmsAffection')
            ->with($demonCode)
            ->atLeast()->once()
            ->andReturn($realmsAffection = $this->createRealmsAffection(-1, AffectionPeriodCode::LIFE));
        $demonTraits = [];
        $realmsAffectionValue = -10;
        $realmsAffectionValueSum = 0;
        foreach (DemonTraitCode::getPossibleValues() as $demonTraitValue) {
            $demonTraitCode = DemonTraitCode::getIt($demonTraitValue);
            $demonTrait = $this->createDemonTrait($demonTraitCode);
            $demonTrait->shouldReceive('getRealmsAffection')
                ->atLeast()->once()
                ->andReturn($this->createRealmsAffection($realmsAffectionValue, AffectionPeriodCode::MONTHLY));
            $demonTraits[] = $demonTrait;
            $realmsAffectionValueSum += $realmsAffectionValue;
            $realmsAffectionValue--; // just some change
        }
        $tables = $this->createTables($demonsTable);
        $demon = new Demon($demonCode, $tables, [], $demonTraits);
        self::assertEquals(
            [
                AffectionPeriodCode::LIFE => new RealmsAffection([-1, AffectionPeriodCode::LIFE]),
                AffectionPeriodCode::MONTHLY => new RealmsAffection([$realmsAffectionValueSum, AffectionPeriodCode::MONTHLY]),
            ],
            $demon->getCurrentRealmsAffections()
        );
    }
}