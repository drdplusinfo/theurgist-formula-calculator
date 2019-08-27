<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions\DummyExceptionsHierarchy\LogicExceptionAsRuntimeException;

class IThoughIAmRuntimeException extends \LogicException implements Runtime
{

}
