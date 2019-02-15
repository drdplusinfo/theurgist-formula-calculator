<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Measurements\Experiences;

use DrdPlus\Tables\Measurements\Experiences\Experiences;
use DrdPlus\Tables\Measurements\Measurement;
use Granam\Tests\ExceptionsHierarchy\Exceptions\AbstractExceptionsHierarchyTest;

class TablesMeasurementsExperiencesExceptionsHierarchyTest extends AbstractExceptionsHierarchyTest
{
    /**
     * @return string
     */
    protected function getTestedNamespace(): string
    {
        $reflection = new \ReflectionClass(Experiences::class);

        return $reflection->getNamespaceName();
    }

    /**
     * @return string
     */
    protected function getRootNamespace(): string
    {
        $reflection = new \ReflectionClass(Measurement::class);

        return $reflection->getNamespaceName();
    }

}