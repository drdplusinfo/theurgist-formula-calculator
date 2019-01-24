<?php
namespace Granam\Tests\ExceptionsHierarchy\Exceptions\DummyExceptionsHierarchy\GreedyException;

class BothRuntimeAndLogicTagged extends \Exception implements Runtime, Logic
{

}
