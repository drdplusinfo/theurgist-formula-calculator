<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions\DummyExceptionsHierarchy\WithSameNamedNonParent;

interface Logic extends Exception, \Granam\Tests\ExceptionsHierarchy\Exceptions\DummyExceptionsHierarchy\Logic
{

}
