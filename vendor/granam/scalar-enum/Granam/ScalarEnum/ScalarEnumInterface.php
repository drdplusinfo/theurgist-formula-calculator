<?php
declare(strict_types=1);

namespace Granam\ScalarEnum;

use Granam\Scalar\ScalarInterface;

interface ScalarEnumInterface extends ScalarInterface
{
    /**
     * @param ScalarInterface|string|bool|int|float|null $enum
     * @param bool $sameClassOnly = true
     * @return bool
     */
    public function is($enum, bool $sameClassOnly = true): bool;
}