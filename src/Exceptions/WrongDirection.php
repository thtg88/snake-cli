<?php

namespace Thtg88\SnakeCli\Exceptions;

use LogicException;

final class WrongDirection extends LogicException
{
    public function __construct()
    {
        parent::__construct('Wrong direction, dickhead!');
    }
}
