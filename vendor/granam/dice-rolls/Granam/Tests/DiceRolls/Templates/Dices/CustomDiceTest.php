<?php
declare(strict_types=1);

namespace Granam\Tests\DiceRolls\Templates\Dices;

use Granam\DiceRolls\Templates\Dices\CustomDice;
use Granam\Integer\IntegerInterface;
use Granam\Tests\Tools\TestWithMockery;

class CustomDiceTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_create_it()
    {
        /** @var IntegerInterface|\Mockery\MockInterface $minimum */
        $minimum = $this->mockery(IntegerInterface::class);
        $minimum->shouldReceive('getValue')
            ->andReturn($minimumValue = 1);
        /** @var IntegerInterface|\Mockery\MockInterface $maximum */
        $maximum = $this->mockery(IntegerInterface::class);
        $maximum->shouldReceive('getValue')
            ->andReturn($maximumValue = 2);

        $customDice = new CustomDice($minimum, $maximum);
        self::assertSame($minimum, $customDice->getMinimum());
        self::assertSame($maximum, $customDice->getMaximum());
    }

    /**
     * @test
     */
    public function I_can_use_same_minimum_as_maximum()
    {
        /** @var IntegerInterface|\Mockery\MockInterface $minimum */
        $minimum = $this->mockery(IntegerInterface::class);
        $minimum->shouldReceive('getValue')
            ->andReturn($minimumValue = 12345);
        /** @var IntegerInterface|\Mockery\MockInterface $maximum */
        $maximum = $this->mockery(IntegerInterface::class);
        $maximum->shouldReceive('getValue')
            ->andReturn($minimumValue);

        $customDice = new CustomDice($minimum, $maximum);
        self::assertNotSame($customDice->getMinimum(), $customDice->getMaximum());
        self::assertSame($customDice->getMinimum()->getValue(), $customDice->getMaximum()->getValue());
    }

    /**
     * @test
     * @expectedException \Granam\DiceRolls\Templates\Dices\Exceptions\InvalidDiceRange
     */
    public function I_can_not_use_minimum_greater_than_maximum()
    {
        /** @var IntegerInterface|\Mockery\MockInterface $minimum */
        $minimum = $this->mockery(IntegerInterface::class);
        $minimum->shouldReceive('getValue')
            ->andReturn($minimumValue = 12345);
        /** @var IntegerInterface|\Mockery\MockInterface $maximum */
        $maximum = $this->mockery(IntegerInterface::class);
        $maximum->shouldReceive('getValue')
            ->andReturn($maximumValue = $minimumValue - 1);
        self::assertLessThan($minimumValue, $maximumValue);

        new CustomDice($minimum, $maximum);
    }

    /**
     * @test
     * @expectedException \Granam\DiceRolls\Templates\Dices\Exceptions\InvalidDiceRange
     */
    public function I_can_not_use_zero_for_minimum()
    {
        /** @var IntegerInterface|\Mockery\MockInterface $minimum */
        $minimum = $this->mockery(IntegerInterface::class);
        $minimum->shouldReceive('getValue')
            ->andReturn(0);
        /** @var IntegerInterface|\Mockery\MockInterface $maximum */
        $maximum = $this->mockery(IntegerInterface::class);
        $maximum->shouldReceive('getValue')
            ->andReturn(12345);

        new CustomDice($minimum, $maximum);
    }

    /**
     * @test
     * @expectedException \Granam\DiceRolls\Templates\Dices\Exceptions\InvalidDiceRange
     */
    public function I_can_not_use_negative_number_for_minimum()
    {
        /** @var IntegerInterface|\Mockery\MockInterface $minimum */
        $minimum = $this->mockery(IntegerInterface::class);
        $minimum->shouldReceive('getValue')
            ->andReturn(-1);
        /** @var IntegerInterface|\Mockery\MockInterface $maximum */
        $maximum = $this->mockery(IntegerInterface::class);
        $maximum->shouldReceive('getValue')
            ->andReturn(12345);

        new CustomDice($minimum, $maximum);
    }

    /**
     * @test
     * @expectedException \Granam\DiceRolls\Templates\Dices\Exceptions\InvalidDiceRange
     */
    public function I_can_not_use_zero_limits()
    {
        /** @var IntegerInterface|\Mockery\MockInterface $minimum */
        $minimum = $this->mockery(IntegerInterface::class);
        $minimum->shouldReceive('getValue')
            ->andReturn(0);
        /** @var IntegerInterface|\Mockery\MockInterface $maximum */
        $maximum = $this->mockery(IntegerInterface::class);
        $maximum->shouldReceive('getValue')
            ->andReturn(0);

        new CustomDice($minimum, $maximum);
    }

    /**
     * @test
     * @expectedException \Granam\DiceRolls\Templates\Dices\Exceptions\InvalidDiceRange
     */
    public function I_can_not_use_negatives_for_limits()
    {
        /** @var IntegerInterface|\Mockery\MockInterface $minimum */
        $minimum = $this->mockery(IntegerInterface::class);
        $minimum->shouldReceive('getValue')
            ->andReturn(-1);
        /** @var IntegerInterface|\Mockery\MockInterface $maximum */
        $maximum = $this->mockery(IntegerInterface::class);
        $maximum->shouldReceive('getValue')
            ->andReturn(-1);

        new CustomDice($minimum, $maximum);
    }

}