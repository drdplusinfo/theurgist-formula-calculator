<?php
declare(strict_types=1);

namespace DrdPlus\RulesSkeleton\Web;

use Granam\Strict\Object\StrictObject;
use Granam\WebContentBuilder\HtmlDocument;
use Granam\WebContentBuilder\Web\BodyInterface;

class PassBody extends StrictObject implements RulesBodyInterface
{
    /** @var Pass */
    private $pass;

    public function __construct(Pass $pass)
    {
        $this->pass = $pass;
    }

    public function __toString()
    {
        return $this->getValue();
    }

    public function getValue(): string
    {
        return <<<HTML
<div class="main pass">
  <div class="background-image"></div>
  {$this->pass->getValue()}
</div>
HTML;
    }

    public function postProcessDocument(HtmlDocument $htmlDocument): HtmlDocument
    {
        return $htmlDocument;
    }
}