<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton\Web;

use Granam\Strict\Object\StrictObject;
use Granam\WebContentBuilder\Web\BodyInterface;

class HistoryDeletionBody extends StrictObject implements BodyInterface
{
    public function __toString()
    {
        return $this->getValue();
    }

    public function getValue(): string
    {
        return <<<HTML
<div class="row">
  <form class="col delete" action="/" method="post" onsubmit="return window.confirm('Opravdu smazat včetně historie?')">
    <label>
      <input type="submit" value="Smazat" name="delete_history" class="manual btn-danger">
    </label>
    <span class="hint">(včetně dlouhodobé paměti)</span>
  </form>
</div>
HTML;

    }

}