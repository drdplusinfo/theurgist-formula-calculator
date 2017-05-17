<?php
namespace DrdPlus\Theurgist\Configurator;

use DrdPlus\Tables\Tables;
use DrdPlus\Theurgist\Spells\FormulasTable;
use DrdPlus\Theurgist\Spells\ModifiersTable;
use DrdPlus\Theurgist\Spells\SpellTraitsTable;

require_once __DIR__ . '/vendor/autoload.php';

error_reporting(-1);
ini_set('display_errors', '1');

$modifiersTable = new ModifiersTable(Tables::getIt());
$spellTraitsTable = new SpellTraitsTable();
$formulasTable = new FormulasTable(Tables::getIt(), $modifiersTable, $spellTraitsTable);
$controller = new IndexController($formulasTable, $modifiersTable, $spellTraitsTable);
$selectedFormula = $controller->getSelectedFormula();
$selectedFormulaCode = $selectedFormula->getFormulaCode();
?>
<!DOCTYPE html>
<html lang="cs" xmlns="http://www.w3.org/1999/html">
<head>
    <title>Formule pro DrD+ theurga</title>
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/socials.css">
    <noscript>
        <link rel="stylesheet" type="text/css" href="css/no_script.css">
    </noscript>
    <link rel="stylesheet" href="css/gh-fork-ribbon.min.css"/>
    <script type="text/javascript" src="js/facebook.js" async></script>
</head>
<body>
<div id="fb-root"></div>
<div>
    <form id="configurator" class="body" method="get">
        <input type="hidden" name="previousFormula" value="<?= $selectedFormulaCode->getValue() ?>">
        <?php require __DIR__ . '/formula/formula.php'; ?>
        <div id="modifiers" class="block">
            <div>Modifikátory:</div>
            <?php require __DIR__ . '/modifiers.php' ?>
        </div>
        <button type="submit">Vybrat</button>
    </form>
</div>
<div id="result" class="result">
    <?php require __DIR__ . '/result.php'; ?>
</div>
<div class="block">
    <div class="fb-like facebook"
         data-href="https://formule.theurg.drdplus.info/<?= $_SERVER['QUERY_STRING'] ? ('?' . $_SERVER['QUERY_STRING']) : '' ?>"
         data-layout="button" data-action="recommend"
         data-size="small" data-show-faces="false" data-share="true"></div>
    <a class="github-fork-ribbon right-bottom fixed"
       href="https://github.com/jaroslavtyc/drd-plus-theurgist-configurator/"
       title="Fork me on GitHub">Fork me</a>
</div>
<script src="js/main.js"></script>
</body>
</html>