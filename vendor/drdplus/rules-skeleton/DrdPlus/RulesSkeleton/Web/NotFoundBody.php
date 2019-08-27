<?php declare(strict_types=1);

namespace DrdPlus\RulesSkeleton\Web;

use DrdPlus\RulesSkeleton\Request;
use Granam\Strict\Object\StrictObject;
use Granam\WebContentBuilder\HtmlDocument;

class NotFoundBody extends StrictObject implements RulesBodyInterface
{
    /** @var Request */
    private $request;
    /** @var DebugContactsBody */
    private $debugContactsBody;

    public function __construct(Request $request, DebugContactsBody $debugContactsBody)
    {
        $this->request = $request;
        $this->debugContactsBody = $debugContactsBody;
    }

    public function __toString()
    {
        return $this->getValue();
    }

    public function getValue(): string
    {
        return <<<HTML
{$this->getNotFoundString()}
HTML;
    }

    private function getNotFoundString(): string
    {
        /** @noinspection PhpUnusedLocalVariableInspection */
        $request = $this->request;
        /** @noinspection PhpUnusedLocalVariableInspection */
        $debugContactsBody = $this->debugContactsBody;
        ob_start();
        include __DIR__ . '/content/not-found.php';
        return ob_get_clean();
    }

    public function postProcessDocument(HtmlDocument $htmlDocument): HtmlDocument
    {
        return $htmlDocument;
    }
}