<?php
declare(strict_types=1);

namespace Granam\WebContentBuilder\Web;

use Granam\WebContentBuilder\HtmlDocument;

interface HtmlContentInterface extends ContentInterface
{
    public function getHtmlDocument(): HtmlDocument;
}