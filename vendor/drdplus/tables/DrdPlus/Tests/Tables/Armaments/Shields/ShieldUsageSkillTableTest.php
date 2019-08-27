<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Armaments\Shields;

use DrdPlus\Tables\Armaments\Shields\ShieldUsageSkillTable;
use DrdPlus\Tests\Tables\Armaments\Partials\AbstractArmamentSkillTableTest;

class ShieldUsageSkillTableTest extends AbstractArmamentSkillTableTest
{
    /**
     * @test
     */
    public function I_can_not_use_negative_rank()
    {
        $this->expectException(\DrdPlus\Tables\Armaments\Partials\Exceptions\UnexpectedSkillRank::class);
        (new ShieldUsageSkillTable())->getCoverMalusForSkillRank(-2);
    }

    /**
     * @test
     */
    public function I_can_not_use_higher_rank_than_three()
    {
        $this->expectException(\DrdPlus\Tables\Armaments\Partials\Exceptions\UnexpectedSkillRank::class);
        (new ShieldUsageSkillTable())->getRestrictionBonusForSkillRank(8);
    }

    /**
     * @test
     */
    public function I_can_get_header()
    {
        self::assertSame([['skill_rank', 'restriction_bonus', 'cover_malus']], (new ShieldUsageSkillTable())->getHeader());
    }

    /**
     * @test
     */
    public function I_can_get_restriction_bonus_for_skill_rank()
    {
        self::assertSame(1, (new ShieldUsageSkillTable())->getRestrictionBonusForSkillRank(1));
    }

    /**
     * @test
     */
    public function I_can_get_cover_for_skill_rank()
    {
        self::assertSame(0, (new ShieldUsageSkillTable())->getCoverMalusForSkillRank(3));
    }
}