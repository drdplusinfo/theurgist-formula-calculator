<?php declare(strict_types=1); 

namespace DrdPlus\Codes;

use DrdPlus\Codes\Partials\TranslatableCode;

/**
 * @method static ItemHoldingCode getIt($codeValue)
 * @method static ItemHoldingCode findIt($codeValue)
 */
class ItemHoldingCode extends TranslatableCode
{
    public const TWO_HANDS = 'two_hands';
    public const MAIN_HAND = 'main_hand';
    public const OFFHAND = 'offhand';

    /**
     * @return array|string[]
     */
    public static function getPossibleValues(): array
    {
        return [
            self::TWO_HANDS,
            self::MAIN_HAND,
            self::OFFHAND,
        ];
    }

    /**
     * @return bool
     */
    public function holdsByTwoHands(): bool
    {
        return $this->getValue() === self::TWO_HANDS;
    }

    /**
     * @return bool
     */
    public function holdsByMainHand(): bool
    {
        return $this->getValue() === self::MAIN_HAND;
    }

    /**
     * @return bool
     */
    public function holdsByOffhand(): bool
    {
        return $this->getValue() === self::OFFHAND;
    }

    /**
     * @return bool
     */
    public function holdsByOneHand(): bool
    {
        return \in_array($this->getValue(), [self::OFFHAND, self::MAIN_HAND], true);
    }

    /**
     * @return ItemHoldingCode
     * @throws \DrdPlus\Codes\Exceptions\ThereIsNoOppositeForTwoHandsHolding
     */
    public function getOpposite(): ItemHoldingCode
    {
        if ($this->holdsByTwoHands()) {
            throw new Exceptions\ThereIsNoOppositeForTwoHandsHolding(
                'Can not make opposite to ' . $this
            );
        }
        if ($this->holdsByMainHand()) {
            return self::getIt(self::OFFHAND);
        }

        return self::getIt(self::MAIN_HAND);
    }

    protected function fetchTranslations(): array
    {
        return [
            'en' => [
                self::TWO_HANDS => [self::$ONE => 'two hands'],
                self::MAIN_HAND => [self::$ONE => 'main hand'],
                self::OFFHAND => [self::$ONE => 'offhand'],
            ],
            'cs' => [
                self::TWO_HANDS => [self::$ONE => 'obouručně'],
                self::MAIN_HAND => [self::$ONE => 'v dominantní ruce'],
                self::OFFHAND => [self::$ONE => 'v druhé ruce'],
            ],
        ];
    }

}