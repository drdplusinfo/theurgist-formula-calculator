<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Armaments\Armors\Exceptions;

use DrdPlus\Tables\Armaments\Armors\Exceptions\DifferentBodyArmorIsUnderSameName;
use Granam\Tests\Tools\TestWithMockery;

class DifferentBodyArmorIsUnderSameNameTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_am_descendant_of_different_armor_part_exception()
    {
        $this->expectException(\DrdPlus\Tables\Armaments\Armors\Exceptions\DifferentArmorPartIsUnderSameName::class);
        $this->expectExceptionMessage('foo');
        throw new DifferentBodyArmorIsUnderSameName('foo');
    }
}