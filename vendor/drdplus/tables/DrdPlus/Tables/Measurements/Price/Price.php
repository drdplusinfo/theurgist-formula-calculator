<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tables\Measurements\Price;

use DrdPlus\Tables\Measurements\Exceptions\UnknownUnit;
use DrdPlus\Tables\Measurements\Partials\AbstractMeasurement;

class Price extends AbstractMeasurement
{
    public const COPPER_COIN = 'copper_coin';
    public const SILVER_COIN = 'silver_coin';
    public const GOLD_COIN = 'gold_coin';

    /**
     * @param float $value
     * @param string $unit
     * @throws \DrdPlus\Tables\Measurements\Exceptions\UnknownUnit
     * @throws \Granam\Float\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Float\Tools\Exceptions\ValueLostOnCast
     * @throws \Granam\Scalar\Tools\Exceptions\WrongParameterType
     */
    public function __construct($value, $unit)
    {
        parent::__construct($value, $unit);
    }

    /**
     * @return array|string[]
     */
    public function getPossibleUnits(): array
    {
        return [self::COPPER_COIN, self::SILVER_COIN, self::GOLD_COIN];
    }

    /**
     * @return int
     * @throws \DrdPlus\Tables\Measurements\Exceptions\UnknownUnit
     */
    public function getCopperCoins()
    {
        switch ($this->getUnit()) {
            case (self::COPPER_COIN) :
                return $this->getValue();
            case (self::SILVER_COIN) :
                return $this->getValue() * 10;
            case (self::GOLD_COIN) :
                return $this->getValue() * 100;
            default :
                throw new UnknownUnit('Unknown unit ' . $this->getUnit());
        }
    }

    /**
     * @return float
     * @throws \DrdPlus\Tables\Measurements\Exceptions\UnknownUnit
     */
    public function getSilverCoins()
    {
        switch ($this->getUnit()) {
            case (self::COPPER_COIN) :
                return $this->getValue() / 10;
            case (self::SILVER_COIN) :
                return $this->getValue();
            case (self::GOLD_COIN) :
                return $this->getValue() * 10;
            default :
                throw new UnknownUnit('Unknown unit ' . $this->getUnit());
        }
    }

    /**
     * @return float
     * @throws \DrdPlus\Tables\Measurements\Exceptions\UnknownUnit
     */
    public function getGoldCoins()
    {
        switch ($this->getUnit()) {
            case (self::COPPER_COIN) :
                return $this->getValue() / 100;
            case (self::SILVER_COIN) :
                return $this->getValue() / 10;
            case (self::GOLD_COIN) :
                return $this->getValue();
            default :
                throw new UnknownUnit('Unknown unit ' . $this->getUnit());
        }
    }

}