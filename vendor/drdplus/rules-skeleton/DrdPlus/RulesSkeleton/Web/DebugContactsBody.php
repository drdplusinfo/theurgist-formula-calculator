<?php
declare(strict_types=1);

namespace DrdPlus\RulesSkeleton\Web;

use Granam\Strict\Object\StrictObject;
use Granam\WebContentBuilder\Web\BodyInterface;

class DebugContactsBody extends StrictObject implements BodyInterface
{
    public function __toString()
    {
        return $this->getValue();
    }

    public function getValue(): string
    {
        return <<<HTML
<div id="debug_contacts">
    Jestli jsi vážně našel chybu, ať už pouhou chybějící čárku, nebo opravdový nesmysl, pošli nám to
    <a href="mailto:info@drdplus.info">mailem</a>, nahlaš na
    <a href="https://www.facebook.com/drdplus.info">Facebooku</a> nebo
    <a href="https://rpgforum.cz/forum/viewtopic.php?f=238&t=14870">řekni na rpgforu</a>.
</div>
HTML;
    }
}