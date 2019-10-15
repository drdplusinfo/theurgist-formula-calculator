<?php declare(strict_types=1);

namespace DrdPlus\RulesSkeleton;

class DummyWebCache extends WebCache
{
    public function isCacheValid(): bool
    {
        return false;
    }

    public function getCachedContent(): string
    {
        return '';
    }

    public function saveContentForDebug(string $content): void
    {
    }

    public function cacheContent(string $content): void
    {
    }

}