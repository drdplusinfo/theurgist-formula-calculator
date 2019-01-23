<?php
declare(strict_types=1);

namespace Granam\Tests\DiceRolls\Templates\Evaluators;

use Granam\DiceRolls\DiceRoll;
use Granam\DiceRolls\DiceRollEvaluator;
use Granam\DiceRolls\Templates\Evaluators\OneToOneEvaluator;
use Granam\Integer\IntegerInterface;
use Granam\Integer\PositiveInteger;
use Granam\Tests\Tools\TestWithMockery;

abstract class AbstractEvaluatorTest extends TestWithMockery
{

    /**
     * @test
     */
    public function I_can_create_it(): void
    {
        $evaluatorClass = $this->getEvaluatorClass();
        $reflection = new \ReflectionClass($evaluatorClass);
        $staticContainer = $reflection->getProperty($this->getStaticContainerName());
        $staticContainer->setAccessible(true);
        // to test factory method coverage (code coverage in separate process is broken)
        $staticContainer->setValue($evaluatorClass, null);
        $evaluator = $evaluatorClass::getIt();
        self::assertSame($evaluator, $evaluatorClass::getIt());
        self::assertEquals($evaluator, new $evaluatorClass());
    }

    private function getStaticContainerName(): string
    {
        self::assertGreaterThan(0, preg_match('~\\\(?<baseName>[^\\\]+)$~', $this->getEvaluatorClass(), $matches));
        $evaluatorBaseName = $matches['baseName'];

        return lcfirst($evaluatorBaseName);
    }

    /**
     * @return string|DiceRollEvaluator|OneToOneEvaluator ...
     */
    protected function getEvaluatorClass()
    {
        return preg_replace('~[\\\]Tests([\\\].+)Test$~', '$1', static::class);
    }

    /**
     * @param $value
     * @return \Mockery\MockInterface|DiceRoll
     */
    protected function createDiceRoll($value)
    {
        $diceRoll = $this->mockery(DiceRoll::class);
        $diceRoll->shouldReceive('getRolledNumber')
            ->andReturn($rolledNumber = $this->mockery(PositiveInteger::class));
        $rolledNumber->shouldReceive('getValue')
            ->andReturn($value);

        return $diceRoll;
    }
}