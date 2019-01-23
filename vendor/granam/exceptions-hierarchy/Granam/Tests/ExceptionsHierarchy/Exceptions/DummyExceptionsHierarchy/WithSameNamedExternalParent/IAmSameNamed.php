<?php
namespace Granam\Tests\ExceptionsHierarchy\Exceptions\DummyExceptionsHierarchy\WithSameNamedExternalParent;

/**
 * This class intentionally skips IAmHereForWithSameNamedExternalParent namespace part,

*
*@see \Granam\Tests\ExceptionsHierarchy\Exceptions\DummyExceptionsHierarchy\WithSameNamedExternalParent\Exception for who not
 */
class IAmSameNamed extends \Granam\Tests\ExceptionsHierarchy\Exceptions\DummyExceptionsHierarchy\WithSameNamedParent\Children\IAmSameNamed
    implements Logic
{

}
