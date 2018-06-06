<?php
require_once __DIR__ . '/../../vendor/autoload.php';

\error_reporting(-1);
\ini_set('display_errors', '1');
\ini_set('xdebug.max_nesting_level', '100');

include __DIR__ . '/tests_config.php';

const DRD_PLUS_INDEX_FILE_NAME_TO_TEST = __DIR__ . '/../../index.php';
