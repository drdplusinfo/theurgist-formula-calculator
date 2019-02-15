<?php
declare(strict_types=1);

namespace DrdPlus\Tests\BaseProperties;

use DrdPlus\BaseProperties\BaseProperty;
use DrdPlus\BaseProperties\Property;
use DrdPlus\Tests\BaseProperties\Partials\AbstractIntegerPropertyTest;

abstract class BasePropertyTest extends AbstractIntegerPropertyTest
{
    protected function getGenericGroupsPropertyClassNames(): array
    {
        return [Property::class, BaseProperty::class];
    }
}