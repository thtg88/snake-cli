<?php

namespace Thtg88\SnakeCli;

final class CliOutput
{
    public function __construct(private readonly bool $debug = false)
    {
    }

    public function write(Board $board, Score $score): void
    {
        $this->clearScreen();

        echo $board->toString() . PHP_EOL;
        echo "Score: {$score->get()}" . PHP_EOL;

        if ($this->debug) {
            echo "FOOD: {$board->foodToString()}" . PHP_EOL;
            echo $board->snakeDirection()->name . PHP_EOL;
            echo $board->snakeToString() . PHP_EOL . PHP_EOL;
        }
    }

    public function writeError(string $message): void
    {
        echo $message . PHP_EOL;
    }

    private function clearScreen(): void
    {
        if ($this->debug) {
            return;
        }

        echo "\e[H\e[J";
    }
}
