<?php

use Thtg88\SnakeCli\Game;
use Thtg88\SnakeCli\Io\DumbAutoInput;

require __DIR__.'/vendor/autoload.php';

(new Game(new DumbAutoInput()))->start();
