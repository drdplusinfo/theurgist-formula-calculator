<?php
declare(strict_types=1);

namespace DrdPlus\Tables;

use DrdPlus\Codes\Armaments\ArmamentCode;
use DrdPlus\Codes\Armaments\ArmorCode;
use DrdPlus\Codes\Armaments\ArrowCode;
use DrdPlus\Codes\Armaments\BodyArmorCode;
use DrdPlus\Codes\Armaments\DartCode;
use DrdPlus\Codes\Armaments\HelmCode;
use DrdPlus\Codes\Armaments\MeleeWeaponCode;
use DrdPlus\Codes\Armaments\MeleeWeaponlikeCode;
use DrdPlus\Codes\Armaments\ProjectileCode;
use DrdPlus\Codes\Armaments\ProtectiveArmamentCode;
use DrdPlus\Codes\Armaments\RangedWeaponCode;
use DrdPlus\Codes\Armaments\ShieldCode;
use DrdPlus\Codes\Armaments\SlingStoneCode;
use DrdPlus\Codes\Armaments\WeaponlikeCode;
use DrdPlus\Codes\Partials\AbstractCode;
use DrdPlus\Tables\Armaments\Armors\ArmorStrengthSanctionsTable;
use DrdPlus\Tables\Armaments\Armors\BodyArmorsTable;
use DrdPlus\Tables\Armaments\Armors\HelmsTable;
use DrdPlus\Tables\Armaments\Armors\ArmorWearingSkillTable;
use DrdPlus\Tables\Armaments\Projectiles\ArrowsTable;
use DrdPlus\Tables\Armaments\Projectiles\DartsTable;
use DrdPlus\Tables\Armaments\Projectiles\SlingStonesTable;
use DrdPlus\Tables\Armaments\Shields\ShieldUsageSkillTable;
use DrdPlus\Tables\Armaments\Shields\ShieldStrengthSanctionsTable;
use DrdPlus\Tables\Armaments\Shields\ShieldsTable;
use DrdPlus\Tables\Armaments\Weapons\Melee\MeleeWeaponStrengthSanctionsTable;
use DrdPlus\Tables\Armaments\Weapons\Melee\Partials\MeleeWeaponsTable;
use DrdPlus\Tables\Armaments\Weapons\Ranged\Partials\RangedWeaponsTable;
use DrdPlus\Tables\Armaments\Weapons\Ranged\RangedWeaponStrengthSanctionsTable;
use DrdPlus\Tests\Tables\TableTest;
use Granam\Tests\Tools\TestWithMockery;

class TablesTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_get_any_table(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $reflectionClass = new \ReflectionClass(Tables::class);
        $tablesInstance = $reflectionClass->getProperty('tablesInstance');
        $tablesInstance->setAccessible(true);
        $tablesInstance->setValue(null);
        $tablesInstance->setAccessible(false);
        $tables = Tables::getIt();
        foreach ($this->getExpectedTableClasses() as $expectedTableClass) {
            $baseName = \preg_replace('~(?:.+[\\\])?(\w+)$~', '$1', $expectedTableClass);
            $getTable = "get{$baseName}";
            self::assertTrue(
                \method_exists($tables, $getTable),
                "'Tables' factory should has getter {$getTable} for {$expectedTableClass} (or the class should be abstract ?)"
            );
            $table = $tables->$getTable();
            self::assertInstanceOf($expectedTableClass, $table);
        }
    }

    /**
     * @test
     */
    public function I_can_iterate_through_tables(): void
    {
        $tables = Tables::getIt();
        $fetchedTableClasses = [];
        foreach ($tables as $table) {
            $fetchedTableClasses[] = \get_class($table);
        }
        $expectedTableClasses = $this->getExpectedTableClasses();
        \sort($expectedTableClasses);
        \sort($fetchedTableClasses);

        self::assertSameSize(
            $expectedTableClasses,
            $fetchedTableClasses,
            'Tables factory should give ' . \implode(',', \array_diff($expectedTableClasses, $fetchedTableClasses))
            . ' on iterating'
        );
        self::assertEquals($expectedTableClasses, $fetchedTableClasses);
    }

    private function getExpectedTableClasses(): array
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $tablesReflection = new \ReflectionClass(Tables::class);
        $rootDir = \dirname($tablesReflection->getFileName());
        $rootNamespace = $tablesReflection->getNamespaceName();

        return $this->scanForTables($rootDir, $rootNamespace);
    }

    private function scanForTables(string $rootDir, string $rootNamespace): array
    {
        $tableClasses = [];
        foreach (\scandir($rootDir, SCANDIR_SORT_NONE) as $folder) {
            $folderFullPath = $rootDir . DIRECTORY_SEPARATOR . $folder;
            if ($folder !== '.' && $folder !== '..') {
                if (\is_dir($folderFullPath)) {
                    foreach ($this->scanForTables($folderFullPath, $rootNamespace . '\\' . $folder) as $foundTable) {
                        $tableClasses[] = $foundTable;
                    }
                } elseif (\is_file($folderFullPath) && \preg_match('~(?<classBasename>\w+(?:Table)?)\.php$~', $folder, $matches)) {
                    /** @noinspection PhpUnhandledExceptionInspection */
                    $reflectionClass = new \ReflectionClass($rootNamespace . '\\' . $matches['classBasename']);
                    if ($reflectionClass->isInstantiable() && $reflectionClass->implementsInterface(Table::class)) {
                        self::assertRegExp(
                            '~Table$~',
                            $reflectionClass->getName(),
                            'Every single table should ends by "Table"'
                        );
                        $tableClasses[] = $reflectionClass->getName();
                    }
                }
            }
        }

        return $tableClasses;
    }

    /**
     * @test
     * @dataProvider provideArmamentCodeAndExpectedTableClass
     * @param ArmamentCode $armamentCode
     * @param string $expectedTableClass
     */
    public function I_can_get_every_armament_table_by_armament_code(ArmamentCode $armamentCode, $expectedTableClass): void
    {
        self::assertInstanceOf($expectedTableClass, Tables::getIt()->getArmamentsTableByArmamentCode($armamentCode));
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function provideArmamentCodeAndExpectedTableClass(): array
    {
        $values = [];
        foreach ([
                     BodyArmorCode::class => BodyArmorsTable::class,
                     HelmCode::class => HelmsTable::class,
                     ShieldCode::class => ShieldsTable::class,
                     MeleeWeaponCode::class => MeleeWeaponsTable::class,
                     RangedWeaponCode::class => RangedWeaponsTable::class,
                     ArrowCode::class => ArrowsTable::class,
                     DartCode::class => DartsTable::class,
                     SlingStoneCode::class => SlingStonesTable::class,
                 ] as $codeClass => $tableClass) {
            foreach ($this->pairCodesWithClass($this->getCodes($codeClass), $tableClass) as $pair) {
                $values[] = $pair;
            }
        }

        return $values;
    }

    /**
     * @param string $class
     * @return array
     * @throws \ReflectionException
     */
    private function getCodes(string $class): array
    {
        $codes = [];
        /** @var AbstractCode $class */
        $reflectionClass = new \ReflectionClass($class);
        foreach ($reflectionClass->getConstants() as $constant) {
            $codes[] = $class::getIt($constant);
        }

        return $codes;
    }

    /**
     * @param array $codes
     * @param $class
     * @return array
     */
    private function pairCodesWithClass(array $codes, string $class): array
    {
        return array_map(
            function ($code) use ($class) {
                return [$code, $class];
            },
            $codes
        );
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Armaments\Exceptions\UnknownArmament
     */
    public function I_do_not_get_any_armament_table_by_unknown_code(): void
    {
        /** @var ArmamentCode $armamentCode */
        $armamentCode = $this->mockery(ArmamentCode::class);
        Tables::getIt()->getArmamentsTableByArmamentCode($armamentCode);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     */
    public function I_do_not_get_any_weaponlike_table_by_unknown_code(): void
    {
        /** @var WeaponlikeCode $weaponlikeCode */
        $weaponlikeCode = $this->mockery(WeaponlikeCode::class);
        Tables::getIt()->getWeaponlikeTableByWeaponlikeCode($weaponlikeCode);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Armaments\Exceptions\UnknownMeleeWeaponlike
     */
    public function I_do_not_get_any_melee_weaponlike_table_by_unknown_code(): void
    {
        /** @var MeleeWeaponlikeCode $meleeWeaponlikeCode */
        $meleeWeaponlikeCode = $this->mockery(MeleeWeaponlikeCode::class);
        Tables::getIt()->getMeleeWeaponlikeTableByMeleeWeaponlikeCode($meleeWeaponlikeCode);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Armaments\Exceptions\UnknownMeleeWeapon
     * @expectedExceptionMessageRegExp ~denigration~
     */
    public function I_do_not_get_any_melee_weapons_table_by_unknown_code(): void
    {
        /** @var MeleeWeaponCode $meleeWeaponCode */
        $meleeWeaponCode = $this->createMeleeWeaponCode('denigration', 'poisonous language');
        Tables::getIt()->getMeleeWeaponsTableByMeleeWeaponCode($meleeWeaponCode);
    }

    /**
     * @param $value
     * @param string $matchingWeaponGroup
     * @return \Mockery\MockInterface|MeleeWeaponCode
     */
    private function createMeleeWeaponCode($value, $matchingWeaponGroup)
    {
        $code = $this->mockery(MeleeWeaponCode::class);
        $code->shouldReceive('getValue')
            ->andReturn($value);
        $code->shouldReceive('__toString')
            ->andReturn((string)$value);
        $weaponGroups = [
            'axe', 'knifeOrDagger', 'maceOrClub', 'morningstarOrMorgenstern',
            'saberOrBowieKnife', 'staffOrSpear', 'sword', 'unarmed', 'voulgeOrTrident',
        ];
        foreach ($weaponGroups as $weaponGroup) {
            $code->shouldReceive('is' . ucfirst($weaponGroup))
                ->andReturn($weaponGroup === $matchingWeaponGroup);
        }

        return $code;
    }

    /**
     * @test
     * @dataProvider provideArmamentCodeAndExpectedSanctionsTable
     * @param ArmamentCode $armamentCode
     * @param string $expectedTableClass
     */
    public function I_can_get_table_with_sanctions_by_missing_strength_for_every_armament(
        ArmamentCode $armamentCode,
        string $expectedTableClass
    ): void
    {
        self::assertInstanceOf(
            $expectedTableClass,
            Tables::getIt()->getArmamentStrengthSanctionsTableByCode($armamentCode)
        );
    }

    public function provideArmamentCodeAndExpectedSanctionsTable(): array
    {
        return [
            [BodyArmorCode::getIt(BodyArmorCode::HOBNAILED_ARMOR), ArmorStrengthSanctionsTable::class],
            [HelmCode::getIt(HelmCode::GREAT_HELM), ArmorStrengthSanctionsTable::class],
            [RangedWeaponCode::getIt(RangedWeaponCode::HEAVY_CROSSBOW), RangedWeaponStrengthSanctionsTable::class],
            [MeleeWeaponCode::getIt(MeleeWeaponCode::CLUB), MeleeWeaponStrengthSanctionsTable::class],
            [ShieldCode::getIt(ShieldCode::BUCKLER), ShieldStrengthSanctionsTable::class],
        ];
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Armaments\Exceptions\UnknownArmament
     */
    public function I_do_not_get_any_sanctions_table_by_unknown_code(): void
    {
        /** @var ArmorCode $armamentCode */
        $armamentCode = $this->mockery(ArmamentCode::class);
        Tables::getIt()->getArmamentStrengthSanctionsTableByCode($armamentCode);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Armaments\Exceptions\UnknownWeaponlike
     */
    public function I_do_not_get_any_weaponlike_sanctions_table_by_unknown_code(): void
    {
        /** @var WeaponlikeCode $weaponlikeCode */
        $weaponlikeCode = $this->mockery(WeaponlikeCode::class);
        Tables::getIt()->getWeaponlikeStrengthSanctionsTableByCode($weaponlikeCode);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Armaments\Exceptions\UnknownMeleeWeaponlike
     */
    public function I_do_not_get_any_melee_weaponlike_sanctions_table_by_unknown_code(): void
    {
        /** @var MeleeWeaponlikeCode $meleeWeaponlikeCode */
        $meleeWeaponlikeCode = $this->mockery(MeleeWeaponlikeCode::class);
        Tables::getIt()->getMeleeWeaponlikeStrengthSanctionsTableByCode($meleeWeaponlikeCode);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Armaments\Exceptions\UnknownRangedWeapon
     * @expectedExceptionMessageRegExp ~wallop~
     */
    public function I_do_not_get_range_weapons_table_by_unknown_code(): void
    {
        /** @var RangedWeaponCode $rangeWeaponCode */
        $rangeWeaponCode = $this->createRangedWeaponCode('wallop', 'bio weapons');
        Tables::getIt()->getRangedWeaponsTableByRangedWeaponCode($rangeWeaponCode);
    }

    /**
     * @param $value
     * @param string $matchingWeaponGroup
     * @return \Mockery\MockInterface|RangedWeaponCode
     */
    private function createRangedWeaponCode(string $value, string $matchingWeaponGroup)
    {
        $code = $this->mockery(RangedWeaponCode::class);
        $code->shouldReceive('getValue')
            ->andReturn($value);
        $code->shouldReceive('__toString')
            ->andReturn($value);
        $rangeWeaponGroups = ['bow', 'arrow', 'crossbow', 'dart', 'throwingWeapon', 'slingStone'];
        /** @noinspection PhpUnhandledExceptionInspection */
        $codeReflection = new \ReflectionClass(RangedWeaponCode::class);
        foreach ($rangeWeaponGroups as $weaponGroup) {
            $isType = 'is' . \ucfirst($weaponGroup);
            if ($codeReflection->hasMethod($isType)) {
                $code->shouldReceive('is' . \ucfirst($weaponGroup))
                    ->andReturn($weaponGroup === $matchingWeaponGroup);
            }
        }

        return $code;
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Armaments\Exceptions\UnknownArmor
     */
    public function I_do_not_get_any_armors_table_by_unknown_code(): void
    {
        /** @var ArmorCode $armorCode */
        $armorCode = $this->mockery(ArmorCode::class);
        Tables::getIt()->getArmorsTableByArmorCode($armorCode);
    }

    /**
     * @test
     * @dataProvider provideProtectiveArmamentCodeAndExpectedSanctionsTable
     * @param ProtectiveArmamentCode $protectiveArmamentCode
     * @param string $expectedTableClass
     */
    public function I_can_get_table_with_sanctions_by_missing_skill_for_every_protective_armament(
        ProtectiveArmamentCode $protectiveArmamentCode,
        string $expectedTableClass
    ): void
    {
        self::assertInstanceOf(
            $expectedTableClass,
            Tables::getIt()->getProtectiveArmamentMissingSkillTableByCode($protectiveArmamentCode)
        );
    }

    public function provideProtectiveArmamentCodeAndExpectedSanctionsTable(): array
    {
        return [
            [BodyArmorCode::getIt(BodyArmorCode::HOBNAILED_ARMOR), ArmorWearingSkillTable::class],
            [HelmCode::getIt(HelmCode::GREAT_HELM), ArmorWearingSkillTable::class],
            [ShieldCode::getIt(ShieldCode::BUCKLER), ShieldUsageSkillTable::class],
        ];
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Armaments\Exceptions\UnknownProtectiveArmament
     */
    public function I_do_not_get_table_any_sanctions_by_missing_skill_table_for_unknown_code(): void
    {
        /** @var ProtectiveArmamentCode $protectiveArmamentCode */
        $protectiveArmamentCode = $this->mockery(ProtectiveArmamentCode::class);
        Tables::getIt()->getProtectiveArmamentMissingSkillTableByCode($protectiveArmamentCode);
    }

    /**
     * @test
     * @dataProvider provideProtectiveArmamentCodeAndExpectedRestrictionTable
     * @param ProtectiveArmamentCode $protectiveArmamentCode
     * @param string $expectedTableClass
     */
    public function I_can_get_table_with_restriction_for_every_protective_armament(
        ProtectiveArmamentCode $protectiveArmamentCode,
        string $expectedTableClass
    ): void
    {
        self::assertInstanceOf(
            $expectedTableClass,
            Tables::getIt()->getProtectiveArmamentsTable($protectiveArmamentCode)
        );
    }

    public function provideProtectiveArmamentCodeAndExpectedRestrictionTable(): array
    {
        return [
            [BodyArmorCode::getIt(BodyArmorCode::HOBNAILED_ARMOR), BodyArmorsTable::class],
            [HelmCode::getIt(HelmCode::GREAT_HELM), HelmsTable::class],
            [ShieldCode::getIt(ShieldCode::BUCKLER), ShieldsTable::class],
        ];
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Armaments\Exceptions\UnknownProtectiveArmament
     */
    public function I_do_not_get_table_any_restriction_table_for_unknown_code(): void
    {
        /** @var ProtectiveArmamentCode $protectiveArmamentCode */
        $protectiveArmamentCode = $this->mockery(ProtectiveArmamentCode::class);
        Tables::getIt()->getProtectiveArmamentsTable($protectiveArmamentCode);
    }

    /**
     * @test
     */
    public function Every_table_is_tested_by_default_test(): void
    {
        foreach ($this->getExpectedTableClasses() as $expectedTableClass) {
            $expectedTableTestClass = str_replace('\Tables\\', '\Tests\Tables\\', $expectedTableClass) . 'Test';
            self::assertTrue(class_exists($expectedTableTestClass), 'Missing test for table ' . $expectedTableClass);
            self::assertTrue(
                is_a($expectedTableTestClass, TableTest::class, true),
                "Table test {$expectedTableTestClass} should extends " . TableTest::class
            );
        }
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Armaments\Exceptions\UnknownProjectile
     * @expectedExceptionMessageRegExp ~foo~
     */
    public function I_can_not_get_projectiles_table_for_unknown_projectile(): void
    {
        $projectile = $this->mockery(ProjectileCode::class);
        $projectile->shouldReceive('isArrow')->andReturn(false);
        $projectile->shouldReceive('isDart')->andReturn(false);
        $projectile->shouldReceive('isSlingStone')->andReturn(false);
        $projectile->shouldReceive('__toString')->andReturn('foo');
        /** @var ProjectileCode $projectile */
        Tables::getIt()->getProjectilesTableByProjectiveCode($projectile);
    }
}