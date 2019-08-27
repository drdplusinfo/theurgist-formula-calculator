<?php declare(strict_types=1);

namespace DrdPlus\Tests\Codes\Skills;

use DrdPlus\Codes\Skills\SkillTypeCode;

class SkillTypeCodeTest extends SkillCodeTest
{
    /**
     * @test
     */
    public function I_can_get_all_codes_at_once_or_by_same_named_constant()
    {
        self::assertEquals(
            ['physical', 'psychical', 'combined'],
            SkillTypeCode::getPossibleValues()
        );
    }
}