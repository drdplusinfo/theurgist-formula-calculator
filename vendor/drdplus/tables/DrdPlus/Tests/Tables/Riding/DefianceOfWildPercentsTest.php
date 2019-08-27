<?php declare(strict_types = 1);

declare(strict_types=1);

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
     */
    public function I_can_not_create_more_than_hundred_of_percents()
    {
        $this->expectException(\DrdPlus\Tables\Riding\Exceptions\UnexpectedDefianceOfWildPercents::class);
        $this->expectExceptionMessageRegExp('~\s101~');
        new DefianceOfWildPercents(101);
    }

}