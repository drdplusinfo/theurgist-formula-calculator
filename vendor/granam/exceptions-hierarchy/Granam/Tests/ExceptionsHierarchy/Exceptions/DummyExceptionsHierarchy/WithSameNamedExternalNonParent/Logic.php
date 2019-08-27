<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions\DummyExceptionsHierarchy\WithSameNamedExternalNonParent;

interface Logic extends Exception, \Granam\Tests\ExceptionsHierarchy\Exceptions\DummyExceptionsHierarchy\WithSameNamedParent\Logic
{

}
