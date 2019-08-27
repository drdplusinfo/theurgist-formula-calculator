<?php declare(strict_types=1);

namespace DrdPlus\Calculators\Theurgist;

use DrdPlus\RulesSkeleton\RoutedDirs;

class FormulaDirs extends RoutedDirs
{
    public function getWebRoot(): string
    {
        $webRoot = $this->getProjectRoot() . '/web/web';
        if ($this->getRelativeWebRoot() !== '') {
            $webRoot .= '/' . $this->getRelativeWebRoot();
        }
        return $webRoot;
    }

}