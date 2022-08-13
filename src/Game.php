<?php

namespace Thtg88\SnakeCli;

use Thtg88\SnakeCli\Exceptions\GameAlreadyStarted;
use Thtg88\SnakeCli\Exceptions\GameOver;

final class Game
{
    private bool $started = false;
    private Board $board;
    private const WIDTH = 40;
    private const HEIGHT = 10;

    public function __construct(private readonly bool $debug = false)
    {
    }

    public function draw(): void
    {
        echo $this->board->toString() . PHP_EOL;

        if ($this->debug) {
            echo $this->board->snakeDirection()->name . PHP_EOL;
            echo $this->board->snakeHead()->toString() . PHP_EOL;
        }
    }

    public function handleInput(string $input): void
    {
        match (trim($input)) {
            'w' => $this->board->moveSnakeUp(),
            'a' => $this->board->moveSnakeLeft(),
            's' => $this->board->moveSnakeDown(),
            'd' => $this->board->moveSnakeRight(),
            default => $this->board->continueMovingSnake(),
        };
    }

    public function round(): void
    {
        $this->board->continueMovingSnake();

        if ($this->board->hasEaten()) {
            $this->board->placeFood();
            $this->board->snakeEatsFood();
        }

        if ($this->board->snakeCrashed()) {
            throw new GameOver();
        }
    }

    public function start(): void
    {
        if ($this->started) {
            throw new GameAlreadyStarted();
        }

        $this->board = new Board(self::WIDTH, self::HEIGHT, $this->newSnake());
        $this->board->placeFood();
    }

    private function newSnake(): Snake
    {
        return new Snake(
            $this->randomSnakeBlock(),
            SnakeDirection::random()
        );
    }

    private function randomSnakeBlock(): SnakeBlock
    {
        return new SnakeBlock(
            rand(0, self::WIDTH - 1),
            rand(0, self::HEIGHT - 1),
        );
    }
}
