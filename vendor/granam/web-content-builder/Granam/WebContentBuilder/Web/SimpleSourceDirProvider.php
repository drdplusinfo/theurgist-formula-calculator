<?php declare(strict_types=1);

namespace Granam\WebContentBuilder\Web;

use Granam\Strict\Object\StrictObject;

class SimpleSourceDirProvider extends StrictObject implements SourceDirProviderInterface
{
    /**
     * @var string
     */
    private $sourceDir;

    public function __construct(string $sourceDir)
    {
        $this->sourceDir = $sourceDir;
    }

    public function getSourceDir(): string
    {
        return $this->sourceDir;
    }

}