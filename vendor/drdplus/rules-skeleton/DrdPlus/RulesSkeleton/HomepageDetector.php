<?php declare(strict_types=1);

namespace DrdPlus\RulesSkeleton;

use Granam\Strict\Object\StrictObject;

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
        $path = $this->pathProvider->getPath();
        return $path === '' || $path === '/';
    }
}