<?php

require_once __DIR__ . '/Dedelang/Command/Invok.php';

(!isset($argv[1])? die("Error commmand zeref"):'');

($argv[1]==="start-project"? Invok::start():'');

($argv[1]==="-m" || $argv[1]==="module" ? Invok::module($argv[2]):'');
