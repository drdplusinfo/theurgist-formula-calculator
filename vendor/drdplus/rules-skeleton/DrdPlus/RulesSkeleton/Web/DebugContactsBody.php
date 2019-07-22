<?php declare(strict_types=1);

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
        ob_start();
        include __DIR__ . '/content/debug-contacts.html';
        return ob_get_clean();
    }
}