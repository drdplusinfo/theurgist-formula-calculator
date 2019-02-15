<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Measurements\Time\Exceptions;

class NotApplicableOnShorterThanDay extends \InvalidArgumentException implements Logic
{

}