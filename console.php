<?php

declare(strict_types=1);

use App\Application;

error_reporting(E_ALL);

require_once "./vendor/autoload.php";

$arguments = [];
foreach ($argv as $k => $arg) {
    if ($k === 1) {
        $arguments['task'] = $arg;
    } elseif ($k === 2) {
        $arguments['action'] = $arg;
    } elseif ($k >= 3) {
        $arguments['params'][] = $arg;
    }
}

$application = new Application;
$application->runCommandLine($arguments);
