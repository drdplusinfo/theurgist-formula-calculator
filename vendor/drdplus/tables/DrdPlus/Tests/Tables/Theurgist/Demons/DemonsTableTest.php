<?php

namespace DrdPlus\Tests\Tables\Theurgist\Demons;

use DrdPlus\Codes\Theurgist\DemonBodyCode;
use DrdPlus\Codes\Theurgist\DemonCode;
use DrdPlus\Codes\Theurgist\DemonKindCode;
use DrdPlus\Codes\Theurgist\DemonTraitCode;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonArea;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonWill;
use DrdPlus\Tables\Theurgist\Demons\DemonsTable;
use DrdPlus\Tables\Theurgist\Demons\DemonTrait;
use DrdPlus\Tests\Tables\Theurgist\AbstractTheurgistTableTest;

class DemonsTableTest extends AbstractTheurgistTableTest
{
    protected function getMandatoryParameters(): array
    {
        return [
            DemonsTable::REALM,
            DemonsTable::EVOCATION,
            DemonsTable::REALMS_AFFECTION,
            DemonsTable::DIFFICULTY,
            DemonsTable::DEMON_WILL,
        ];
    }

    protected function getMainCodeClass(): string
    {
        return DemonCode::class;
    }

    protected function getOptionalParameters(): array
    {
        return [
            DemonsTable::DEMON_CAPACITY,
            DemonsTable::DEMON_ENDURANCE,
            DemonsTable::SPELL_SPEED,
            DemonsTable::DEMON_QUALITY,
            DemonsTable::DEMON_ACTIVATION_DURATION,
            DemonsTable::DEMON_RADIUS,
            DemonsTable::DEMON_STRENGTH,
            DemonsTable::DEMON_AGILITY,
            DemonsTable::DEMON_KNACK,
            DemonsTable::DEMON_ARMOR,
            DemonsTable::DEMON_INVISIBILITY,
            // DemonsTable::DEMON_AREA, // can not as it requires different parameters
        ];
    }

    /**
     * @test
     * @dataProvider provideDemonAndExpectedTraits
     * @param string $demonCodeValue
     * @param array $expectedDemonTraitCodeValues
     */
    public function I_can_get_demon_traits(string $demonCodeValue, array $expectedDemonTraitCodeValues)
    {
        $demonsTable = new DemonsTable(Tables::getIt());
        $demonTraits = $demonsTable->getDemonTraits(DemonCode::getIt($demonCodeValue));
        $expectedDemonTraits = array_map(
            function (string $demonTraitCodeValue) {
                return new DemonTrait(DemonTraitCode::getIt($demonTraitCodeValue), Tables::getIt());
            },
            $expectedDemonTraitCodeValues
        );
        self::assertEquals($expectedDemonTraits, $demonTraits);

    }

    public function provideDemonAndExpectedTraits(): array
    {
        return [
            DemonCode::CRON => [DemonCode::CRON, [DemonTraitCode::UNLIMITED_CAPACITY]],
            DemonCode::DEMON_OF_MOVEMENT => [DemonCode::DEMON_OF_MOVEMENT, [DemonTraitCode::CHEAP_UNLIMITED_CAPACITY]],
            DemonCode::WARDEN => [DemonCode::WARDEN, [DemonTraitCode::CASTER, DemonTraitCode::FORMULER, DemonTraitCode::BUILDER, DemonTraitCode::UNLIMITED_CAPACITY]],
            DemonCode::DEMON_OF_MUSIC => [DemonCode::DEMON_OF_MUSIC, []],
            DemonCode::DEMON_DEFENDER => [DemonCode::DEMON_DEFENDER, []],
            DemonCode::DEMON_GAMBLER => [DemonCode::DEMON_GAMBLER, []],
            DemonCode::DEMON_OF_TIRELESSNESS => [DemonCode::DEMON_OF_TIRELESSNESS, []],
            DemonCode::DEMON_OF_OMIT_VOMIT => [DemonCode::DEMON_OF_OMIT_VOMIT, []],
            DemonCode::DEMON_ATTACKER => [DemonCode::DEMON_ATTACKER, []],
            DemonCode::DEMON_OF_VISION => [DemonCode::DEMON_OF_VISION, []],
            DemonCode::GOLEM => [DemonCode::GOLEM, []],
            DemonCode::DEADY => [DemonCode::DEADY, []],
            DemonCode::BERSERK => [DemonCode::BERSERK, []],
            DemonCode::IMP => [DemonCode::IMP, []],
            DemonCode::DEMON_OF_KNOWLEDGE => [DemonCode::DEMON_OF_KNOWLEDGE, []],
            DemonCode::NAVIGATOR => [DemonCode::NAVIGATOR, []],
            DemonCode::GUARDIAN => [DemonCode::GUARDIAN, []],
            DemonCode::SPY => [DemonCode::SPY, []],
        ];
    }

