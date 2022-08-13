<?php

namespace Thtg88\SnakeCli;

final class SnakeBlock
{
    public const BLOCK = '🟩';

    public function __construct(public readonly int $x, public readonly int $y)
    {
    }

    public function toString(): string
    {
        return "[x={$this->x}, y={$this->y}]";
    }
}
