<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions\DummyExceptionsHierarchy\BrokenLineage\RuntimeTagWithoutParent;

interface Logic extends Exception, \Granam\Tests\ExceptionsHierarchy\Exceptions\DummyExceptionsHierarchy\BrokenLineage\Logic
{

}