    /**
     * @test
     * @dataProvider provideDemonAndExpectedBody
     * @param string $demonCodeValue
     * @param string $expectedDemonBodyCodeValue
     */
    public function I_can_get_demon_body_code(string $demonCodeValue, string $expectedDemonBodyCodeValue)
    {
        $demonsTable = new DemonsTable(Tables::getIt());
        self::assertEquals(
            DemonBodyCode::getIt($expectedDemonBodyCodeValue),
            $demonsTable->getDemonBodyCode(DemonCode::getIt($demonCodeValue))
        );
    }

    public function provideDemonAndExpectedBody(): array
    {
        return [
            DemonCode::CRON => [DemonCode::CRON, DemonBodyCode::CLOCK],
            DemonCode::DEMON_OF_MOVEMENT => [DemonCode::DEMON_OF_MOVEMENT, DemonBodyCode::PEBBLE],
            DemonCode::WARDEN => [DemonCode::WARDEN, DemonBodyCode::WAND_OR_RING],
            DemonCode::DEMON_OF_MUSIC => [DemonCode::DEMON_OF_MUSIC, DemonBodyCode::MUSIC_INSTRUMENT],
            DemonCode::DEMON_DEFENDER => [DemonCode::DEMON_DEFENDER, DemonBodyCode::ARMAMENT],
            DemonCode::DEMON_GAMBLER => [DemonCode::DEMON_GAMBLER, DemonBodyCode::AMULET],
            DemonCode::DEMON_OF_TIRELESSNESS => [DemonCode::DEMON_OF_TIRELESSNESS, DemonBodyCode::FLACON],
            DemonCode::DEMON_OF_OMIT_VOMIT => [DemonCode::DEMON_OF_OMIT_VOMIT, DemonBodyCode::BOTTLE],
            DemonCode::DEMON_ATTACKER => [DemonCode::DEMON_ATTACKER, DemonBodyCode::WEAPON],
            DemonCode::DEMON_OF_VISION => [DemonCode::DEMON_OF_VISION, DemonBodyCode::GLASSES],
            DemonCode::GOLEM => [DemonCode::GOLEM, DemonBodyCode::DUMMY],
            DemonCode::DEADY => [DemonCode::DEADY, DemonBodyCode::CORPSE],
            DemonCode::BERSERK => [DemonCode::BERSERK, DemonBodyCode::OWN],
            DemonCode::IMP => [DemonCode::IMP, DemonBodyCode::OWN],
            DemonCode::DEMON_OF_KNOWLEDGE => [DemonCode::DEMON_OF_KNOWLEDGE, DemonBodyCode::OWN],
            DemonCode::NAVIGATOR => [DemonCode::NAVIGATOR, DemonBodyCode::ROUGE],
            DemonCode::GUARDIAN => [DemonCode::GUARDIAN, DemonBodyCode::OWN],
            DemonCode::SPY => [DemonCode::SPY, DemonBodyCode::OWN],
        ];
    }

