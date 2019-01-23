<?php
namespace Doctrineum\Tests\Scalar\Helpers\EnumTypes;

use Doctrineum\Scalar\ScalarEnumType;

class EnumWithSubNamespaceType extends ScalarEnumType
{
    public const WITH_SUB_NAMESPACE = 'with_sub_namespace';

    public function getName(): string
    {
        return self::WITH_SUB_NAMESPACE;
    }
}