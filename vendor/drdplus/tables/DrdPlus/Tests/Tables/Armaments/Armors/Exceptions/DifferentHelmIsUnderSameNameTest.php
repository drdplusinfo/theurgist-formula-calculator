<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Armaments\Armors\Exceptions;

use DrdPlus\Tables\Armaments\Armors\Exceptions\DifferentHelmIsUnderSameName;
use Granam\Tests\Tools\TestWithMockery;

class DifferentHelmIsUnderSameNameTest extends TestWithMockery
{
    /**
     * @test
     * @expectedException \DrdPlus\Tables\Armaments\Armors\Exceptions\DifferentArmorPartIsUnderSameName
     * @expectedExceptionMessage foo
     */
    public function I_am_descendant_of_different_armor_part_exception()
    {
        throw new DifferentHelmIsUnderSameName('foo');
    }
}