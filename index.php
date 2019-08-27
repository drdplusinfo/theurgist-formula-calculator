<?php
namespace DrdPlus\Theurgist\Formulas;

use DrdPlus\Calculators\Theurgist\FormulaServicesContainer;
use DrdPlus\CalculatorSkeleton\CalculatorApplication;
use DrdPlus\CalculatorSkeleton\CalculatorConfiguration;
use DrdPlus\RulesSkeleton\Dirs;
use DrdPlus\RulesSkeleton\Environment;
use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\RulesSkeleton\TracyDebugger;

error_reporting(-1);
if ((!empty($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] === '127.0.0.1') || PHP_SAPI === 'cli') {
    ini_set('display_errors', '1');
} else {
    ini_set('display_errors', '0');
}
$documentRoot = $documentRoot ?? (PHP_SAPI !== 'cli' ? rtrim(dirname($_SERVER['SCRIPT_FILENAME']), '\/') : getcwd());

/** @noinspection PhpIncludeInspection */
require_once $documentRoot . '/vendor/autoload.php';

$dirs = $dirs ?? new Dirs($documentRoot);
$htmlHelper = $htmlHelper ?? HtmlHelper::createFromGlobals($dirs, Environment::createFromGlobals());
if (PHP_SAPI !== 'cli') {
    TracyDebugger::enable($htmlHelper->isInProduction());
}

$configuration = $configuration ?? CalculatorConfiguration::createFromYml($dirs);
$servicesContainer = $servicesContainer ?? new FormulaServicesContainer($configuration, $htmlHelper);
$formulaCalculatorApplication = $calculatorApplication ?? $rulesApplication ?? new CalculatorApplication($servicesContainer);

$formulaCalculatorApplication->run();