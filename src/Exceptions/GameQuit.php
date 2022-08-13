<?php

namespace Thtg88\SnakeCli\Exceptions;

use RuntimeException;

final class GameQuit extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Cheery-bye!');
    }
}
