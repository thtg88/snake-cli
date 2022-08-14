<?php

use Thtg88\SnakeCli\Game;
use Thtg88\SnakeCli\Io\CliInput;

require __DIR__.'/vendor/autoload.php';

(new Game(new CliInput()))->start();
