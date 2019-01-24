<?php
declare(strict_types=1);

namespace DrdPlus\RulesSkeleton;

use DeviceDetector\Parser\Bot;
use DrdPlus\RulesSkeleton\Web\DebugContactsBody;
use DrdPlus\RulesSkeleton\Web\EmptyMenu;
use DrdPlus\RulesSkeleton\Web\Head;
use DrdPlus\RulesSkeleton\Web\Menu;
use DrdPlus\RulesSkeleton\Web\Pass;
use DrdPlus\RulesSkeleton\Web\PassBody;
use DrdPlus\RulesSkeleton\Web\PassContent;
use DrdPlus\RulesSkeleton\Web\PdfBody;
use DrdPlus\RulesSkeleton\Web\RulesMainBody;
use DrdPlus\RulesSkeleton\Web\RulesMainContent;
use DrdPlus\RulesSkeleton\Web\TablesBody;
use DrdPlus\RulesSkeleton\Web\TablesContent;
use DrdPlus\RulesSkeleton\Web\WebFiles;
use DrdPlus\WebVersions\WebVersions;
use Granam\Git\Git;
use Granam\Strict\Object\StrictObject;
use Granam\String\StringTools;
use Granam\WebContentBuilder\Web\Body;
use Granam\WebContentBuilder\Web\CssFiles;
use Granam\WebContentBuilder\Web\HtmlContentInterface;
use Granam\WebContentBuilder\Web\JsFiles;

class ServicesContainer extends StrictObject
{

    /** @var CurrentWebVersion */
    protected $currentWebVersion;
    /** @var WebVersions */
    protected $webVersions;
    /** @var Git */
    protected $git;
    /** @var Configuration */
    protected $configuration;
    /** @var HtmlHelper */
    protected $htmlHelper;
    /** @var Cache */
    protected $webCache;
    /** @var Head */
    protected $head;
    /** @var Menu */
    protected $menu;
    /** @var Body */
    protected $rulesMainBody;
    /** @var TablesBody */
    protected $tablesBody;
    /** @var Cache */
    private $tablesWebCache;
    /** @var CssFiles */
    protected $cssFiles;
    /** @var JsFiles */
    protected $jsFiles;
    /** @var WebFiles */
    protected $webFiles;
    /** @var Request */
    protected $request;
    /** @var Bot */
    protected $botParser;
    /** @var RulesMainContent */
    protected $rulesMainContent;
    /** @var RulesMainContent */
    protected $tablesMainContent;
    /** @var HtmlContentInterface */
    protected $rulesPdfWebContent;
    /** @var RulesMainContent */
    protected $passContent;
    /** @var CookiesService */
    private $cookiesService;
    /** @var \DateTimeImmutable */
    private $now;
    /** @var Cache */
    private $passWebCache;
    /** @var Cache */
    private $passedWebCache;
    /** @var UsagePolicy */
    private $usagePolicy;
    /** @var Pass */
    private $pass;
    /** @var PassBody */
    private $passBody;
    /** @var DebugContactsBody */
    private $debugContactsBody;
    /** @var PdfBody */
    private $pdfBody;

    public function __construct(Configuration $configuration, HtmlHelper $htmlHelper)
    {
        $this->configuration = $configuration;
        $this->htmlHelper = $htmlHelper;
    }

