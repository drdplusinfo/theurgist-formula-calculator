<?php
global $testsConfiguration;
$testsConfiguration = new \DrdPlus\Tests\CalculatorSkeleton\TestsConfiguration();
$testsConfiguration->disableHasCustomBodyContent();
$testsConfiguration->disableHasTables();
$testsConfiguration->disableHasNotes();
$testsConfiguration->disableHasLinksToAltar();
$testsConfiguration->setExpectedWebName('DrD+ formule pro theurga');
$testsConfiguration->setExpectedPageTitle('DrD+ formule pro theurga');
$testsConfiguration->disableHasMoreVersions();