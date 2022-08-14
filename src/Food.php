<?php

namespace Thtg88\SnakeCli;

final class Food
{
    public function __construct(public readonly int $x, public readonly int $y)
    {
    }

    public function isAt(int $x, int $y): bool
    {
        return $this->x === $x && $this->y === $y;
    }

    public function toString(): string
    {
        return "[x={$this->x}, y={$this->y}]";
    }
}