    /**
     * @test
     * @dataProvider provideDemonAndExpectedKind
     * @param string $demonCodeValue
     * @param string $expectedDemonKindCodeValue
     */
    public function I_can_get_demon_kind(string $demonCodeValue, string $expectedDemonKindCodeValue)
    {
        $demonsTable = new DemonsTable(Tables::getIt());
        self::assertEquals(
            DemonKindCode::getIt($expectedDemonKindCodeValue),
            $demonsTable->getDemonKindCode(DemonCode::getIt($demonCodeValue))
        );
    }

    public function provideDemonAndExpectedKind(): array
    {
        return [
            DemonCode::CRON => [DemonCode::CRON, DemonKindCode::BARE],
            DemonCode::DEMON_OF_MOVEMENT => [DemonCode::DEMON_OF_MOVEMENT, DemonKindCode::ANIMATING],
            DemonCode::WARDEN => [DemonCode::WARDEN, DemonKindCode::BARE],
            DemonCode::DEMON_OF_MUSIC => [DemonCode::DEMON_OF_MUSIC, DemonKindCode::ANIMATING],
            DemonCode::DEMON_DEFENDER => [DemonCode::DEMON_DEFENDER, DemonKindCode::ANIMATING],
            DemonCode::DEMON_GAMBLER => [DemonCode::DEMON_GAMBLER, DemonKindCode::BARE],
            DemonCode::DEMON_OF_TIRELESSNESS => [DemonCode::DEMON_OF_TIRELESSNESS, DemonKindCode::BARE],
            DemonCode::DEMON_OF_OMIT_VOMIT => [DemonCode::DEMON_OF_OMIT_VOMIT, DemonKindCode::BARE],
            DemonCode::DEMON_ATTACKER => [DemonCode::DEMON_ATTACKER, DemonKindCode::ANIMATING],
            DemonCode::DEMON_OF_VISION => [DemonCode::DEMON_OF_VISION, DemonKindCode::BARE],
            DemonCode::GOLEM => [DemonCode::GOLEM, DemonKindCode::ANIMATING],
            DemonCode::DEADY => [DemonCode::DEADY, DemonKindCode::ANIMATING],
            DemonCode::BERSERK => [DemonCode::BERSERK, DemonKindCode::ANIMATING],
            DemonCode::IMP => [DemonCode::IMP, DemonKindCode::ANIMATING],
            DemonCode::DEMON_OF_KNOWLEDGE => [DemonCode::DEMON_OF_KNOWLEDGE, DemonKindCode::ANIMATING],
            DemonCode::NAVIGATOR => [DemonCode::NAVIGATOR, DemonKindCode::ANIMATING],
            DemonCode::GUARDIAN => [DemonCode::GUARDIAN, DemonKindCode::ANIMATING],
            DemonCode::SPY => [DemonCode::SPY, DemonKindCode::ANIMATING],
        ];
    }

    /**
     * @test
     * @dataProvider provideDemonAndExpectedWill
     * @param string $demonCodeValue
     * @param int $expectedDemonWillValue
     */
    public function I_can_get_demon_will(string $demonCodeValue, int $expectedDemonWillValue)
    {
        $demonsTable = new DemonsTable(Tables::getIt());
        self::assertEquals(
            new DemonWill([$expectedDemonWillValue, 0], Tables::getIt()),
            $demonsTable->getDemonWill(DemonCode::getIt($demonCodeValue))
        );
    }

