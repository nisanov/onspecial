<?php

declare(strict_types=1);

use App\Application;

error_reporting(E_ALL);

require_once "../vendor/autoload.php";

$application = new Application;
$application->run($_SERVER['REQUEST_URI']);
