<?php
declare(strict_types=1);

namespace DrdPlus\RulesSkeleton\Web;

use DrdPlus\RulesSkeleton\Dirs;

class WebFiles extends \Granam\WebContentBuilder\Web\WebFiles
{
    public function __construct(Dirs $dirs)
    {
        parent::__construct($dirs->getWebRoot());
    }
}