<?php
declare(strict_types = 1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace Granam\Boolean;

use Granam\Boolean\Tools\ToBoolean;
use Granam\Scalar\Scalar;

class Boolean extends Scalar implements BooleanInterface
{

    /**
     * @param mixed $value
     * @param bool $strict = true On FALSE suppresses raising of an exception on NULL
     */
    public function __construct($value, bool $strict = true)
    {
        parent::__construct(ToBoolean::toBoolean($value, $strict));
    }

    public function getValue(): bool
    {
        return parent::getValue();
    }

}