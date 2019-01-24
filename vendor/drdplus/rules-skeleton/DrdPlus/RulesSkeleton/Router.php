<?php
declare(strict_types=1);

namespace DrdPlus\RulesSkeleton;

use Granam\Strict\Object\StrictObject;

class Router extends StrictObject
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var array|string[]
     */
    private $routesToKnownBlocNames;

    public function __construct(Request $request, array $routesToKnownBlocNames)
    {
        $this->request = $request;
        $this->routesToKnownBlocNames = \array_map(function (string $blockName) {
            return \preg_replace('~^block-~', '', $blockName);
        }, $routesToKnownBlocNames);
    }

    public function getRequiredBlockName(): string
    {
        $route = \ltrim($this->request->getPathInfo(), '/');

        return $this->routesToKnownBlocNames[$route] ?? '';
    }
}