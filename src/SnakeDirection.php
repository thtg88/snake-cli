<?php

namespace Thtg88\SnakeCli;

enum SnakeDirection
{
    case UP;
    case RIGHT;
    case DOWN;
    case LEFT;

    public static function random(): self
    {
        $cases = self::cases();

        return $cases[array_rand($cases)];
    }
}
