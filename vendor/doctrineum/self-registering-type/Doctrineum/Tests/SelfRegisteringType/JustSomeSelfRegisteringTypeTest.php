<?php
declare(strict_types = 1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace Doctrineum\Tests\SelfRegisteringType;

class JustSomeSelfRegisteringTypeTest extends AbstractSelfRegisteringTypeTest
{
    /**
     * @return mixed
     */
    protected function getTypeClass(): string
    {
        return JustSomeSelfRegisteringType::class;
    }
}