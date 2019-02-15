<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Body\Resting;

use DrdPlus\Tables\Body\Resting\RestingSituationPercents;
use DrdPlus\Tests\Tables\Partials\PercentsTest;

class RestingSituationPercentsTest extends PercentsTest
{
    /**
     * @test
     */
    public function I_can_create_more_than_hundred_of_percents()
    {
        $restingSituationPercents = new RestingSituationPercents(101);
        self::assertSame(101, $restingSituationPercents->getValue());
    }

    public function I_can_not_create_more_than_hundred_of_percents()
    {
        // intentionally empty because I can
    }

}