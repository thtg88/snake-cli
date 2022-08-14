<?php

use Thtg88\SnakeCli\Game;
use Thtg88\SnakeCli\Io\CliImmediateInput;

require __DIR__.'/vendor/autoload.php';

(new Game(new CliImmediateInput()))->start();
