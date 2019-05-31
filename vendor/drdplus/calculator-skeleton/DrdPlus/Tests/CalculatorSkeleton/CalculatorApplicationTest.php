<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\CalculatorSkeleton\CalculatorApplication;
use DrdPlus\CalculatorSkeleton\CalculatorServicesContainer;
use DrdPlus\CalculatorSkeleton\History;
use DrdPlus\CalculatorSkeleton\Memory;
use DrdPlus\RulesSkeleton\Request;
use DrdPlus\Tests\CalculatorSkeleton\Partials\AbstractCalculatorContentTest;
use Mockery\MockInterface;

/**
 * @backupGlobals enabled
 */
class CalculatorApplicationTest extends AbstractCalculatorContentTest
{
    /**
     * @test
     * @dataProvider provideHistoryDeletionRequest
     * @param bool $historyDeletionRequested
     */
    public function History_and_memory_is_solved_before_run(bool $historyDeletionRequested): void
    {
        $request = $this->createRequestMock($historyDeletionRequested);
        $request->shouldReceive('getValuesFromGet')
            ->atLeast()->once()
            ->andReturn($valuesFromGet = ['foo' => 'bar']);
        $memory = $this->createMemoryMock();
        $memory->shouldReceive('saveMemory')
            ->atLeast()->once()
            ->with($valuesFromGet);
        if ($historyDeletionRequested) {
            $memory->shouldReceive('deleteMemory')
                ->atLeast()->once();
        }
        $history = $this->createHistoryMock();
        $history->shouldReceive('saveHistory')
            ->atLeast()->once()
            ->with($valuesFromGet);
        if ($historyDeletionRequested) {
            $history->shouldReceive('deleteHistory')
                ->atLeast()->once();
        }
        $calculatorServicesContainer = $this->createServicesContainerMock($request, $memory, $history);
        $sutClass = static::getSutClass();
        /** @var CalculatorApplication $calculatorApplication */
        $calculatorApplication = new $sutClass($calculatorServicesContainer);
        \ob_start();
        $calculatorApplication->run();
        \ob_end_clean();
    }

    public function provideHistoryDeletionRequest(): array
    {
        return [
            'history deletion not requested' => [false],
            'history deletion requested' => [true],
        ];
    }

    /**
     * @param bool $isRequestedHistoryDeletion
     * @return Request|MockInterface
     */
    private function createRequestMock(bool $isRequestedHistoryDeletion): Request
    {
        $request = $this->mockery(Request::class);
        $request->shouldReceive('isRequestedHistoryDeletion')
            ->atLeast()->once()
            ->andReturn($isRequestedHistoryDeletion);
        $request->makePartial();
        return $request;
    }

    /**
     * @return Memory|MockInterface
     */
    private function createMemoryMock(): Memory
    {
        return $this->mockery(Memory::class);
    }

    /**
     * @return History|MockInterface
     */
    private function createHistoryMock(): History
    {
        return $this->mockery(History::class);
    }

    /**
     * @param Request $request
     * @param Memory $memory
     * @param History $history
     * @return CalculatorServicesContainer|MockInterface
     */
    private function createServicesContainerMock(Request $request, Memory $memory, History $history): CalculatorServicesContainer
    {
        $calculatorServicesContainer = $this->mockery($this->getServicesContainerClass());
        $calculatorServicesContainer->shouldReceive('getRequest')
            ->atLeast()->once()
            ->andReturn($request);
        $calculatorServicesContainer->shouldReceive('getMemory')
            ->atLeast()->once()
            ->andReturn($memory);
        $calculatorServicesContainer->shouldReceive('getHistory')
            ->atLeast()->once()
            ->andReturn($history);
        $calculatorServicesContainer->shouldReceive('getConfiguration')
            ->andReturn($this->getConfiguration());
        $calculatorServicesContainer->shouldReceive('getHtmlHelper')
            ->andReturn($this->getHtmlHelper());
        $calculatorServicesContainer->makePartial();
        return $calculatorServicesContainer;
    }
}