<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions\DummyExceptionsHierarchy\WithSameNamedNonParent\Children;

class IAmSameNamedButNotFromFamily extends \LogicException implements Logic
{

}
