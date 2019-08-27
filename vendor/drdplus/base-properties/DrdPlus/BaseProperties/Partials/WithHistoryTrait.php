<?php declare(strict_types=1);

namespace DrdPlus\BaseProperties\Partials;

use Granam\Tools\ValueDescriber;

trait WithHistoryTrait
{
    protected $history = [];

    /**
     * Gives array of modifications and result values, from very first to current value.
     * Order of historical values is from oldest as first to newest as last.
     * Warning: history is NOT persisted.
     *
     * @return array
     */
    public function getHistory(): array
    {
        return $this->history;
    }

    protected function noticeChange(): void
    {
        $changingCall = $this->findChangingCall(); // find a last call outside of this class (that causing current change)
        $this->history[] = [
            'changeBy' => [
                'name' => $this->formatToSentence($changingCall['function']),
                'with' => $this->extractArgumentsDescription($changingCall['args']),
            ],
            'result' => $this->getValue(),
        ];
    }

    /**
     * @return array
     */
    protected function findChangingCall(): array
    {
        /** @var array $call */
        foreach (\debug_backtrace() as $call) {
            if ((!\array_key_exists('object', $call) || $call['object'] !== $this)
                && (!\array_key_exists('class', $call)
                    || (!\in_array($call['class'], [__CLASS__, \get_class($this)], true))
                )
            ) {
                return $call;
            }
        }

        // @codeCoverageIgnoreStart
        return ['function' => '', 'args' => []];
        // @codeCoverageIgnoreEnd
    }

    /**
     * @param string $string
     * @return string
     */
    protected function formatToSentence(string $string): string
    {
        \preg_match_all('~[[:upper:]]?[[:lower:]]*~', $string, $matches);
        $captures = \array_filter($matches[0], function ($capture) {
            return $capture !== '';
        });

        return \implode(' ', array_map('lcfirst', $captures));
    }

    /**
     * @param array $arguments
     * @return string
     */
    private function extractArgumentsDescription(array $arguments): string
    {
        $descriptions = [];
        foreach ($arguments as $argument) {
            $descriptions[] = ValueDescriber::describe($argument);
        }

        return \implode(',', $descriptions);
    }

    protected function adoptHistory($numberWithHistory): void
    {
        /** @var WithHistoryTrait $numberWithHistory */
        // previous history FIRST, current after
        $this->history = array_merge($numberWithHistory->getHistory(), $this->getHistory());
    }
}