    public function provideDemonAndExpectedWill(): array
    {
        return [
            DemonCode::CRON => [DemonCode::CRON, 2],
            DemonCode::DEMON_OF_MOVEMENT => [DemonCode::DEMON_OF_MOVEMENT, 1],
            DemonCode::WARDEN => [DemonCode::WARDEN, 1],
            DemonCode::DEMON_OF_MUSIC => [DemonCode::DEMON_OF_MUSIC, 2],
            DemonCode::DEMON_DEFENDER => [DemonCode::DEMON_DEFENDER, 3],
            DemonCode::DEMON_GAMBLER => [DemonCode::DEMON_GAMBLER, 5],
            DemonCode::DEMON_OF_TIRELESSNESS => [DemonCode::DEMON_OF_TIRELESSNESS, 5],
            DemonCode::DEMON_OF_OMIT_VOMIT => [DemonCode::DEMON_OF_OMIT_VOMIT, 3],
            DemonCode::DEMON_ATTACKER => [DemonCode::DEMON_ATTACKER, 4],
            DemonCode::DEMON_OF_VISION => [DemonCode::DEMON_OF_VISION, 4],
            DemonCode::GOLEM => [DemonCode::GOLEM, 6],
            DemonCode::DEADY => [DemonCode::DEADY, 8],
            DemonCode::BERSERK => [DemonCode::BERSERK, 12],
            DemonCode::IMP => [DemonCode::IMP, 6],
            DemonCode::DEMON_OF_KNOWLEDGE => [DemonCode::DEMON_OF_KNOWLEDGE, 11],
            DemonCode::NAVIGATOR => [DemonCode::NAVIGATOR, 7],
            DemonCode::GUARDIAN => [DemonCode::GUARDIAN, 9],
            DemonCode::SPY => [DemonCode::SPY, 10],
        ];
    }

    /**
     * @test
     * @dataProvider provideDemonAndExpectedArea
     * @param string $demonCodeValue
     * @param int|null $expectedDemonAreaValue
     */
    public function I_can_get_demon_area(string $demonCodeValue, ?int $expectedDemonAreaValue)
    {
        $demonsTable = new DemonsTable(Tables::getIt());
        if ($expectedDemonAreaValue === null) {
            self::assertNull($demonsTable->getDemonArea(DemonCode::getIt($demonCodeValue)));
        } else {
            self::assertEquals(
                new DemonArea([$expectedDemonAreaValue, 1, 4], Tables::getIt()),
                $demonsTable->getDemonArea(DemonCode::getIt($demonCodeValue))
            );
        }
    }

    public function provideDemonAndExpectedArea(): array
    {
        return [
            DemonCode::CRON => [DemonCode::CRON, null],
            DemonCode::DEMON_OF_MOVEMENT => [DemonCode::DEMON_OF_MOVEMENT, null],
            DemonCode::WARDEN => [DemonCode::WARDEN, null],
            DemonCode::DEMON_OF_MUSIC => [DemonCode::DEMON_OF_MUSIC, null],
            DemonCode::DEMON_DEFENDER => [DemonCode::DEMON_DEFENDER, null],
            DemonCode::DEMON_GAMBLER => [DemonCode::DEMON_GAMBLER, null],
            DemonCode::DEMON_OF_TIRELESSNESS => [DemonCode::DEMON_OF_TIRELESSNESS, null],
            DemonCode::DEMON_OF_OMIT_VOMIT => [DemonCode::DEMON_OF_OMIT_VOMIT, null],
            DemonCode::DEMON_ATTACKER => [DemonCode::DEMON_ATTACKER, null],
            DemonCode::DEMON_OF_VISION => [DemonCode::DEMON_OF_VISION, null],
            DemonCode::GOLEM => [DemonCode::GOLEM, null],
            DemonCode::DEADY => [DemonCode::DEADY, null],
            DemonCode::BERSERK => [DemonCode::BERSERK, null],
            DemonCode::IMP => [DemonCode::IMP, null],
            DemonCode::DEMON_OF_KNOWLEDGE => [DemonCode::DEMON_OF_KNOWLEDGE, null],
            DemonCode::NAVIGATOR => [DemonCode::NAVIGATOR, 60],
            DemonCode::GUARDIAN => [DemonCode::GUARDIAN, null],
            DemonCode::SPY => [DemonCode::SPY, null],
        ];
    }
}
