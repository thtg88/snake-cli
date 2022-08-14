<?php

namespace Thtg88\SnakeCli;

final class Score
{
    public function __construct(private int $value = 0)
    {
    }

    public function increment(int $value = 1): void
    {
        $this->value = $this->value + $value;
    }

    public function get(): int
    {
        return $this->value;
    }
}
