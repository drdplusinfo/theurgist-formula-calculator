<?php
namespace DrdPlus\Calculators\Theurgist\Formulas;

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
$selectedFormula = $controller->getSelectedFormula();
$selectedFormulaCode = $selectedFormula->getFormulaCode();
?>
<!DOCTYPE html>
<html lang="cs" xmlns="http://www.w3.org/1999/html">
  <head>
    <title>Formule pro DrD+ theurga</title>
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/generic/skeleton.css">
    <link rel="stylesheet" type="text/css" href="css/theurgist.css">
    <link rel="stylesheet" type="text/css" href="css/generic/graphics.css">
    <link rel="stylesheet" type="text/css" href="css/generic/issues.css">
    <noscript>
      <link href="css/generic/no_script.css" rel="stylesheet" type="text/css">
    </noscript>
  </head>
  <body>
    <div id="fb-root"></div>
    <div class="background"></div>
    <div>
      <form class="block delete" action="" method="post" onsubmit="return window.confirm('Opravdu smazat?')">
        <label>
          <input type="submit" value="Smazat" name="<?= $controller::DELETE_HISTORY ?>">
          <span class="hint">(včetně paměti uložené v cookies)</span>
        </label>
      </form>
      <form id="configurator" class="block" action="" method="get">
        <div class="block remember">
          <label><input type="checkbox" name="<?= $controller::REMEMBER_CURRENT ?>" value="1"
                        <?php if ($controller->shouldRemember()) { ?>checked="checked"<?php } ?>>
            Pamatovat <span class="hint">(i při zavření prohlížeče)</span></label>
        </div>
        <div class="block">
          <input type="hidden" name="<?= $controller::PREVIOUS_FORMULA ?>"
                 value="<?= $selectedFormulaCode->getValue() ?>">
            <?php require __DIR__ . '/formula/formula.php'; ?>
          <hr class="clear">
            <?php require __DIR__ . '/modifiers/modifiers.php' ?>
          <hr class="clear">
          <button type="submit">Vybrat</button>
        </div>
      </form>
      <div id="result" class="result">
          <?php require __DIR__ . '/result.php'; ?>
      </div>
      <div class="block issues">
        <a href="https://rpgforum.cz/forum/viewtopic.php?f=238&t=14870">
          <img src="images/generic/rpgforum-ico.png">
          Máš nápad 😀? Vidíš chybu 😱?️ Sem s tím!
        </a>
        <a class="float-right" href="https://github.com/jaroslavtyc/drd-plus-theurgist-configurator"
           title="Fork me on GitHub"><img class="github" src="/images/generic/GitHub-Mark-64px.png"></a>
      </div>
      <script src="js/generic/skeleton.js"></script>
  </body>
</html>