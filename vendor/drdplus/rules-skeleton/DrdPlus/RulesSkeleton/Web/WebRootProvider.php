<?php declare(strict_types=1);

namespace DrdPlus\RulesSkeleton\Web;

use DrdPlus\RulesSkeleton\Dirs;
use Granam\Strict\Object\StrictObject;
use Granam\WebContentBuilder\Web\SourceDirProviderInterface;

class WebRootProvider extends StrictObject implements SourceDirProviderInterface
{
    /**
     * @var Dirs
     */
    private $dirs;

    public function __construct(Dirs $dirs)
    {
        $this->dirs = $dirs;
    }

    public function getSourceDir(): string
    {
        return $this->dirs->getWebRoot();
    }

}