<?php declare(strict_types = 1);

declare(strict_types=1);

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
     */
    public function I_can_not_create_more_than_hundred_of_percents()
    {
        $this->expectException(\DrdPlus\Tables\Environments\Exceptions\UnexpectedDifficultyPercents::class);
        $this->expectExceptionMessageRegExp('~101~');
        try {
            new TerrainDifficultyPercents(100);
        } catch (\Exception $exception) {
            self::fail('No exception expected so far: ' . $exception->getTraceAsString());
        }
        new TerrainDifficultyPercents(101);
    }

}
