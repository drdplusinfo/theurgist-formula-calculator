<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Theurgist;

use DrdPlus\Tables\Partials\AbstractTable;
use DrdPlus\Codes\Theurgist\AbstractTheurgistCode;
use DrdPlus\Tables\Tables;
use DrdPlus\Tables\Theurgist\Demons\DemonParameters\DemonCapacity;
use DrdPlus\Tables\Theurgist\Spells\FormulasTable;
use DrdPlus\Tables\Theurgist\Spells\ModifiersTable;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\Partials\CastingParameter;
use DrdPlus\Tables\Theurgist\Spells\SpellParameters\SpellAttack;
use DrdPlus\Tables\Theurgist\Spells\SpellTrait;
use DrdPlus\Tables\Theurgist\Spells\SpellTraitsTable;
use DrdPlus\Tests\Tables\TableTest;
use Granam\String\StringTools;

abstract class AbstractTheurgistTableTest extends TableTest
{
    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_can_get_every_mandatory_parameter(): void
    {
        if (count($this->getMandatoryParameters()) === 0) {
            self::assertGreaterThan(
                0,
                count($this->getOptionalParameters()),
                'At least some optional parameter was expected when no parameter is mandatory'
            );
            return;
        }
        foreach ($this->getMandatoryParameters() as $mandatoryParameter) {
            $this->I_can_get_mandatory_parameter($mandatoryParameter, $this->getMainCodeClass());
        }
    }

    /**
     * @return array|string[]
     */
    abstract protected function getMandatoryParameters(): array;

    abstract protected function getMainCodeClass(): string;

    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_can_get_every_optional_parameter()
    {
        if (count($this->getOptionalParameters()) === 0) {
            self::assertGreaterThan(
                0,
                count($this->getMandatoryParameters()),
                'At least some mandatory parameter was expected when no parameter is optional'
            );
            return;
        }
        foreach ($this->getOptionalParameters() as $optionalParameter) {
            $this->I_can_get_optional_parameter($optionalParameter, $this->getMainCodeClass());
        }
    }

    abstract protected function getOptionalParameters(): array;

    /**
     * @param string $profile
     * @return string
     */
    protected function reverseProfileGender(string $profile): string
    {
        $oppositeProfile = str_replace('venus', 'mars', $profile, $countOfReplaced);
        if ($countOfReplaced === 1) {
            return $oppositeProfile;
        }
        $oppositeProfile = str_replace('mars', 'venus', $profile, $countOfReplaced);
        self::assertSame(1, $countOfReplaced);

        return $oppositeProfile;
    }

    /**
     * @param AbstractTable $table
     * @param string $formulaName
     * @param string $parameterName
     * @return mixed
     */
    protected function getValueFromTable(AbstractTable $table, string $formulaName, string $parameterName)
    {
        return $table->getIndexedValues()[$formulaName][$parameterName];
    }

    /**
     * @param string $mandatoryParameter
     * @param string|AbstractTheurgistCode $codeClass
     * @throws \ReflectionException
     */
    protected function I_can_get_mandatory_parameter(string $mandatoryParameter, string $codeClass): void
    {
        $getMandatoryParameter = StringTools::assembleGetterForName($mandatoryParameter);
        $parameterClass = $this->assembleParameterClassName($mandatoryParameter);
        $sut = $this->createSut();
        foreach ($codeClass::getPossibleValues() as $codeValue) {
            $expectedParameterValue = $this->getValueFromTable($sut, $codeValue, $mandatoryParameter);
            $expectedParameterObject = $this->createParameter($parameterClass, $expectedParameterValue);
            $parameterObject = $sut->$getMandatoryParameter($codeClass::getIt($codeValue));
            self::assertEquals($expectedParameterObject, $parameterObject);
        }
    }

    private function createSut(): AbstractTable
    {
        $sutClass = self::getSutClass();
        return new $sutClass(Tables::getIt());
    }

    private function createParameter(string $parameterClass, $parameterValue)
    {
        if (is_a($parameterClass, CastingParameter::class, true)) {
            return new $parameterClass($parameterValue, Tables::getIt());
        } else {
            return new $parameterClass($parameterValue);
        }
    }

    /**
     * @param string $parameter
     * @return string
     * @throws \ReflectionException
     */
    protected function assembleParameterClassName(string $parameter): string
    {
        $basename = StringTools::snakeCaseToCamelCase($parameter);
        if (strpos($parameter, 'demon_') === 0) {
            $namespace = (new \ReflectionClass(DemonCapacity::class))->getNamespaceName();
        } else {
            $namespace = (new \ReflectionClass(SpellAttack::class))->getNamespaceName();
        }
        return $namespace . '\\' . $basename;
    }

    /**
     * @param string $optionalParameter
     * @param string|AbstractTheurgistCode $codeClass
     * @throws \ReflectionException
     */
    protected function I_can_get_optional_parameter(string $optionalParameter, string $codeClass)
    {
        $getOptionalParameter = StringTools::assembleGetterForName($optionalParameter);
        $parameterClass = $this->assembleParameterClassName($optionalParameter);
        $sut = $this->createSut();
        foreach ($codeClass::getPossibleValues() as $codeValue) {
            $expectedParameterValue = $this->getValueFromTable($sut, $codeValue, $optionalParameter);
            $expectedParameterObject = count($expectedParameterValue) !== 0
                ? $this->createParameter($parameterClass, $expectedParameterValue)
                : null;
            $parameterObject = $sut->$getOptionalParameter($codeClass::getIt($codeValue));
            self::assertEquals($expectedParameterObject, $parameterObject);
        }
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function Every_parameter_can_be_get_by_getter()
    {
        $reflection = new \ReflectionClass(static::getSutClass());
        $constants = [];
        foreach ($reflection->getReflectionConstants() as $reflectionConstant) {
            if (!$reflectionConstant->isPublic()) {
                continue;
            }
            $constants[] = $reflectionConstant->getValue();
        }
        $getRowsHeader = $reflection->getMethod('getRowsHeader');
        $getRowsHeader->setAccessible(true);
        $rowsHeader = $getRowsHeader->invoke($this->createSut()); // constants used for first column heading
        foreach ($constants as $constant) {
            if (in_array($constant, $rowsHeader)) {
                continue;
            }
            if ($constant === FormulasTable::PROFILES) {
                $constant = 'profile_codes';
            } elseif ($constant === FormulasTable::MODIFIERS) {
                $constant = 'modifier_codes';
            } elseif ($constant === SpellTraitsTable::FORMULAS) {
                $constant = 'formula_codes';
            } elseif ($constant === ModifiersTable::FORMS) {
                $constant = 'form_codes';
            } elseif ($constant === ModifiersTable::PARENT_MODIFIERS) {
                $constant = 'parent_modifier_codes';
            } elseif ($constant === ModifiersTable::CHILD_MODIFIERS) {
                $constant = 'child_modifier_codes';
            }
            $getter = StringTools::assembleGetterForName($constant);
            self::assertTrue(
                $reflection->hasMethod($getter),
                sprintf('Missing getter %s in %s', $getter, static::getSutClass())
            );
        }
    }
}