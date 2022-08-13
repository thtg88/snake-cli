<?php

namespace Thtg88\SnakeCli\Exceptions;

use LogicException;

final class GameAlreadyStarted extends LogicException
{
    public function __construct()
    {
        parent::__construct('The game has already started, dickhead!');
    }
}
