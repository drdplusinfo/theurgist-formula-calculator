<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\Cache;
use DrdPlus\RulesSkeleton\CurrentWebVersion;
use DrdPlus\RulesSkeleton\Web\Menu;
use DrdPlus\RulesSkeleton\Web\RulesContent;
use DrdPlus\RulesSkeleton\Web\RulesMainContent;

class CalculatorContent extends RulesContent
{
    public function __construct(
        RulesMainContent $rulesMainContent,
        Menu $menu,
        CurrentWebVersion $currentWebVersion,
        Cache $cache
    )
    {
        parent::__construct($rulesMainContent, $menu, $currentWebVersion, $cache, self::FULL, null);
    }
}