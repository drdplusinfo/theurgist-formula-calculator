<?php
namespace Granam\Tests\ExceptionsHierarchy\Exceptions\DummyExceptionsHierarchy\BrokenLineage\RuntimeTagWithoutParent;

interface Runtime extends Exception /* missing parent Runtime here */
{

}
