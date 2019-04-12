<?php
namespace DrdPlus\Tests\Tables\Theurgist\Demons;

use DrdPlus\Codes\Theurgist\DemonTraitCode;
use DrdPlus\Tables\Theurgist\Demons\DemonTraitsTable;
use DrdPlus\Tests\Tables\Theurgist\AbstractTheurgistTableTest;

class DemonTraitsTableTest extends AbstractTheurgistTableTest
{
    protected function getMandatoryParameters(): array
    {
        return [DemonTraitsTable::REALM, DemonTraitsTable::REALMS_AFFECTION];
    }

    protected function getMainCodeClass(): string
    {
        return DemonTraitCode::class;
    }

    protected function getOptionalParameters(): array
    {
        return [];
    }

}
