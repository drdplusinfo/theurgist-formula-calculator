<?php
declare(strict_types=1);

namespace DrdPlus\RulesSkeleton\Web;

use DrdPlus\RulesSkeleton\Configuration;
use DrdPlus\RulesSkeleton\HtmlHelper;
use Granam\WebContentBuilder\Web\CssFiles;
use Granam\WebContentBuilder\Web\JsFiles;

class Head extends \Granam\WebContentBuilder\Web\Head
{
    public function __construct(
        Configuration $configuration,
        HtmlHelper $htmlHelper,
        CssFiles $cssFiles,
        JsFiles $jsFiles,
        string $customWebName = null
    )
    {
        $pageTitle = $this->buildPageTitle($customWebName, $configuration);
        parent::__construct(
            $htmlHelper,
            $cssFiles,
            $jsFiles,
            $pageTitle,
            $configuration->getFavicon(),
            $configuration->getGoogleAnalyticsId()
        );
    }

    protected function buildPageTitle(?string $customWebName, Configuration $configuration): string
    {
        $name = $customWebName ?? $configuration->getWebName();
        $smiley = $configuration->getTitleSmiley();

        return $smiley !== ''
            ? ($smiley . ' ' . $name)
            : $name;
    }
}