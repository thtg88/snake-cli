<?php

namespace Thtg88\SnakeCli;

use Thtg88\SnakeCli\Exceptions\WrongDirection;

enum SnakeDirection
{
    case UP;
    case RIGHT;
    case DOWN;
    case LEFT;

    public static function opposite(SnakeDirection $direction): self
    {
        return match ($direction) {
            self::UP => self::DOWN,
            self::RIGHT => self::LEFT,
            self::DOWN => self::UP,
            self::LEFT => self::RIGHT,
            default => throw new WrongDirection(),
        };
    }

    public static function random(): self
    {
        $cases = self::cases();

        return $cases[array_rand($cases)];
    }
}
