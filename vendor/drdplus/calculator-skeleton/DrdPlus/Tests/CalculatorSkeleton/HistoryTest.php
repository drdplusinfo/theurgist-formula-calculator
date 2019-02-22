<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\CalculatorSkeleton\DateTimeProvider;
use DrdPlus\CalculatorSkeleton\History;
use DrdPlus\CalculatorSkeleton\StorageInterface;
use DrdPlus\Tests\CalculatorSkeleton\Partials\AbstractCalculatorContentTest;

class HistoryTest extends AbstractCalculatorContentTest
{
    /**
     * @test
     */
    public function I_can_save_values_with_year_as_default_ttl(): void
    {
        $expectedValues = ['foo' => 'bar'];
        /** @noinspection PhpUnhandledExceptionInspection */
        $now = new \DateTimeImmutable('@123456');
        $expectedTtlDate = $now->modify('+ 1 year');
        $storage = $this->mockery(StorageInterface::class);
        $storage->shouldReceive('getValues')
            ->andReturn([]);
        $storage->shouldReceive('storeValues')
            ->once()
            ->andReturnUsing(function (array $values, \DateTimeInterface $ttlDate) use ($expectedValues, $expectedTtlDate) {
                self::assertSame($expectedValues, $values);
                self::assertEquals($ttlDate, $expectedTtlDate);
            });
        /** @var StorageInterface $storage */
        $history = new History($storage, new DateTimeProvider($now), null);
        $history->saveHistory($expectedValues);
    }

    /**
     * @test
     */
    public function I_can_save_values_with_specific_ttl(): void
    {
        $expectedValues = ['foo' => 'bar'];
        /** @noinspection PhpUnhandledExceptionInspection */
        $now = new \DateTimeImmutable('@123456');
        $ttl = 5;
        $expectedTtlDate = $now->modify('+ ' . $ttl . ' seconds');
        $storage = $this->mockery(StorageInterface::class);
        $storage->shouldReceive('getValues')
            ->andReturn([]);
        $storage->shouldReceive('storeValues')
            ->once()
            ->andReturnUsing(function (array $values, \DateTimeInterface $ttlDate) use ($expectedValues, $expectedTtlDate) {
                self::assertSame($expectedValues, $values);
                self::assertEquals($ttlDate, $expectedTtlDate);
            });
        /** @var StorageInterface $storage */
        $history = new History($storage, new DateTimeProvider($now), $ttl);
        $history->saveHistory($expectedValues);
    }

    /**
     * @test
     */
    public function Previous_values_are_restored_before_saving_new_ones(): void
    {
        $historyValues = ['bubu' => 'baf'];
        $expectedValues = ['foo' => 'bar'];
        $storage = $this->mockery(StorageInterface::class);
        $storage->shouldReceive('getValues')
            ->andReturn($historyValues, $expectedValues, []);
        $storage->shouldReceive('storeValues');
        /** @var StorageInterface $storage */
        /** @noinspection PhpUnhandledExceptionInspection */
        $history = new History($storage, new DateTimeProvider(new \DateTimeImmutable()), null);
        $history->saveHistory($expectedValues);
        self::assertSame('baf', $history->getValue('bubu'));
        self::assertNull($history->getValue('foo'));
        $history->saveHistory([]);
        self::assertNull($history->getValue('bubu'));
        self::assertSame('bar', $history->getValue('foo'));
        $history->saveHistory([123 => 456]);
        self::assertNull($history->getValue('bubu'));
        self::assertNull($history->getValue('foo'));
    }

    /**
     * @test
     */
    public function I_can_get_value(): void
    {
        $storage = $this->mockery(StorageInterface::class);
        $storage->shouldReceive('getValues')
            ->atLeast()->once()
            ->andReturn(['foo' => 'bar']);
        /** @var StorageInterface $storage */
        /** @noinspection PhpUnhandledExceptionInspection */
        $history = new History($storage, new DateTimeProvider(new \DateTimeImmutable()), null);
        self::assertSame('bar', $history->getValue('foo'));
        self::assertNull($history->getValue('baz'));
    }

    /**
     * @test
     */
    public function I_can_delete_history(): void
    {
        $storage = $this->mockery(StorageInterface::class);
        $storage->shouldReceive('deleteAll')
            ->once();
        /** @var StorageInterface $storage */
        /** @noinspection PhpUnhandledExceptionInspection */
        $history = new History($storage, new DateTimeProvider(new \DateTimeImmutable()), null);
        $history->deleteHistory();
    }
}
