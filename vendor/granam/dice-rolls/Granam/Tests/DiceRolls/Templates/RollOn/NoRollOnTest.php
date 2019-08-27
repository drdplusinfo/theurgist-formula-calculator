<?php declare(strict_types=1);

namespace Granam\Tests\DiceRolls\Templates\RollOn;

use Granam\DiceRolls\Templates\RollOn\NoRollOn;
use PHPUnit\Framework\TestCase;

class NoRollOnTest extends TestCase
{

    protected function setUp(): void
    {
        $instanceProperty = new \ReflectionProperty(NoRollOn::class, 'noRollOn');
        $instanceProperty->setAccessible(true);
        $instanceProperty->setValue(null, null); // workaround for PhpUnit coverage
    }

    /**
     * @test
     */
    public function I_do_not_get_any_repeat_roll()
    {
        $noRollOn = NoRollOn::getIt();
        self::assertSame($noRollOn, NoRollOn::getIt());
        self::assertEquals($noRollOn, new NoRollOn());

        self::assertEquals([], $noRollOn->rollDices(123));
        foreach ([-123, 0, 456, 7891011] as $rollValue) {
            self::assertFalse($noRollOn->shouldHappen($rollValue), 'No value should trigger repeat roll');
        }
        self::assertEquals([], $noRollOn->rollDices(456));
    }

}