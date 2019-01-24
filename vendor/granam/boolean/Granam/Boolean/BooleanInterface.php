<?php
namespace Granam\Boolean;

use Granam\Scalar\ScalarInterface;

interface BooleanInterface extends ScalarInterface
{
    /**
     * @return bool
     */
    public function getValue(): bool;
}
