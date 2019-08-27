<?php declare(strict_types=1);

namespace DrdPlus\RulesSkeleton\Web;

class EmptyMenu extends Menu
{
    public function getValue(): string
    {
        return '';
    }

}