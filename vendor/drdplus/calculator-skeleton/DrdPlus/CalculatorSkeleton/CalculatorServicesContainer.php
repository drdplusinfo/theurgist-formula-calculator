<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

use DrdPlus\CalculatorSkeleton\Web\HistoryDeletionBody;
use DrdPlus\CalculatorSkeleton\Web\CalculatorDebugContactsBody;
use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\RulesSkeleton\Request;
use DrdPlus\RulesSkeleton\ServicesContainer;
use DrdPlus\RulesSkeleton\Web\RulesMainContent;
use Granam\Git\Git;

/**
 * @method CalculatorConfiguration getConfiguration()
 */
class CalculatorServicesContainer extends ServicesContainer
{
    /** @var CalculatorRequest */
    private $calculatorRequest;

    /** @var Memory */
    private $memory;

    /** @var CurrentValues */
    private $currentValues;

    /** @var History */
    private $history;

    /** @var CalculatorContent */
    private $calculatorContent;

    /** @var RulesMainContent */
    private $calculatorWebContent;

    /** @var GitReader */
    private $gitReader;

    /** @var HistoryDeletionBody */
    private $historyDeletionBody;

    /** @var CalculatorDebugContactsBody */
    private $calculatorDebugContactsBody;

    public function __construct(CalculatorConfiguration $calculatorConfiguration, HtmlHelper $htmlHelper)
    {
        parent::__construct($calculatorConfiguration, $htmlHelper);
    }

    public function getRulesMainBodyParameters(): array
    {
        return [
            'historyDeletion' => $this->getHistoryDeletionBody(),
            'calculatorDebugContacts' => $this->getCalculatorDebugContactsBody(),
        ];
    }

    public function getHistoryDeletionBody(): HistoryDeletionBody
    {
        if ($this->historyDeletionBody === null) {
            $this->historyDeletionBody = new HistoryDeletionBody();
        }

        return $this->historyDeletionBody;
    }

    public function getCalculatorDebugContactsBody(): CalculatorDebugContactsBody
    {
        if ($this->calculatorDebugContactsBody === null) {
            $this->calculatorDebugContactsBody = new CalculatorDebugContactsBody();
        }

        return $this->calculatorDebugContactsBody;
    }

    /**
     * @return Request|CalculatorRequest
     */
    public function getRequest(): Request
    {
        if ($this->calculatorRequest === null) {
            $this->calculatorRequest = new CalculatorRequest($this->getBotParser());
        }

        return $this->calculatorRequest;
    }

    public function getMemory(): Memory
    {
        if ($this->memory === null) {
            $this->memory = new Memory(
                $this->getCookiesService(),
                $this->getRequest()->isRequestedHistoryDeletion(),
                $this->getRequest()->getValuesFromGet(),
                $this->getRequest()->isRequestedRememberCurrent(),
                $this->getConfiguration()->getCookiesPostfix(),
                $this->getConfiguration()->getCookiesTtl()
            );
        }

        return $this->memory;
    }

    public function getCurrentValues(): CurrentValues
    {
        if ($this->currentValues === null) {
            $this->currentValues = new CurrentValues($this->getRequest()->getValuesFromGet(), $this->getMemory());
        }

        return $this->currentValues;
    }

    public function getHistory(): History
    {
        if ($this->history === null) {
            $this->history = new History(
                $this->getCookiesService(),
                $this->getRequest()->isRequestedHistoryDeletion(),
                $this->getRequest()->getValuesFromGet(),
                $this->getRequest()->isRequestedRememberCurrent(),
                $this->getConfiguration()->getCookiesPostfix(),
                $this->getConfiguration()->getCookiesTtl()
            );
        }

        return $this->history;
    }

    public function getCalculatorContent(): CalculatorContent
    {
        if ($this->calculatorContent === null) {
            $this->calculatorContent = new CalculatorContent(
                $this->getRulesMainContent(),
                $this->getMenu(),
                $this->getCurrentWebVersion(),
                $this->getPassedWebCache()
            );
        }

        return $this->calculatorContent;
    }

    public function getRulesMainContent(): RulesMainContent
    {
        if ($this->calculatorWebContent === null) {
            $this->calculatorWebContent = new RulesMainContent(
                $this->getHtmlHelper(),
                $this->getHead(),
                $this->getRulesMainBody()
            );
        }

        return $this->calculatorWebContent;
    }

    public function getGit(): Git
    {
        if ($this->gitReader === null) {
            $this->gitReader = new GitReader();
        }

        return $this->gitReader;
    }
}