<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\CalculatorSkeleton\CurrentValues;
use DrdPlus\CalculatorSkeleton\Memory;
use DrdPlus\Tests\CalculatorSkeleton\Partials\AbstractCalculatorContentTest;

class CurrentValuesTest extends AbstractCalculatorContentTest
{
    /**
     * @test
     */
    public function I_can_get_selected_value(): void
    {
        $currentValues = new CurrentValues(['foo' => 'Foo', 'bar' => 'Bar'], $this->createMemory(['baz' => 'Baz']));
        self::assertSame('Foo', $currentValues->getSelectedValue('foo'));
        self::assertSame('Bar', $currentValues->getSelectedValue('bar'));
        self::assertNull(
            $currentValues->getSelectedValue('baz'),
            'That value should NOT be taken from memory as it should be taken from selected values only'
        );
    }

    /**
     * @test
     */
    public function I_can_get_current_value(): void
    {
        $currentValues = new CurrentValues(
            ['foo' => 'Foo', 'bar' => 'Bar', 'baz' => 'Baz'],
            $this->createMemory(['baz' => 'Zab', 'qux' => 'Xuq'])
        );
        self::assertSame('Foo', $currentValues->getCurrentValue('foo'));
        self::assertSame('Bar', $currentValues->getCurrentValue('bar'));
        self::assertSame(
            'Baz',
            $currentValues->getCurrentValue('baz'),
            'That value should NOT be taken from memory as selected value is different'
        );
        self::assertSame(
            'Xuq',
            $currentValues->getCurrentValue('qux'),
            'That value should be taken from memory as selected value is not set'
        );
    }

    /**
     * @param array $values
     * @return Memory|\Mockery\MockInterface
     */
    private function createMemory(array $values): Memory
    {
        $memory = $this->mockery(Memory::class);
        $memory->shouldReceive('getValue')
            ->zeroOrMoreTimes()
            ->andReturnUsing(function (string $name) use ($values) {
                return $values[$name] ?? null;
            });

        return $memory;
    }
}
