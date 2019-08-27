<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Armaments\Partials;

use DrdPlus\Tables\Armaments\Partials\AbstractStrengthSanctionsTable;
use DrdPlus\Tests\Tables\TableTest;

abstract class AbstractStrengthSanctionsTableTest extends TableTest
{
    /**
     * @test
     */
    abstract public function I_can_get_sanctions_for_missing_strength();

    /**
     * @test
     */
    public function I_can_easily_find_out_if_can_use_armament()
    {
        $sutClass = self::getSutClass();
        /** @var AbstractStrengthSanctionsTable $sut */
        $sut = new $sutClass();
        self::assertTrue($sut->canUseIt(-999));
        self::assertFalse($sut->canUseIt(999));
    }
}