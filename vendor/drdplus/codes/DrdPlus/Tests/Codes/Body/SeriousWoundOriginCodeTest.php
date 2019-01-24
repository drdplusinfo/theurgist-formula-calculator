<?php
namespace DrdPlus\Tests\Codes\Body;

use DrdPlus\Codes\Body\SeriousWoundOriginCode;
use Granam\String\StringTools;

class SeriousWoundOriginCodeTest extends WoundOriginCodeTest
{
    /**
     * @test
     * @dataProvider provideSeriousWoundOriginCode
     * @param string $seriousWoundOriginName
     */
    public function I_can_get_every_type_of_serious_wound_origin(string $seriousWoundOriginName): void
    {
        $getWoundOrigin = StringTools::assembleGetterForName($seriousWoundOriginName) . 'WoundOrigin';
        /** @var SeriousWoundOriginCode $seriousWoundOrigin */
        $seriousWoundOrigin = SeriousWoundOriginCode::$getWoundOrigin();

        $isWoundOrigin = StringTools::assembleGetterForName($seriousWoundOriginName, 'is') . 'WoundOrigin';
        self::assertTrue($seriousWoundOrigin->$isWoundOrigin());
        self::assertFalse($seriousWoundOrigin->isOrdinaryWoundOrigin());
        self::assertTrue($seriousWoundOrigin->isSeriousWoundOrigin());
        self::assertSame(\strpos($seriousWoundOriginName, 'mechanical') !== false, $seriousWoundOrigin->isMechanical());

        $otherOrigins = \array_diff(SeriousWoundOriginCode::getPossibleValues(), [$seriousWoundOriginName]);
        foreach ($otherOrigins as $otherOrigin) {
            $isOtherWoundOrigin = StringTools::assembleGetterForName($otherOrigin, 'is') . 'WoundOrigin';
            self::assertFalse($seriousWoundOrigin->$isOtherWoundOrigin());
        }
    }

    public function provideSeriousWoundOriginCode(): array
    {
        return \array_map(
            function ($code) {
                return [$code];
            },
            SeriousWoundOriginCode::getPossibleValues()
        );
    }

    /**
     * @test
     * @expectedException \DrdPlus\Codes\Partials\Exceptions\UnknownValueForCode
     * @expectedExceptionMessageRegExp ~Bathroom slipping~
     */
    public function I_can_not_create_custom_origin(): void
    {
        SeriousWoundOriginCode::getEnum('Bathroom slipping');
    }

    /**
     * @test
     */
    public function I_can_get_all_codes_at_once_or_by_same_named_constant(): void
    {
        self::assertSame(
            [
                'mechanical_stab',
                'mechanical_cut',
                'mechanical_crush',
                'elemental',
                'psychical',
            ],
            SeriousWoundOriginCode::getPossibleValues()
        );
    }

    /**
     * @test
     * @dataProvider provideOriginAndAnswersToType
     * @param string $origin
     * @param bool $isPsychical
     * @param bool $isElemental
     * @param bool $isMechanical
     */
    public function I_can_ask_it_if_is_mechanical(string $origin, bool $isPsychical, bool $isElemental, bool $isMechanical): void
    {
        $seriousWoundOriginCode = SeriousWoundOriginCode::getIt($origin);
        self::assertSame($origin, $seriousWoundOriginCode->getValue());
        self::assertSame($isPsychical, $seriousWoundOriginCode->isPsychical());
        self::assertSame($isElemental, $seriousWoundOriginCode->isElemental());
        self::assertSame($isMechanical, $seriousWoundOriginCode->isMechanical());
    }

    public function provideOriginAndAnswersToType(): array
    {
        return [
            ['psychical', true, false, false],
            ['elemental', false, true, false],
            ['mechanical_stab', false, false, true],
            ['mechanical_cut', false, false, true],
            ['mechanical_crush', false, false, true],
        ];
    }
}