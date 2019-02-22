<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\CalculatorSkeleton\DateTimeProvider;
use DrdPlus\CalculatorSkeleton\Memory;
use DrdPlus\CalculatorSkeleton\StorageInterface;
use Granam\Tests\Tools\TestWithMockery;

class MemoryTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_save_values_with_null_as_ttl(): void
    {
        $expectedValues = ['foo' => 'bar'];
        $storage = $this->mockery(StorageInterface::class);
        $storage->shouldReceive('storeValues')
            ->once()
            ->with($expectedValues, null);
        /** @var StorageInterface $storage */
        /** @noinspection PhpUnhandledExceptionInspection */
        $memory = new Memory($storage, new DateTimeProvider(new \DateTimeImmutable()), null);
        $memory->saveMemory($expectedValues);
    }

    /**
     * @test
     */
    public function I_can_save_values_with_specific_ttl(): void
    {
        $expectedValues = ['foo' => 'bar'];
        $expectedTtlSeconds = 123456;
        $nowInSeconds = 456789;
        /** @noinspection PhpUnhandledExceptionInspection */
        $now = new \DateTimeImmutable('@' . $nowInSeconds);
        /** @noinspection PhpUnhandledExceptionInspection */
        $expectedTtlDate = new \DateTimeImmutable('@' . ($nowInSeconds + $expectedTtlSeconds));
        $storage = $this->mockery(StorageInterface::class);
        $storage->shouldReceive('storeValues')
            ->once()
            ->andReturnUsing(function (array $valuesToStore, \DateTimeInterface $ttlDate) use ($expectedValues, $expectedTtlDate) {
                self::assertSame($expectedValues, $valuesToStore);
                self::assertNotNull($ttlDate);
                self::assertEquals($expectedTtlDate, $ttlDate);
            });
        /** @var StorageInterface $storage */
        $memory = new Memory($storage, new DateTimeProvider($now), $expectedTtlSeconds);
        $memory->saveMemory($expectedValues);
    }

    /**
     * @test
     */
    public function I_can_get_value(): void
    {
        $storage = $this->mockery(StorageInterface::class);
        $storage->shouldReceive('getValues')
            ->atLeast()->once()
            ->andReturn($values = ['foo' => 'bar']);
        /** @var StorageInterface $storage */
        /** @noinspection PhpUnhandledExceptionInspection */
        $memory = new Memory($storage, new DateTimeProvider(new \DateTimeImmutable()), null);
        self::assertSame('bar', $memory->getValue('foo'));
        self::assertNull($memory->getValue('baz'));
    }

    /**
     * @test
     */
    public function I_can_delete_memory(): void
    {
        $storage = $this->mockery(StorageInterface::class);
        $storage->shouldReceive('deleteAll')
            ->once();
        /** @var StorageInterface $storage */
        /** @noinspection PhpUnhandledExceptionInspection */
        $memory = new Memory($storage, new DateTimeProvider(new \DateTimeImmutable()), null);
        $memory->deleteMemory();
    }

}