    public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }

    public function getCurrentWebVersion(): CurrentWebVersion
    {
        if ($this->currentWebVersion === null) {
            $this->currentWebVersion = new CurrentWebVersion(
                $this->getDirs(),
                $this->getGit(),
                $this->getWebVersions()
            );
        }

        return $this->currentWebVersion;
    }

    public function getWebVersions(): WebVersions
    {
        if ($this->webVersions === null) {
            $this->webVersions = new WebVersions($this->getGit(), $this->getDirs()->getProjectRoot());
        }

        return $this->webVersions;
    }

    public function getRequest(): Request
    {
        if ($this->request === null) {
            $this->request = new Request($this->getBotParser());
        }

        return $this->request;
    }

    public function getGit(): Git
    {
        if ($this->git === null) {
            $this->git = new Git();
        }

        return $this->git;
    }

    public function getBotParser(): Bot
    {
        if ($this->botParser === null) {
            $this->botParser = new Bot();
        }

        return $this->botParser;
    }

    public function getRulesMainContent(): RulesMainContent
    {
        if ($this->rulesMainContent === null) {
            $this->rulesMainContent = new RulesMainContent(
                $this->getHtmlHelper(),
                $this->getHead(),
                $this->getRulesMainBody()
            );
        }

        return $this->rulesMainContent;
    }

    public function getTablesContent(): TablesContent
    {
        if ($this->tablesMainContent === null) {
            $this->tablesMainContent = new TablesContent(
                $this->getHtmlHelper(),
                $this->getHeadForTables(),
                $this->getTablesBody()
            );
        }

        return $this->tablesMainContent;
    }

    public function getPdfContent(): PdfContent
    {
        if ($this->rulesPdfWebContent === null) {
            $this->rulesPdfWebContent = new PdfContent($this->getPdfBody());
        }

        return $this->rulesPdfWebContent;
    }

    public function getPassContent(): PassContent
    {
        if ($this->passContent === null) {
            $this->passContent = new PassContent(
                $this->getHtmlHelper(),
                $this->getHead(),
                $this->getPassBody()
            );
        }

        return $this->passContent;
    }

    public function getHtmlHelper(): HtmlHelper
    {
        return $this->htmlHelper;
    }

    public function getMenu(): Menu
    {
        if ($this->menu === null) {
            $this->menu = new Menu($this->getConfiguration(), $this->getWebVersions(), $this->getCurrentWebVersion(), $this->getRequest());
        }

        return $this->menu;
    }

    public function getHead(): Head
    {
        if ($this->head === null) {
            $this->head = new Head($this->getConfiguration(), $this->getHtmlHelper(), $this->getCssFiles(), $this->getJsFiles());
        }

        return $this->head;
    }

    public function getRulesMainBody(): RulesMainBody
    {
        if ($this->rulesMainBody === null) {
            $this->rulesMainBody = new RulesMainBody($this->getWebFiles(), $this->getRulesMainBodyParameters());
        }

        return $this->rulesMainBody;
    }

    public function getRulesMainBodyParameters(): array
    {
        return ['debugContacts' => $this->getDebugContactsBody()];
    }

    public function getHeadForTables(): Head
    {
        return new Head(
            $this->getConfiguration(),
            $this->getHtmlHelper(),
            $this->getCssFiles(),
            $this->getJsFiles(),
            'Tabulky pro ' . $this->getHead()->getPageTitle()
        );
    }

    public function getTablesBody(): TablesBody
    {
        if ($this->tablesBody === null) {
            $this->tablesBody = new TablesBody($this->getRulesMainBody(), $this->getHtmlHelper(), $this->getRequest());
        }

        return $this->tablesBody;
    }

    public function getTablesWebCache(): Cache
    {
        if ($this->tablesWebCache === null) {
            $this->tablesWebCache = new Cache(
                $this->getCurrentWebVersion(),
                $this->getDirs(),
                $this->getRequest(),
                $this->getGit(),
                $this->getHtmlHelper()->isInProduction(),
                Cache::TABLES
            );
        }

        return $this->tablesWebCache;
    }

    public function getCssFiles(): CssFiles
    {
        if ($this->cssFiles === null) {
            $this->cssFiles = new CssFiles($this->getDirs(), $this->getHtmlHelper()->isInProduction());
        }

        return $this->cssFiles;
    }

    public function getJsFiles(): JsFiles
    {
        if ($this->jsFiles === null) {
            $this->jsFiles = new JsFiles($this->getConfiguration()->getDirs(), $this->getHtmlHelper()->isInProduction());
        }

        return $this->jsFiles;
    }

    public function getDirs(): Dirs
    {
        return $this->getConfiguration()->getDirs();
    }

    public function getWebFiles(): WebFiles
    {
        if ($this->webFiles === null) {
            $this->webFiles = new WebFiles($this->getDirs());
        }

        return $this->webFiles;
    }

    public function getCookiesService(): CookiesService
    {
        if ($this->cookiesService === null) {
            $this->cookiesService = new CookiesService();
        }

        return $this->cookiesService;
    }

    public function getNow(): \DateTimeImmutable
    {
        if ($this->now === null) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $this->now = new \DateTimeImmutable();
        }

        return $this->now;
    }

    public function getPassWebCache(): Cache
    {
        if ($this->passWebCache === null) {
            $this->passWebCache = new Cache(
                $this->getCurrentWebVersion(),
                $this->getDirs(),
                $this->getRequest(),
                $this->getGit(),
                $this->getHtmlHelper()->isInProduction(),
                Cache::PASS
            );
        }

        return $this->passWebCache;
    }

    public function getPassedWebCache(): Cache
    {
        if ($this->passedWebCache === null) {
            $this->passedWebCache = new Cache(
                $this->getCurrentWebVersion(),
                $this->getDirs(),
                $this->getRequest(),
                $this->getGit(),
                $this->getHtmlHelper()->isInProduction(),
                Cache::PASSED
            );
        }

        return $this->passedWebCache;
    }

    public function getPassBody(): PassBody
    {
        if ($this->passBody === null) {
            $this->passBody = new PassBody($this->getPass());
        }

        return $this->passBody;
    }

    public function getDebugContactsBody(): DebugContactsBody
    {
        if ($this->debugContactsBody === null) {
            $this->debugContactsBody = new DebugContactsBody();
        }

        return $this->debugContactsBody;
    }

    public function getPass(): Pass
    {
        if ($this->pass === null) {
            $this->pass = new Pass($this->getConfiguration(), $this->getUsagePolicy());
        }

        return $this->pass;
    }

    public function getUsagePolicy(): UsagePolicy
    {
        if ($this->usagePolicy === null) {
            $this->usagePolicy = new UsagePolicy(
                StringTools::toVariableName($this->getConfiguration()->getWebName()),
                $this->getRequest(),
                $this->getCookiesService()
            );
        }

        return $this->usagePolicy;
    }

    public function getPdfBody(): PdfBody
    {
        if ($this->pdfBody === null) {
            $this->pdfBody = new PdfBody($this->getDirs());
        }

        return $this->pdfBody;
    }

    public function getEmptyMenu(): EmptyMenu
    {
        return new EmptyMenu(
            $this->getConfiguration(),
            $this->getWebVersions(),
            $this->getCurrentWebVersion(),
            $this->getRequest()
        );
    }

    public function getDummyWebCache(): DummyWebCache
    {
        return new DummyWebCache(
            $this->getCurrentWebVersion(),
            $this->getDirs(),
            $this->getRequest(),
            $this->getGit(),
            $this->getHtmlHelper()->isInProduction(),
            'empty'
        );
    }
}