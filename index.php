<?php
namespace DrdPlus\Calculator\Theurgist\Formulas;

use DrdPlus\Tables\Tables;
use DrdPlus\Theurgist\Spells\FormulasTable;
use DrdPlus\Theurgist\Spells\ModifiersTable;
use DrdPlus\Theurgist\Spells\SpellTraitsTable;

require_once __DIR__ . '/vendor/autoload.php';

error_reporting(-1);
ini_set('display_errors', '1');

$formulasTable = new FormulasTable();
$modifiersTable = new ModifiersTable();
$spellTraitsTable = new SpellTraitsTable();
$controller = new Controller($formulasTable, $modifiersTable, $spellTraitsTable, Tables::getIt()->getDistanceTable());
$selectedFormula = $controller->getCurrentFormula();
$selectedFormulaCode = $selectedFormula->getFormulaCode();
?>
<!DOCTYPE html>
<html lang="cs" xmlns="http://www.w3.org/1999/html">
  <head>
    <title>Formule pro DrD+ theurga</title>
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/generic/vendor/bootstrap.4.0.0/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/generic/vendor/bootstrap.4.0.0/bootstrap-reboot.min.css">
    <link rel="stylesheet" type="text/css" href="css/generic/vendor/bootstrap.4.0.0/bootstrap-grid.min.css">
    <link rel="stylesheet" type="text/css" href="css/generic/skeleton.css">
    <link rel="stylesheet" type="text/css" href="css/generic/graphics.css">
    <link rel="stylesheet" type="text/css" href="css/generic/issues.css">
    <link rel="stylesheet" type="text/css" href="css/theurgist.css">
    <noscript>
      <link href="css/generic/no_script.css" rel="stylesheet" type="text/css">
    </noscript>
  </head>
  <body class="container">
    <div id="fb-root"></div>
    <div class="background"></div>
    <div>
        <?php include __DIR__ . '/vendor/drd-plus/calculator-skeleton/history_deletion.php'; ?>
      <div class="row">
        <div class="message info col text-center">čísla jsou ve formátu +-bonus (hodnota) [náročnost]</div>
      </div>
      <div class="row">
        <hr class="col">
      </div>
      <div class="row">
        <form id="configurator" action="" method="get">
          <input type="hidden" name="<?= $controller::PREVIOUS_FORMULA ?>"
                 value="<?= $selectedFormulaCode->getValue() ?>">
            <?php require __DIR__ . '/formula/formula.php'; ?>
          <hr class="clear">
            <?php require __DIR__ . '/modifiers/modifiers.php' ?>
          <hr class="clear">
          <button type="submit">Vybrat</button>
        </form>
      </div>
        <?php require __DIR__ . '/result.php'; ?>
      <div class="row">
        <hr class="col">
      </div>
      <div class="row">
        <div class="col">
          <a href="https://theurg.drdplus.info/#tabulka_formuli">Pravidla pro Theurga</a>
        </div>
      </div>
        <?php
        /** @noinspection PhpUnusedLocalVariableInspection */
        $sourceCodeUrl = 'https://github.com/jaroslavtyc/drd-plus-theurgist-configurator';
        include __DIR__ . '/vendor/drd-plus/calculator-skeleton/issues.php'; ?>
      <script src="js/generic/skeleton.js"></script>
  </body>
</html>