<?php

namespace Thtg88\SnakeCli;

final class Food
{
    public const BLOCK = '🍎';

    public function __construct(public readonly int $x, public readonly int $y)
    {
    }

    public function isAt(int $x, int $y): bool
    {
        return $this->x === $x && $this->y === $y;
    }
}
