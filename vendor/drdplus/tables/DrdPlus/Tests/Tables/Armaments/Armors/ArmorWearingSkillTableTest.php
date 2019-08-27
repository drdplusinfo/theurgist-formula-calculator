<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Armaments\Armors;

use DrdPlus\Tables\Armaments\Armors\ArmorWearingSkillTable;
use DrdPlus\Tests\Tables\Armaments\Partials\AbstractArmamentSkillTableTest;

class ArmorWearingSkillTableTest extends AbstractArmamentSkillTableTest
{
    /**
     * @test
     */
    public function I_can_not_use_negative_rank()
    {
        $this->expectException(\DrdPlus\Tables\Armaments\Partials\Exceptions\UnexpectedSkillRank::class);
        (new ArmorWearingSkillTable())->getRestrictionBonusForSkillRank(-1);
    }

    /**
     * @test
     */
    public function I_can_not_use_higher_rank_than_three()
    {
        $this->expectException(\DrdPlus\Tables\Armaments\Partials\Exceptions\UnexpectedSkillRank::class);
        (new ArmorWearingSkillTable())->getRestrictionBonusForSkillRank(4);
    }

    /**
     * @test
     */
    public function I_can_get_header()
    {
        self::assertSame([['skill_rank', 'restriction_bonus']], (new ArmorWearingSkillTable())->getHeader());
    }

    /**
     * @test
     */
    public function I_can_get_bonus_for_skill_rank()
    {
        self::assertSame(2, (new ArmorWearingSkillTable())->getRestrictionBonusForSkillRank(2));
    }
}