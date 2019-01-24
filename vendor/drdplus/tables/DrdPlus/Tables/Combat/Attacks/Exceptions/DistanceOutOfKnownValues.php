<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tables\Combat\Attacks\Exceptions;

class DistanceOutOfKnownValues extends \OutOfRangeException implements Logic
{

}