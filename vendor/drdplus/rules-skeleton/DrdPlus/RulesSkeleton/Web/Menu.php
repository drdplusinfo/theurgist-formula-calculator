<?php declare(strict_types=1);

namespace DrdPlus\RulesSkeleton\Web;

use DrdPlus\RulesSkeleton\Configuration;
use DrdPlus\RulesSkeleton\Request;
use DrdPlus\RulesSkeleton\CurrentWebVersion;
use DrdPlus\WebVersions\WebVersions;
use Granam\Strict\Object\StrictObject;
use Granam\String\StringInterface;

class Menu extends StrictObject implements StringInterface
{
    /** @var Configuration */
    private $configuration;
    /** @var WebVersions */
    private $webVersions;
    /** @var CurrentWebVersion */
    private $currentWebVersion;
    /** @var Request */
    private $request;

    public function __construct(Configuration $configuration, WebVersions $webVersions, CurrentWebVersion $currentWebVersion, Request $request)
    {
        $this->configuration = $configuration;
        $this->webVersions = $webVersions;
        $this->currentWebVersion = $currentWebVersion;
        $this->request = $request;
    }

    public function __toString()
    {
        return $this->getValue();
    }

    public function getValue(): string
    {
        /** @noinspection PhpUnusedLocalVariableInspection */
        $configuration = $this->configuration;
        ob_start();
        include __DIR__ . '/content/menu.php';
        return ob_get_clean();
    }

    protected function getConfiguration(): Configuration
    {
        return $this->configuration;
    }

    protected function getCurrentWebVersion(): CurrentWebVersion
    {
        return $this->currentWebVersion;
    }

    protected function getWebVersions(): WebVersions
    {
        return $this->webVersions;
    }

    protected function getRequest(): Request
    {
        return $this->request;
    }
}