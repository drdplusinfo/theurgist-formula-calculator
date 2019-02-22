<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

use Granam\Strict\Object\StrictObject;

class Memory extends StrictObject
{
    /** @var StorageInterface */
    private $storage;
    /** @var int|null */
    private $ttl;
    /** @var null|\DateTimeImmutable */
    private $ttlDate = false;
    /**
     * @var DateTimeProvider
     */
    private $dateTimeProvider;

    public function __construct(StorageInterface $storage, DateTimeProvider $dateTimeProvider, ?int $ttl)
    {
        $this->storage = $storage;
        $this->dateTimeProvider = $dateTimeProvider;
        $this->ttl = $ttl;
    }

    public function saveMemory(array $valuesToRemember): void
    {
        $this->storage->storeValues($valuesToRemember, $this->getTtlDate());
    }

    private function getTtlDate(): ?\DateTimeImmutable
    {
        if ($this->ttlDate === false) {
            $this->ttlDate = $this->ttl !== null
                ? $this->dateTimeProvider->getNow()->modify('+ ' . $this->ttl . ' seconds')
                : null; // end of session
        }
        return $this->ttlDate;
    }

    public function deleteMemory(): void
    {
        $this->storage->deleteAll();
    }

    public function getValue(string $name)
    {
        return $this->storage->getValues()[$name] ?? null;
    }
}