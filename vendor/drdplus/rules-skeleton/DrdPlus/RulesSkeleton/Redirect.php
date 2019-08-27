<?php declare(strict_types=1);

namespace DrdPlus\RulesSkeleton;

use Granam\Strict\Object\StrictObject;

class Redirect extends StrictObject
{
    /** @var string */
    private $target;
    /** @var int */
    private $afterSeconds;

    public function __construct(string $target, int $afterSeconds)
    {
        $this->target = $target;
        $this->afterSeconds = $afterSeconds;
    }

    /**
     * @return string
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * @return int
     */
    public function getAfterSeconds(): int
    {
        return $this->afterSeconds;
    }
}