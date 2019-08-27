<?php declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\Request;

class CalculatorRequest extends Request
{
    public const DELETE_HISTORY = 'delete_history';

    public function isRequestedHistoryDeletion(): bool
    {
        return (bool)$this->getValueFromPost(static::DELETE_HISTORY);
    }
}