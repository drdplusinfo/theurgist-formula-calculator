<?php declare(strict_types=1);

namespace DrdPlus\RulesSkeleton;

use Granam\Git\Git;

class WebCache extends Cache
{
    public const TABLES = 'tables';
    public const PASS = 'pass';
    public const PASSED = 'passed';
    public const NOT_FOUND = 'not_found';
    public const DUMMY = 'dummy';
    public const ROUTER = 'router';

    public function __construct(
        CurrentWebVersion $currentWebVersion,
        Dirs $dirs,
        string $cacheSubDir,
        Request $request,
        ContentIrrelevantRequestAliases $contentIrrelevantRequestAliases,
        ContentIrrelevantParametersFilter $contentIrrelevantParametersFilter,
        Git $git,
        bool $isInProduction
    )
    {
        parent::__construct(
            $currentWebVersion,
            $dirs->getProjectRoot(),
            $dirs->getCacheRoot() . '/web/' . $cacheSubDir,
            $request,
            $contentIrrelevantRequestAliases,
            $contentIrrelevantParametersFilter,
            $git,
            $isInProduction
        );
    }

}
