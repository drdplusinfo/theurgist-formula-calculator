<?php
namespace DrdPlus\Tests\TheurgistCalculator\Formulas;

use DrdPlus\Codes\Theurgist\ModifierCode;
use DrdPlus\Tables\Tables;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;
use DrdPlus\Theurgist\Spells\FormulasTable;
use DrdPlus\Theurgist\Spells\ModifiersTable;
use DrdPlus\Theurgist\Spells\SpellTraitsTable;
use DrdPlus\TheurgistCalculator\Formulas\FormulasController;
use Granam\Number\NumberObject;

class FormulasControllerTest extends AbstractContentTest
{

    /**
     * @test
     */
    public function I_can_find_out_if_modifier_is_selected(): void
    {
        $formulasController = $this->createFormulasController();
        self::assertFalse(
            $formulasController->isModifierSelected(ModifierCode::COLOR, [], 5),
            'No selected modifier provided so no is selected'
        );
        self::assertFalse(
            $formulasController->isModifierSelected(ModifierCode::COLOR, [5 => []], 5),
            'No selected modifier selection provided so no modifier is selected'
        );
        self::assertFalse(
            $formulasController->isModifierSelected(ModifierCode::COLOR, [5 => [ModifierCode::BREACH => ModifierCode::BREACH]], 5),
            'Another modifiers provided so color should not be selected'
        );
        self::assertTrue(
            $formulasController->isModifierSelected(ModifierCode::COLOR, [5 => [ModifierCode::COLOR => ModifierCode::COLOR]], 5)
        );
        self::assertFalse($formulasController->isModifierSelected(ModifierCode::COLOR, [5 => [ModifierCode::EXPLOSION => []]], 5));
        self::assertTrue($formulasController->isModifierSelected(ModifierCode::COLOR, [5 => [ModifierCode::COLOR => []]], 5));
    }

    /**
     * @test
     */
    public function I_can_format_number(): void
    {
        $formulasController = $this->createFormulasController();
        self::assertSame('+123', $formulasController->formatNumber(new NumberObject(123)));
        self::assertSame('-456', $formulasController->formatNumber(new NumberObject(-456)));
        self::assertSame('+0', $formulasController->formatNumber(new NumberObject(0)));
    }

    private function createFormulasController(): FormulasController
    {
        return new FormulasController(
            $this->getConfiguration(),
            new FormulasTable(),
            new ModifiersTable(),
            new SpellTraitsTable(),
            Tables::getIt()
        );
    }
}