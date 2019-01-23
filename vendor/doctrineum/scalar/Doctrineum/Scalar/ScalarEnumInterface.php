<?php
declare(strict_types = 1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace Doctrineum\Scalar;

use Granam\Scalar\ScalarInterface;

interface ScalarEnumInterface extends ScalarInterface
{
    /**
     * @param ScalarEnumInterface $enum
     * @param bool $sameClassOnly = true
     * @return bool
     */
    public function is(ScalarEnumInterface $enum, bool $sameClassOnly = true): bool;
}