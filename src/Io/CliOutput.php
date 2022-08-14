<?php

namespace Thtg88\SnakeCli\Io;

use Thtg88\SnakeCli\Board;
use Thtg88\SnakeCli\Exceptions\TileNotValid;
use Thtg88\SnakeCli\Score;
use Thtg88\SnakeCli\Tile;

final class CliOutput
{
    public function __construct(private readonly bool $debug = false)
    {
    }

    public function write(Board $board, Score $score): void
    {
        $this->clearScreen();

        echo $this->boardToString($board) . PHP_EOL;
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

    private function boardToString(Board $board): string
    {
        return implode('', array_map(
            fn (array $row) =>  $this->rowToString($row) . PHP_EOL,
            $board->toArray(),
        ));
    }

    private function rowToString(array $row): string
    {
        return implode('', array_map(
            fn ($tile) => $this->tileToString($tile),
            $row
        ));
    }

    private function tileToString(Tile $tile): string
    {
        return match ($tile) {
            Tile::BRICK => '🟧',
            Tile::EMPTY => '⬛️',
            Tile::SNAKE => '🟩',
            Tile::FRUIT => '🍎',
            default => throw new TileNotValid(),
        };
    }

    private function clearScreen(): void
    {
        if ($this->debug) {
            return;
        }

        echo "\e[H\e[J";
    }
}
