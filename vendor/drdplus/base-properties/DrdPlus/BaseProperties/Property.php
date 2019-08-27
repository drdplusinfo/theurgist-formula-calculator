<?php declare(strict_types=1);

namespace DrdPlus\BaseProperties;

use DrdPlus\Codes\Code;
use Granam\Scalar\ScalarInterface;

interface Property extends ScalarInterface
{

    /**
     * @return Code
     */
    public function getCode();

    /**
     * @return int|float|bool|string
     */
    public function getValue();
}