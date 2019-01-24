<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Armaments\Armors;

use DrdPlus\Tables\Armaments\Armors\ArmorWearingSkillTable;
use DrdPlus\Tests\Tables\Armaments\Partials\AbstractArmamentSkillTableTest;

class ArmorWearingSkillTableTest extends AbstractArmamentSkillTableTest
{
    /**
     * @test
     * @expectedException \DrdPlus\Tables\Armaments\Partials\Exceptions\UnexpectedSkillRank
     */
    public function I_can_not_use_negative_rank()
    {
        (new ArmorWearingSkillTable())->getRestrictionBonusForSkillRank(-1);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Armaments\Partials\Exceptions\UnexpectedSkillRank
     */
    public function I_can_not_use_higher_rank_than_three()
    {
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