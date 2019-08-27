<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions\DummyExceptionsHierarchy\BrokenLineage\ExceptionTagWithoutParent;

interface Exception /* missing parent Exception here */
{

}
