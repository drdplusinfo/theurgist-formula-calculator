<?php declare(strict_types=1);

namespace Granam\ScalarEnum\Exceptions;

/**
 * Cloning is prohibited for enum.
 *
 * Class CanNotBeCloned
 * @package Granam\Scalar\Exceptions
 */
class CanNotBeCloned extends \LogicException implements Logic
{

}
