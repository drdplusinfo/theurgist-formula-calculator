<?php
declare(strict_types=1);

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
        $fixed = $this->getConfiguration()->isMenuPositionFixed()
            ? 'fixed'
            : '';
        $homeButton = '';
        if ($this->getConfiguration()->isShowHomeButton()) {
            $homeButton = <<<HTML
<span class="menu">
    <a id="homeButton" class="internal-url" href="https://www.drdplus.info">
        <img class="home" alt="Small dragon menu" src="/images/generic/skeleton/rules-drd-plus-dragon-menu-2x22.png">
    </a>
</span>
HTML;
        }

        return <<<HTML
  <div class="contacts visible top permanent $fixed" id="menu">
    <div class="container">
      {$homeButton}
      <span class="contact">
        <a href="mailto:info@drdplus.info">
          <span class="mobile"><i class="fas fa-envelope"></i></span>
          <span class="tablet">info@drdplus.info</span>
          <span class="desktop"><i class="fas fa-envelope"></i> info@drdplus.info</span>
        </a>
      </span>
      <span class="contact">
        <a target="_blank" class="rpgforum-contact" href="https://rpgforum.cz/forum/viewtopic.php?f=238&t=14870">
          <span class="mobile"><i class="fas fa-dice-six"></i></span>
          <span class="tablet">RPG fórum</span>
          <span class="desktop"><i class="fas fa-dice-six"></i> RPG fórum</span>
        </a>
      </span>
      <span class="contact">
        <a target="_blank" class="facebook-contact" href="https://www.facebook.com/drdplus.info">
          <span class="mobile"><i class="fab fa-facebook-square"></i></span>
          <span class="tablet">Facebook</span>
          <span class="desktop"><i class="fab fa-facebook-square"></i> Facebook</span>
        </a>
      </span>
      <span class="contact">
        <a target="_blank" class="discord-contact" href="https://discordapp.com/invite/n5nCgdu">
          <span class="mobile"><i class="fab fa-discord"></i></span>
          <span class="tablet">Discord</span>
          <span class="desktop"><i class="fab fa-discord"></i> Discord</span>
        </a>
      </span>
    </div>
  </div>
  <div class="contacts-placeholder invisible">
    Placeholder for contacts
  </div>
HTML;
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