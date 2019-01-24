<?php
namespace Doctrineum\Tests\Scalar\Helpers;

use Doctrineum\Scalar\ScalarEnum;

class TestInheritedScalarEnum extends ScalarEnum
{
    public function __construct($enumValue)
    {
        parent::__construct($enumValue);
    }
}