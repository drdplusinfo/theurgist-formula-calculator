<?php
namespace DrdPlus\Tests\Codes;

use DrdPlus\Codes\ItemHoldingCode;
use DrdPlus\Tests\Codes\Partials\TranslatableCodeTest;

class ItemHoldingCodeTest extends TranslatableCodeTest
{
    /**
     * @test
     * @dataProvider provideHoldingExpectation
     * @param string $holdingValue
     * @param bool $holdsByTwoHands
     * @param bool $holdsByMainHand
     * @param bool $holdsByOffhand
     */
    public function I_can_ask_it_which_holding_easily($holdingValue, $holdsByTwoHands, $holdsByMainHand, $holdsByOffhand)
    {
        $itemHolding = ItemHoldingCode::getIt($holdingValue);
        self::assertSame($itemHolding->holdsByTwoHands(), $holdsByTwoHands);
        self::assertSame($itemHolding->holdsByMainHand(), $holdsByMainHand);
        self::assertSame($itemHolding->holdsByOffhand(), $holdsByOffhand);
        self::assertSame($itemHolding->holdsByOneHand(), !$holdsByTwoHands);
    }

    public function provideHoldingExpectation(): array
    {
        return [
            [ItemHoldingCode::TWO_HANDS, true, false, false],
            [ItemHoldingCode::MAIN_HAND, false, true, false],
            [ItemHoldingCode::OFFHAND, false, false, true],
        ];
    }

    /**
     * @test
     */
    public function I_can_get_opposite_holding()
    {
        $mainHand = ItemHoldingCode::getIt(ItemHoldingCode::MAIN_HAND);
        self::assertSame(ItemHoldingCode::MAIN_HAND, $mainHand->getValue());
        $opposite = $mainHand->getOpposite();
        self::assertSame(ItemHoldingCode::OFFHAND, $opposite->getValue());
        $oppositeOpposite = $opposite->getOpposite();
        self::assertSame($mainHand, $oppositeOpposite);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Codes\Exceptions\ThereIsNoOppositeForTwoHandsHolding
     * @expectedExceptionMessageRegExp ~two_hands~
     */
    public function I_can_not_get_opposite_holding_for_two_hands()
    {
        ItemHoldingCode::getIt(ItemHoldingCode::TWO_HANDS)->getOpposite();
    }
}