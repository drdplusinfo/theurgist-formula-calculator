<?php
declare(strict_types=1);

namespace DrdPlus\RulesSkeleton\Web;

use DrdPlus\RulesSkeleton\Configuration;
use DrdPlus\RulesSkeleton\UsagePolicy;
use Granam\Strict\Object\StrictObject;
use Granam\String\StringInterface;

class Pass extends StrictObject implements StringInterface
{
    /** @var Configuration */
    private $configuration;
    /** @var UsagePolicy */
    private $usagePolicy;

    public function __construct(Configuration $configuration, UsagePolicy $usagePolicy)
    {
        $this->configuration = $configuration;
        $this->usagePolicy = $usagePolicy;
    }

    public function __toString()
    {
        return $this->getValue();
    }

    public function getValue(): string
    {
        return <<<HTML
{$this->getTrialTimeExpiredString()}
{$this->getLicenceManifestString()}
{$this->getFooterString()}
</div>
HTML;
    }

    private function getTrialTimeExpiredString(): string
    {
        if (!$this->usagePolicy->trialJustExpired()) {
            return '';
        }

        return '<div class="message warning">⌛ Čas tvého testování se naplnil ⌛</div>';
    }

    private function getLicenceManifestString(): string
    {
        /** @noinspection PhpUnusedLocalVariableInspection */
        $configuration = $this->configuration;
        ob_start();
        include __DIR__ . '/content/pass.php';
        return ob_get_clean();
    }

    private function getFooterString(): string
    {
        ob_start();
        include __DIR__ . '/content/pass-footer.html';
        return ob_get_clean();
    }
}