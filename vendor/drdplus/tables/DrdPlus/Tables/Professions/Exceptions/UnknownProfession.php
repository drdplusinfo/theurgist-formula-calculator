<?php
declare(strict_types=1);

namespace DrdPlus\Tables\Professions\Exceptions;

use DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound;

class UnknownProfession extends RequiredRowNotFound implements Logic
{

}