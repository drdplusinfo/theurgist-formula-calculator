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
        return <<<HTML
<h1>{$this->configuration->getWebName()}</h1>

<h3>Zkusím</h3>
<div class="row">
  <form class="manifest trial" action="/" method="post">
    <div class="col">
      <button class="btn btn-light" type="submit" id="trial" name="trial" value="trial">
        Zkusím {$this->configuration->getWebName()}
      </button>
    </div>
    <div class="col">
      <ul>
        <li>
          <label for="trial">
            chci se na <strong>{$this->configuration->getWebName()}</strong> jen na chvíli podívat, ať vím, o co jde
          </label>
        </li>
      </ul>
    </div>
  </form>
</div>

<h2>Koupím</h2>
<div class="row">
  <form class="manifest buy" action="{$this->configuration->getEshopUrl()}">
    <div class="col">
      <button class="btn btn-light" type="submit" id="buy" name="buy" value="buy">Koupím {$this->configuration->getWebName()}</button>
    </div>
    <div class="col">
      <ul>
        <li>
          <label for="buy">
            zatím nemám <strong>{$this->configuration->getWebName()}</strong>, tak si je od Altaru koupím <span class="note">(doporučujeme PDF verzi)</span>
          </label>
        </li>
      </ul>
    </div>
  </form>
</div>

<h3>Vlastním</h3>
<div class="row">
  <div class="manifest owning">
    <div class="col">
      <button class="btn btn-light" type="button" id="confirm" data-toggle="modal" data-target="#confirm_ownership">
        Vlastním {$this->configuration->getWebName()}
      </button>
    </div>
    <div class="col">
      <ul>
        <li>
          <label for="confirm">
            prohlašuji na svou čest, že vlastním legální kopii <strong>{$this->configuration->getWebName()}</strong>
          </label>
        </li>
      </ul>
    </div>
  </div>
</div>

<div class="modal" id="confirmOwnership">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div class="modal-title note" id="confirmOwnershipModalLabel">Vlastním {$this->configuration->getWebName()}</div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        A klidně to potvrdím dvakrát
      </div>
      <div class="modal-footer">
        <form class="manifest owning" action="/" method="post">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Zavřít</button>
          <button type="submit" class="btn btn-primary" name="confirm" value="1">Vlastním</button>
        </form>
      </div>
    </div>
  </div>
</div>
HTML;
    }

    private function getFooterString(): string
    {
        return <<<HTML
<div class="footer">
  <div class="row">
    <p class="col">
      Dračí doupě<span class="upper-index">®</span>, DrD<span class="upper-index">TM</span>a ALTAR<span
        class="upper-index">®</span> jsou zapsané ochranné známky nakladatelství <a
        href="http://www.altar.cz/">ALTAR</a>.
    </p>
  </div>
  <div class="row">
    <p class="col">
      Hledáš-li živou komunitu kolem RPG, mrkni na <a
        href="https://rpgforum.cz">rpgforum.cz</a>, nebo rovnou na
      <a href="https://rpgforum.cz/forum/viewforum.php?f=238">
        sekci pro DrD+.
      </a>
    </p>
  </div>
  <div class="row">
    <p class="col">Pokud nevlastníš pravidla DrD+, prosím, <a href="https://obchod.altar.cz">kup si je</a>
      - podpoříš autory a <strong>budoucnost Dračího doupěte</strong>. Děkujeme!
    </p>
  </div>
HTML;
    }
}