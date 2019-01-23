<?php
namespace DrdPlus\Tests\Calculators\Theurgist;

use DrdPlus\Calculators\Theurgist\CurrentFormulaValues;
use DrdPlus\Calculators\Theurgist\FormulaServicesContainer;
use DrdPlus\CalculatorSkeleton\CurrentValues;
use DrdPlus\CalculatorSkeleton\Memory;
use DrdPlus\Codes\Theurgist\ModifierCode;
use DrdPlus\RulesSkeleton\Configuration;
use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\RulesSkeleton\ServicesContainer;
use DrdPlus\Tables\Tables;
use DrdPlus\Tests\CalculatorSkeleton\Partials\AbstractContentTestTrait;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;
use Granam\Number\NumberObject;
use Mockery\MockInterface;

class CurrentFormulaValuesTest extends AbstractContentTest
{
    use AbstractContentTestTrait;

    /**
     * @test
     */
    public function I_can_find_out_if_modifier_is_selected(): void
    {
        $currentFormulaValues = $this->createCurrentFormulaValues();
        self::assertFalse(
            $currentFormulaValues->isModifierSelected(ModifierCode::COLOR, [], 5),
            'No selected modifier provided so no is selected'
        );
        self::assertFalse(
            $currentFormulaValues->isModifierSelected(ModifierCode::COLOR, [5 => []], 5),
            'No selected modifier selection provided so no modifier is selected'
        );
        self::assertFalse(
            $currentFormulaValues->isModifierSelected(ModifierCode::COLOR, [5 => [ModifierCode::BREACH => ModifierCode::BREACH]], 5),
            'Another modifiers provided so color should not be selected'
        );
        self::assertTrue(
            $currentFormulaValues->isModifierSelected(ModifierCode::COLOR, [5 => [ModifierCode::COLOR => ModifierCode::COLOR]], 5)
        );
        self::assertFalse($currentFormulaValues->isModifierSelected(ModifierCode::COLOR, [5 => [ModifierCode::EXPLOSION => []]], 5));
        self::assertTrue($currentFormulaValues->isModifierSelected(ModifierCode::COLOR, [5 => [ModifierCode::COLOR => []]], 5));
    }

    /**
     * @test
     */
    public function I_can_format_number(): void
    {
        $formulaValues = $this->createCurrentFormulaValues();
        self::assertSame('+123', $formulaValues->formatNumber(new NumberObject(123)));
        self::assertSame('-456', $formulaValues->formatNumber(new NumberObject(-456)));
        self::assertSame('+0', $formulaValues->formatNumber(new NumberObject(0)));
    }

    private function createCurrentFormulaValues(): CurrentFormulaValues
    {
        return new CurrentFormulaValues(new CurrentValues([], $this->createMemory()), Tables::getIt());
    }

    /**
     * @return Memory|MockInterface
     */
    private function createMemory(): Memory
    {
        return $this->mockery(Memory::class);
    }

    /**
     * @param Configuration|null $configuration
     * @param HtmlHelper|null $htmlHelper
     * @return ServicesContainer|FormulaServicesContainer
     */
    protected function createServicesContainer(Configuration $configuration = null, HtmlHelper $htmlHelper = null): ServicesContainer
    {

        return new FormulaServicesContainer(
            $configuration ?? $this->getConfiguration(),
            $htmlHelper ?? $this->createHtmlHelper($this->getDirs())
        );
    }
}