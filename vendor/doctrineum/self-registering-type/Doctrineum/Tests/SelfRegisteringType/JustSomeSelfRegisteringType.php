<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace Doctrineum\Tests\SelfRegisteringType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrineum\SelfRegisteringType\AbstractSelfRegisteringType;

class JustSomeSelfRegisteringType extends AbstractSelfRegisteringType
{
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return 'foo';
    }

    public const JUST_SOME_SELF_REGISTERING = 'just_some_self_registering';

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::JUST_SOME_SELF_REGISTERING;
    }

}