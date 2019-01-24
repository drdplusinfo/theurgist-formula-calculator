<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton;

use DrdPlus\RulesSkeleton\Request;

class CalculatorRequest extends Request
{
    public const DELETE_HISTORY = 'delete_history';
    public const REMEMBER_CURRENT = 'remember_current';

    public function isRequestedHistoryDeletion(): bool
    {
        return (bool)$this->getValueFromPost(static::DELETE_HISTORY);
    }

    public function isRequestedRememberCurrent(): bool
    {
        return (bool)$this->getValueFromGet(static::REMEMBER_CURRENT);
    }
}