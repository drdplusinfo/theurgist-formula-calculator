<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties;

use DrdPlus\Codes\Code;
use Granam\Scalar\ScalarInterface;

/**
 * @method static Property getIt(int | ScalarInterface $value)
 */
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