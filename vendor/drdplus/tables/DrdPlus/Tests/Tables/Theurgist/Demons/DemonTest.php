<?php
namespace DrdPlus\Tables\Theurgist\Demons;

use DrdPlus\BaseProperties\Will;
use DrdPlus\Codes\Theurgist\DemonBodyCode;
use DrdPlus\Codes\Theurgist\DemonCode;
use DrdPlus\Codes\Theurgist\DemonKindCode;
use DrdPlus\Codes\Theurgist\DemonTraitCode;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonAgility;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonArmor;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonCapacity;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonEndurance;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonKnack;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonStrength;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Difficulty;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Evocation;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Invisibility;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Quality;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Realm;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\RealmsAffection;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellDuration;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellRadius;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellSpeed;
use PHPUnit\Framework\TestCase;

class DemonTest extends TestCase
{
    /**
     * @test
     */
    public function I_can_create_it_with_every_parameter()
    {
        $demon = new Demon(
            $demonCode = DemonCode::getIt(DemonCode::DEMON_OF_MOVEMENT),
            $realm = new Realm(123),
            $evocation = new Evocation([10, 0], Tables::getIt()),
            $demonBodyCode = DemonBodyCode::getIt(DemonBodyCode::PEBBLE),
            $demonKindCode = DemonKindCode::getIt(DemonKindCode::ANIMATING),
            $realmsAffection = new RealmsAffection([-1]),
            $will = Will::getIt(123),
            $spellDuration = new SpellDuration([1, 0], Tables::getIt()),
            $difficulty = new Difficulty([1, 2, 0]),
            $demonTraits = [new DemonTrait(DemonTraitCode::getIt(DemonTraitCode::CHEAP_UNLIMITED_CAPACITY), Tables::getIt())],
            $demonCapacity = new DemonCapacity([1, 1], Tables::getIt()),
            $demonEndurance = new DemonEndurance([1, 0], Tables::getIt()),
            $spellSpeed = new SpellSpeed([1, 0], Tables::getIt()),
            $quality = new Quality([1, 0], Tables::getIt()),
            $spellRadius = new SpellRadius([1, 0], Tables::getIt()),
            $invisibility = new Invisibility([1, 0], Tables::getIt()),
            $demonStrength = new DemonStrength([1, 0], Tables::getIt()),
            $demonAgility = new DemonAgility([1, 0], Tables::getIt()),
            $demonKnack = new DemonKnack([1, 0], Tables::getIt()),
            $demonArmor = new DemonArmor([1, 0], Tables::getIt())
        );
        self::assertSame($demonCode, $demon->getDemonCode());
        self::assertSame($realm, $demon->getRealm());
        self::assertSame($evocation, $demon->getEvocation());
        self::assertSame($demonBodyCode, $demon->getDemonBodyCode());
        self::assertSame($demonKindCode, $demon->getDemonKindCode());
        self::assertSame($realmsAffection, $demon->getRealmsAffection());
        self::assertSame($will, $demon->getWill());
        self::assertSame($spellDuration, $demon->getSpellDuration());
        self::assertSame($difficulty, $demon->getDifficulty());
        self::assertSame($demonTraits, $demon->getDemonTraits());
        self::assertSame($demonCapacity, $demon->getDemonCapacity());
        self::assertSame($demonEndurance, $demon->getDemonEndurance());
        self::assertSame($spellSpeed, $demon->getSpellSpeed());
        self::assertSame($quality, $demon->getQuality());
        self::assertSame($spellRadius, $demon->getSpellRadius());
        self::assertSame($invisibility, $demon->getInvisibility());
        self::assertSame($demonStrength, $demon->getDemonStrength());
        self::assertSame($demonAgility, $demon->getDemonAgility());
        self::assertSame($demonKnack, $demon->getDemonKnack());
        self::assertSame($demonArmor, $demon->getDemonArmor());
    }

    /**
     * @test
     */
    public function I_can_create_it_with_mandatory_parameters_only()
    {
        $demon = new Demon(
            $demonCode = DemonCode::getIt(DemonCode::DEMON_OF_MOVEMENT),
            $realm = new Realm(123),
            $evocation = new Evocation([10, 0], Tables::getIt()),
            $demonBodyCode = DemonBodyCode::getIt(DemonBodyCode::PEBBLE),
            $demonKindCode = DemonKindCode::getIt(DemonKindCode::ANIMATING),
            $realmsAffection = new RealmsAffection([-1]),
            $will = Will::getIt(123)
        );
        self::assertSame($demonCode, $demon->getDemonCode());
        self::assertSame($realm, $demon->getRealm());
        self::assertSame($evocation, $demon->getEvocation());
        self::assertSame($demonBodyCode, $demon->getDemonBodyCode());
        self::assertSame($demonKindCode, $demon->getDemonKindCode());
        self::assertSame($realmsAffection, $demon->getRealmsAffection());
        self::assertSame($will, $demon->getWill());
        self::assertNull($demon->getSpellDuration());
        self::assertNull($demon->getDifficulty());
        self::assertSame([], $demon->getDemonTraits());
        self::assertNull($demon->getDemonCapacity());
        self::assertNull($demon->getDemonEndurance());
        self::assertNull($demon->getSpellSpeed());
        self::assertNull($demon->getQuality());
        self::assertNull($demon->getSpellRadius());
        self::assertNull($demon->getInvisibility());
        self::assertNull($demon->getDemonStrength());
        self::assertNull($demon->getDemonAgility());
        self::assertNull($demon->getDemonKnack());
        self::assertNull($demon->getDemonArmor());
    }
}
