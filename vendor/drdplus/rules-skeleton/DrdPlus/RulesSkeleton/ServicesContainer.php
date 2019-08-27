<?php declare(strict_types=1);

namespace DrdPlus\RulesSkeleton;

use DeviceDetector\Parser\Bot;
use DrdPlus\RulesSkeleton\Web\EmptyMenu;
use DrdPlus\RulesSkeleton\Web\Head;
use DrdPlus\RulesSkeleton\Web\Menu;
use DrdPlus\RulesSkeleton\Web\NotFoundContent;
use DrdPlus\RulesSkeleton\Web\Pass;
use DrdPlus\RulesSkeleton\Web\PassContent;
use DrdPlus\RulesSkeleton\Web\RulesMainContent;
use DrdPlus\RulesSkeleton\Web\TablesContent;
use DrdPlus\RulesSkeleton\Web\WebFiles;
use DrdPlus\RulesSkeleton\Web\WebPartsContainer;
use DrdPlus\RulesSkeleton\Web\WebRootProvider;
use DrdPlus\WebVersions\WebVersions;
use Granam\Git\Git;
use Granam\Strict\Object\StrictObject;
use Granam\String\StringTools;
use Granam\WebContentBuilder\Web\CssFiles;
use Granam\WebContentBuilder\Web\HtmlContentInterface;
use Granam\WebContentBuilder\Web\JsFiles;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;

class ServicesContainer extends StrictObject
{

    /** @var CurrentWebVersion */
    private $currentWebVersion;
    /** @var WebVersions */
    private $webVersions;
    /** @var Git */
    private $git;
    /** @var Configuration */
    private $configuration;
    /** @var HomepageDetector */
    private $homepageDetector;
    /** @var HtmlHelper */
    private $htmlHelper;
    /** @var Head */
    private $head;
    /** @var Menu */
    private $menu;
    /** @var Cache */
    private $tablesWebCache;
    /** @var CssFiles */
    private $cssFiles;
    /** @var JsFiles */
    private $jsFiles;
    /** @var WebFiles */
    private $webFiles;
    /** @var WebRootProvider */
    private $webRootProvider;
    /** @var PathProvider */
    private $pathProvider;
    /** @var Request */
    private $request;
    /** @var Environment */
    private $environment;
    /** @var ContentIrrelevantParametersFilter */
    private $contentIrrelevantParametersFilter;
    /** @var Bot */
    private $botParser;
    /** @var WebPartsContainer */
    private $webPartsContainer;
    /** @var RulesMainContent */
    private $rulesMainContent;
    /** @var RulesMainContent */
    private $tablesMainContent;
    /** @var HtmlContentInterface */
    private $rulesPdfWebContent;
    /** @var RulesMainContent */
    private $passContent;
    /** @var NotFoundContent */
    private $notFoundContent;
    /** @var CookiesService */
    private $cookiesService;
    /** @var \DateTimeImmutable */
    private $now;
    /** @var Cache */
    private $passWebCache;
    /** @var Cache */
    private $passedWebCache;
    /** @var Cache */
    private $notFoundCache;
    /** @var UsagePolicy */
    private $usagePolicy;
    /** @var Pass */
    private $pass;
    /** @var RulesUrlMatcher */
    private $rulesUrlMatcher;

    public function __construct(Configuration $configuration, HtmlHelper $htmlHelper)
    {
        $this->configuration = $configuration;
        $this->htmlHelper = $htmlHelper;
    }

