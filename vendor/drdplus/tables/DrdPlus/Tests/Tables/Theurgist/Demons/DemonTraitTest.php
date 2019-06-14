<?php declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist\Demons;

use DrdPlus\Codes\Theurgist\DemonTraitCode;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Demons\DemonTrait;
use PHPUnit\Framework\TestCase;

class DemonTraitTest extends TestCase
{
    /**
     * @test
     */
    public function I_can_use_it()
    {
        $demonTraitCode = DemonTraitCode::getIt(DemonTraitCode::CHEAP_UNLIMITED_CAPACITY);
        $demonTrait = new DemonTrait($demonTraitCode, Tables::getIt());
        self::assertSame($demonTraitCode, $demonTrait->getDemonTraitCode());
        self::assertSame($demonTraitCode->getValue(), (string)$demonTrait);
        self::assertEquals(Tables::getIt()->getDemonTraitsTable()->getRealm($demonTraitCode), $demonTrait->getRequiredRealm());
        self::assertEquals(Tables::getIt()->getDemonTraitsTable()->getRealmsAffection($demonTraitCode), $demonTrait->getRealmsAffection());
    }
}

