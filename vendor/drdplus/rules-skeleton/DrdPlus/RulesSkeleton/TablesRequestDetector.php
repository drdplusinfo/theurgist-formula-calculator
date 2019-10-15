<?php declare(strict_types=1);

namespace DrdPlus\RulesSkeleton;

use Granam\Strict\Object\StrictObject;

class TablesRequestDetector extends StrictObject
{
    /**
     * @var RulesUrlMatcher
     */
    private $rulesUrlMatcher;
    /**
     * @var Request
     */
    private $request;

    public function __construct(RulesUrlMatcher $rulesUrlMatcher, Request $request)
    {
        $this->rulesUrlMatcher = $rulesUrlMatcher;
        $this->request = $request;
    }

    public function areTablesRequested(): bool
    {
        return $this->rulesUrlMatcher->match($this->request->getCurrentUrl())->getRouteName() === 'tables'
            || $this->request->areTablesRequested();
    }
}
