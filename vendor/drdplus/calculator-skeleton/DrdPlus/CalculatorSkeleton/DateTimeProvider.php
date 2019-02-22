<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

use Granam\Strict\Object\StrictObject;

class DateTimeProvider extends StrictObject
{
    /**
     * @var \DateTimeImmutable
     */
    private $now;

    public function __construct(\DateTimeImmutable $now)
    {
        $this->now = $now;
    }

    public function getNow(): \DateTimeImmutable
    {
        return $this->now;
    }
}