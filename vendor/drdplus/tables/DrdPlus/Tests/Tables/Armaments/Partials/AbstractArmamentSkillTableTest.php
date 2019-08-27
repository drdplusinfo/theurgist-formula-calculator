<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Armaments\Partials;

use DrdPlus\Tests\Tables\TableTest;

abstract class AbstractArmamentSkillTableTest extends TableTest
{
    /**
     * @test
     */
    abstract public function I_can_not_use_negative_rank();

    /**
     * @test
     */
    abstract public function I_can_not_use_higher_rank_than_three();
}