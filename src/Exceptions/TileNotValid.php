<?php

namespace Thtg88\SnakeCli\Exceptions;

use LogicException;

final class TileNotValid extends LogicException
{
    public function __construct()
    {
        parent::__construct('Tile not valid, dickhead!');
    }
}
