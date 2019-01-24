<?php
declare(strict_types=1);

namespace DrdPlus\RulesSkeleton;

use Granam\Git\Git;

class WebCache extends Cache
{
    public function __construct(
        CurrentWebVersion $currentWebVersion,
        Dirs $dirs,
        Request $request,
        Git $git,
        bool $isInProduction,
        string $cachePrefix = null
    )
    {
        parent::__construct($currentWebVersion, $dirs, $request, $git, $isInProduction, $cachePrefix ?? 'page-' . \md5($dirs->getCacheRoot()));
    }
}