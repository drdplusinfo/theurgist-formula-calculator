<?php
global $testsConfiguration;
$testsConfiguration = new \DrdPlus\Tests\FrontendSkeleton\TestsConfiguration();
$testsConfiguration->disableHasCustomBodyContent();
$testsConfiguration->disableHasTables();
$testsConfiguration->disableHasNotes();
$testsConfiguration->disableHasLinksToAltar();
$testsConfiguration->setExpectedPageTitle('Formule pro DrD+ theurga');