    public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }

    public function getHomepageDetector(): HomepageDetector
    {
        if ($this->homepageDetector === null) {
            $this->homepageDetector = new HomepageDetector($this->getPathProvider());
        }
        return $this->homepageDetector;
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
            $this->request = Request::createFromGlobals($this->getBotParser(), $this->getEnvironment());
        }
        return $this->request;
    }

    public function getEnvironment(): Environment
    {
        if ($this->environment === null) {
            $this->environment = Environment::createFromGlobals();
        }
        return $this->environment;
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
                $this->getWebPartsContainer()->getRulesMainBody()
            );
        }
        return $this->rulesMainContent;
    }

    public function getWebPartsContainer(): WebPartsContainer
    {
        if ($this->webPartsContainer === null) {
            $this->webPartsContainer = new WebPartsContainer(
                $this->getPass(),
                $this->getWebFiles(),
                $this->getDirs(),
                $this->getHtmlHelper(),
                $this->getRequest()
            );
        }
        return $this->webPartsContainer;
    }

    public function getTablesContent(): TablesContent
    {
        if ($this->tablesMainContent === null) {
            $this->tablesMainContent = new TablesContent(
                $this->getHtmlHelper(),
                $this->getHeadForTables(),
                $this->getWebPartsContainer()->getTablesBody()
            );
        }
        return $this->tablesMainContent;
    }

    public function getPdfContent(): PdfContent
    {
        if ($this->rulesPdfWebContent === null) {
            $this->rulesPdfWebContent = new PdfContent($this->getWebPartsContainer()->getPdfBody());
        }
        return $this->rulesPdfWebContent;
    }

    public function getPassContent(): PassContent
    {
        if ($this->passContent === null) {
            $this->passContent = new PassContent(
                $this->getHtmlHelper(),
                $this->getHead(),
                $this->getWebPartsContainer()->getPassBody()
            );
        }
        return $this->passContent;
    }

    public function getNotFoundContent(): NotFoundContent
    {
        if ($this->notFoundContent === null) {
            $this->notFoundContent = new NotFoundContent(
                $this->getHtmlHelper(),
                $this->getHead(),
                $this->getWebPartsContainer()->getNotFoundBody()
            );
        }
        return $this->notFoundContent;
    }

    public function getHtmlHelper(): HtmlHelper
    {
        return $this->htmlHelper;
    }

    public function getMenu(): Menu
    {
        if ($this->menu === null) {
            $this->menu = new Menu($this->getConfiguration(), $this->getHomepageDetector());
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

    public function getTablesWebCache(): Cache
    {
        if ($this->tablesWebCache === null) {
            $this->tablesWebCache = new Cache(
                $this->getCurrentWebVersion(),
                $this->getDirs(),
                $this->getRequest(),
                $this->getContentIrrelevantParametersFilter(),
                $this->getGit(),
                $this->getHtmlHelper()->isInProduction(),
                Cache::TABLES
            );
        }
        return $this->tablesWebCache;
    }

    public function getContentIrrelevantParametersFilter(): ContentIrrelevantParametersFilter
    {
        if ($this->contentIrrelevantParametersFilter === null) {
            $this->contentIrrelevantParametersFilter = new ContentIrrelevantParametersFilter([Request::TRIAL]);
        }
        return $this->contentIrrelevantParametersFilter;
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
            $this->webFiles = new WebFiles($this->getWebRootProvider());
        }
        return $this->webFiles;
    }

    protected function getWebRootProvider(): WebRootProvider
    {
        if ($this->webRootProvider === null) {
            $this->webRootProvider = new WebRootProvider($this->createRoutedDirs($this->getDirs()));
        }
        return $this->webRootProvider;
    }

    protected function createRoutedDirs(Dirs $dirs): RoutedDirs
    {
        return new RoutedDirs($dirs->getProjectRoot(), $this->getPathProvider());
    }

    protected function getPathProvider(): PathProvider
    {
        if ($this->pathProvider === null) {
            $this->pathProvider = new PathProvider($this->getRulesUrlMatcher(), $this->getRequest()->getCurrentUrl());
        }
        return $this->pathProvider;
    }

    public function getRulesUrlMatcher(): RulesUrlMatcher
    {
        if ($this->rulesUrlMatcher === null) {
            $this->rulesUrlMatcher = new RulesUrlMatcher($this->createUrlMatcher());
        }
        return $this->rulesUrlMatcher;
    }

    private function createUrlMatcher(): UrlMatcherInterface
    {
        $yamlFileWithRoutes = $this->getYamlFileWithRoutes();
        if ($yamlFileWithRoutes === '') {
            return new DummyUrlMatcher();
        }
        $router = new \Symfony\Component\Routing\Router(
            new YamlFileLoader(new FileLocator([$this->getDirs()->getProjectRoot()])),
            $yamlFileWithRoutes,
            ['cache_dir' => $this->getPassWebCache()->getCacheDir() . '/router'],
            (new RequestContext())->fromRequest(\Symfony\Component\HttpFoundation\Request::createFromGlobals())
        );
        return $router->getMatcher();
    }

    protected function getYamlFileWithRoutes(): string
    {
        $yamlFileWithRoutes = $this->getConfiguration()->getYamlFileWithRoutes();
        if ($yamlFileWithRoutes !== '') {
            return $yamlFileWithRoutes;
        }
        $defaultYamlFileWithRoutes = $this->getDirs()->getProjectRoot() . '/' . $this->getConfiguration()->getDefaultYamlFileWithRoutes();
        if (!file_exists($defaultYamlFileWithRoutes)) {
            return '';
        }
        return $defaultYamlFileWithRoutes;
    }

    public function getCookiesService(): CookiesService
    {
        if ($this->cookiesService === null) {
            $this->cookiesService = new CookiesService($this->getRequest());
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
                $this->getContentIrrelevantParametersFilter(),
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
                $this->getContentIrrelevantParametersFilter(),
                $this->getGit(),
                $this->getHtmlHelper()->isInProduction(),
                Cache::PASSED
            );
        }
        return $this->passedWebCache;
    }

    public function getNotFoundCache(): Cache
    {
        if ($this->notFoundCache === null) {
            $this->notFoundCache = new Cache(
                $this->getCurrentWebVersion(),
                $this->getDirs(),
                $this->getRequest(),
                $this->getContentIrrelevantParametersFilter(),
                $this->getGit(),
                $this->getHtmlHelper()->isInProduction(),
                Cache::NOT_FOUND
            );
        }
        return $this->notFoundCache;
    }

    public function getPass(): Pass
    {
        if ($this->pass === null) {
            $this->pass = new Pass($this->getConfiguration(), $this->getUsagePolicy(), $this->getRequest());
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

    public function getEmptyMenu(): EmptyMenu
    {
        return new EmptyMenu(
            $this->getConfiguration(),
            $this->getHomepageDetector()
        );
    }

    public function getDummyWebCache(): DummyWebCache
    {
        return new DummyWebCache(
            $this->getCurrentWebVersion(),
            $this->getDirs(),
            $this->getRequest(),
            $this->getContentIrrelevantParametersFilter(),
            $this->getGit(),
            $this->getHtmlHelper()->isInProduction(),
            'empty'
        );
    }
}