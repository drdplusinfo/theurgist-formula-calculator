<?php
declare(strict_types=1);

namespace DrdPlus\Tables\History\Exceptions;

use DrdPlus\Tables\Partials\Exceptions\RequiredColumnNotFound;

class UnexpectedProfessionAndSkillTypeCombination extends RequiredColumnNotFound implements Logic
{

}