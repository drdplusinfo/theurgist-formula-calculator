<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Environments;

use DrdPlus\Tables\Environments\TerrainDifficultyPercents;
use DrdPlus\Tests\Tables\Partials\PercentsTest;

class TerrainDifficultyPercentsTest extends PercentsTest
{

    public function I_can_create_more_than_hundred_of_percents()
    {
        // intentionally empty, because I can not
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Environments\Exceptions\UnexpectedDifficultyPercents
     * @expectedExceptionMessageRegExp ~101~
     */
    public function I_can_not_create_more_than_hundred_of_percents()
    {
        try {
            new TerrainDifficultyPercents(100);
        } catch (\Exception $exception) {
            self::fail('No exception expected so far: ' . $exception->getTraceAsString());
        }
        new TerrainDifficultyPercents(101);
    }

}
