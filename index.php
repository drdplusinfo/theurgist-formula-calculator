<?php
namespace DrdPlus\Calculator\Theurgist\Formulas;

use DrdPlus\Tables\Tables;
use DrdPlus\Theurgist\Spells\FormulasTable;
use DrdPlus\Theurgist\Spells\ModifiersTable;
use DrdPlus\Theurgist\Spells\SpellTraitsTable;

\error_reporting(-1);
if ((!empty($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] === '127.0.0.1') || PHP_SAPI === 'cli') {
    \ini_set('display_errors', '1');
} else {
    \ini_set('display_errors', '0');
}
$documentRoot = $documentRoot ?? (PHP_SAPI !== 'cli' ? \rtrim(\dirname($_SERVER['SCRIPT_FILENAME']), '\/') : \getcwd());
$vendorRoot = $vendorRoot ?? $documentRoot . '/vendor';
/** @noinspection PhpIncludeInspection */
require_once $vendorRoot . '/autoload.php';

$formulasTable = new FormulasTable();
$modifiersTable = new ModifiersTable();
$spellTraitsTable = new SpellTraitsTable();
/** @noinspection PhpUnusedLocalVariableInspection */
$controller = $controller ?? new FormulasController(
        $formulasTable,
        $modifiersTable,
        $spellTraitsTable,
        Tables::getIt(),
        'https://github.com/jaroslavtyc/drd-plus-theurgist-configurator',
        $documentRoot,
        $vendorRoot
    );
/** @noinspection PhpIncludeInspection */
require $vendorRoot . '/drd-plus/calculator-skeleton/index.php';
