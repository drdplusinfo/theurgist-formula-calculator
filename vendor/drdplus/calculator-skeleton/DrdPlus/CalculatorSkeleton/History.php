<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

use Granam\Strict\Object\StrictObject;

class History extends StrictObject
{
    /** @var array */
    private $historyValues;
    /** @var StorageInterface */
    private $storage;
    /** @var int|null */
    private $ttl;
    /** @var \DateTimeImmutable|null */
    private $ttlDate;
    /**
     * @var DateTimeProvider
     */
    private $dateTimeProvider;

    public function __construct(StorageInterface $storage, DateTimeProvider $dateTimeProvider, ?int $ttl)
    {
        $this->storage = $storage;
        $this->ttl = $ttl;
        $this->dateTimeProvider = $dateTimeProvider;
    }

    public function saveHistory(array $valuesToRemember): void
    {
        $this->loadsHistoryValues(); // loads previous history as they would be overwritten now
        $this->storage->storeValues($valuesToRemember, $this->getTtlDate());
    }

    protected function getTtlDate(): \DateTimeImmutable
    {
        if ($this->ttlDate === null) {
            $this->ttlDate = $this->ttl !== null
                ? $this->dateTimeProvider->getNow()->modify('+' . $this->ttl . ' seconds')
                : $this->dateTimeProvider->getNow()->modify('+ 1 year');
        }
        return $this->ttlDate;
    }

    private function loadsHistoryValues(): void
    {
        $this->historyValues = $this->storage->getValues();
    }

    public function deleteHistory(): void
    {
        $this->storage->deleteAll();
        $this->historyValues = [];
    }

    public function getValue(string $name)
    {
        return $this->getHistoryValues()[$name] ?? null;
    }

    private function getHistoryValues(): array
    {
        if ($this->historyValues === null) {
            $this->loadsHistoryValues();
        }
        return $this->historyValues;
    }
}