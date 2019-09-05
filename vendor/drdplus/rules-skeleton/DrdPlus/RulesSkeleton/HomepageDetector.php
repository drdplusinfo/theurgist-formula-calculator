<?php declare(strict_types=1);

namespace DrdPlus\RulesSkeleton;

use Granam\Strict\Object\StrictObject;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class HomepageDetector extends StrictObject
{
    /**
     * @var PathProvider
     */
    private $pathProvider;

    public function __construct(PathProvider $pathProvider)
    {
        $this->pathProvider = $pathProvider;
    }

    public function isHomepageRequested(): bool
    {
        try {
            $path = $this->pathProvider->getPath();
            return $path === '' || $path === '/';
        } catch (RouteNotFoundException | ResourceNotFoundException $notFoundException) {
            return false;
        }
    }
}