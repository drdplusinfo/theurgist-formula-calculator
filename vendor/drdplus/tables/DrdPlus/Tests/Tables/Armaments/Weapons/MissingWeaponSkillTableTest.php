<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Armaments\Weapons;

use DrdPlus\Tables\Armaments\Weapons\MissingWeaponSkillTable;
use DrdPlus\Tests\Tables\Armaments\Partials\AbstractArmamentSkillTableTest;

class MissingWeaponSkillTableTest extends AbstractArmamentSkillTableTest
{
    /**
     * @test
     * @expectedException \DrdPlus\Tables\Armaments\Partials\Exceptions\UnexpectedSkillRank
     */
    public function I_can_not_use_negative_rank()
    {
        (new MissingWeaponSkillTable())->getAttackNumberMalusForSkillRank(-1);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Armaments\Partials\Exceptions\UnexpectedSkillRank
     */
    public function I_can_not_use_higher_rank_than_three()
    {
        (new MissingWeaponSkillTable())->getBaseOfWoundsMalusForSkillRank(4);
    }

    /**
     * @test
     */
    public function I_can_get_header()
    {
        self::assertSame(
            [['skill_rank', 'fight_number', 'attack_number', 'cover', 'base_of_wounds']],
            (new MissingWeaponSkillTable())->getHeader()
        );
    }

    /**
     * @test
     */
    public function I_can_get_all_values()
    {
        self::assertSame(
            [
                '0' => [
                    'skill_rank' => 0,
                    'fight_number' => -3,
                    'attack_number' => -3,
                    'cover' => -2,
                    'base_of_wounds' => -1,
                ],
                '1' => [
                    'skill_rank' => 1,
                    'fight_number' => -2,
                    'attack_number' => -2,
                    'cover' => -1,
                    'base_of_wounds' => -1,
                ],
                '2' => [
                    'skill_rank' => 2,
                    'fight_number' => -1,
                    'attack_number' => -1,
                    'cover' => -1,
                    'base_of_wounds' => 0,
                ],
                '3' => [
                    'skill_rank' => 3,
                    'fight_number' => 0,
                    'attack_number' => 0,
                    'cover' => 0,
                    'base_of_wounds' => 0,
                ]
            ],
            (new MissingWeaponSkillTable())->getIndexedValues()
        );
    }

    /**
     * @test
     */
    public function I_can_get_maluses_for_missing_weapon_skill()
    {
        self::assertSame(
            [
                'skill_rank' => 2,
                'fight_number' => -1,
                'attack_number' => -1,
                'cover' => -1,
                'base_of_wounds' => 0
            ],
            (new MissingWeaponSkillTable())->getMalusesForWeaponSkill(2)
        );
    }

    /**
     * @test
     */
    public function I_can_get_malus_to_fight_number_for_missing_weapon_skill()
    {
        self::assertSame(
            -3,
            (new MissingWeaponSkillTable())->getFightNumberMalusForSkillRank(0)
        );
    }

    /**
     * @test
     */
    public function I_can_get_malus_to_attack_number_for_missing_weapon_skill()
    {
        self::assertSame(
            -2,
            (new MissingWeaponSkillTable())->getAttackNumberMalusForSkillRank(1)
        );
    }

    /**
     * @test
     */
    public function I_can_get_malus_to_cover_for_missing_weapon_skill()
    {
        self::assertSame(
            0,
            (new MissingWeaponSkillTable())->getCoverMalusForSkillRank(3)
        );
    }

    /**
     * @test
     */
    public function I_can_get_malus_to_base_of_wounds_for_missing_weapon_skill()
    {
        self::assertSame(
            0,
            (new MissingWeaponSkillTable())->getBaseOfWoundsMalusForSkillRank(2)
        );
    }

}