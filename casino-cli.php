#!/usr/bin/php
<?php

$_GET['arg'] = 'cli';

require_once 'src/casino.php';

$casino = new Casino();
$casino->showWelcomeScreen();
$casino->gameLoop();
