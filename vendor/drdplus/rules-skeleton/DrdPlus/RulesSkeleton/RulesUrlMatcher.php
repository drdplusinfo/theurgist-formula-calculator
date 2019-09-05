<?php declare(strict_types=1);

namespace DrdPlus\RulesSkeleton;

use Granam\Strict\Object\StrictObject;
use Granam\String\StringInterface;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

    class RulesUrlMatcher extends StrictObject
{
    /**
     * @var UrlMatcherInterface
     */
    private $urlMatcher;

    public function __construct(UrlMatcherInterface $urlMatcher)
    {
        $this->urlMatcher = $urlMatcher;
    }

    /**
     * @param string|StringInterface $pathInfo
     * @return RouteMatch
     * @throws \Symfony\Component\Routing\Exception\ResourceNotFoundException
     * @throws \Symfony\Component\Routing\Exception\RouteNotFoundException
     */
    public function match($pathInfo): RouteMatch
    {
        $match = $this->urlMatcher->match($pathInfo);
        return new RouteMatch($match);
    }
}