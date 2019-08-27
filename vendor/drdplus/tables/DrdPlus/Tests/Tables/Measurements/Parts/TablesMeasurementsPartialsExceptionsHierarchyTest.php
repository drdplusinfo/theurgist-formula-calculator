<?php declare(strict_types = 1);

declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Measurements\Partials;

use DrdPlus\Tables\Measurements\Measurement;
use DrdPlus\Tables\Measurements\Partials\AbstractMeasurement;
use Granam\Tests\ExceptionsHierarchy\Exceptions\AbstractExceptionsHierarchyTest;

class TablesMeasurementsPartialsExceptionsHierarchyTest extends AbstractExceptionsHierarchyTest
{
    /**
     * @return string
     */
    protected function getTestedNamespace(): string
    {
        $abstractTableReflection = new \ReflectionClass(AbstractMeasurement::class);

        return $abstractTableReflection->getNamespaceName();
    }

    /**
     * @return string
     */
    protected function getRootNamespace(): string
    {
        $measurementReflection = new \ReflectionClass(Measurement::class);

        return $measurementReflection->getNamespaceName();
    }
}