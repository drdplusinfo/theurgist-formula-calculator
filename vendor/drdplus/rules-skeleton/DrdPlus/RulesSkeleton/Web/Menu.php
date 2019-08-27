<?php declare(strict_types=1);

namespace DrdPlus\RulesSkeleton\Web;

use DrdPlus\RulesSkeleton\Configuration;
use DrdPlus\RulesSkeleton\HomepageDetector;
use Granam\Strict\Object\StrictObject;
use Granam\String\StringInterface;

class Menu extends StrictObject implements StringInterface
{
    /** @var Configuration */
    private $configuration;
    /** @var HomepageDetector */
    private $homepageDetector;

    public function __construct(Configuration $configuration, HomepageDetector $homepageDetector)
    {
        $this->configuration = $configuration;
        $this->homepageDetector = $homepageDetector;
    }

    public function __toString()
    {
        return $this->getValue();
    }

    public function getValue(): string
    {
        /** @noinspection PhpUnusedLocalVariableInspection */
        $configuration = $this->configuration;
        /** @noinspection PhpUnusedLocalVariableInspection */
        $homepageDetector = $this->homepageDetector;
        ob_start();
        include __DIR__ . '/content/menu.php';
        return ob_get_clean();
    }

    protected function getConfiguration(): Configuration
    {
        return $this->configuration;
    }
}