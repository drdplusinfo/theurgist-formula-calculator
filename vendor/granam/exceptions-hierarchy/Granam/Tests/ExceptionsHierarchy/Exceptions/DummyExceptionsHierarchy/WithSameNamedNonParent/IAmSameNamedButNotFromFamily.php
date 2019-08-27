<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions\DummyExceptionsHierarchy\WithSameNamedNonParent;

class IAmSameNamedButNotFromFamily extends \LogicException implements Logic
{

}
