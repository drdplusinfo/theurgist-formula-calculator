<?php declare(strict_types = 1);

namespace DrdPlus\Tests\Tables\Theurgist\Spells\SpellParameters;

use DrdPlus\Codes\Theurgist\AffectionPeriodCode;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\RealmsAffection;
use Granam\Tests\Tools\TestWithMockery;

class RealmsAffectionTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_create_it_without_period()
    {
        $affection = new RealmsAffection(['-213']);
        self::assertSame(-213, $affection->getValue());
        self::assertSame('-213 daily', (string)$affection);
        self::assertSame(AffectionPeriodCode::getIt(AffectionPeriodCode::DAILY), $affection->getAffectionPeriodCode());
    }

    /**
     * @test
     */
    public function I_can_create_it_with_explicit_period()
    {
        $affection = new RealmsAffection(['-357', AffectionPeriodCode::MONTHLY]);
        self::assertSame(-357, $affection->getValue());
        self::assertSame('-357 monthly', (string)$affection);
        self::assertSame(AffectionPeriodCode::getIt(AffectionPeriodCode::MONTHLY), $affection->getAffectionPeriodCode());
    }

    /**
     * @test
     */
    public function I_can_create_zero_affection()
    {
        $affection = new RealmsAffection(['0']);
        self::assertSame(0, $affection->getValue());
        self::assertSame('0', (string)$affection);
        self::assertSame(AffectionPeriodCode::getIt(AffectionPeriodCode::DAILY), $affection->getAffectionPeriodCode());
    }

    /**
     * @test
     */
    public function I_can_not_create_positive_affection()
    {
        $this->expectException(\DrdPlus\Tables\Theurgist\Spells\SpellParameters\Exceptions\InvalidFormatForNegativeCastingParameter::class);
        $this->expectExceptionMessageRegExp('~1~');
        new RealmsAffection(['1']);
    }

}