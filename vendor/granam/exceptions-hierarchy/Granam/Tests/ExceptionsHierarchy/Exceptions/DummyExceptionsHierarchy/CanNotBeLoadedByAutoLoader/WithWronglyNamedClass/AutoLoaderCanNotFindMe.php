<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions\DummyExceptionsHierarchy\CanNotBeLoadedByAutoLoader\WithWronglyNamedClass;

class AutoLoaderCanNotFindMeBecauseSomeoneNamedMeWrongly extends \RuntimeException implements Runtime
{

}