<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Riding;

use DrdPlus\Tables\Riding\DefianceOfWildPercents;
use DrdPlus\Tests\Tables\Partials\PercentsTest;

class DefianceOfWildPercentsTest extends PercentsTest
{
    public function I_can_create_more_than_hundred_of_percents()
    {
        // intentionally empty, because I can NOT create more than a hundred of percents
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Riding\Exceptions\UnexpectedDefianceOfWildPercents
     * @expectedExceptionMessageRegExp ~\s101~
     */
    public function I_can_not_create_more_than_hundred_of_percents()
    {
        new DefianceOfWildPercents(101);
    }

}