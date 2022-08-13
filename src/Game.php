<?php

namespace Thtg88\SnakeCli;

use Thtg88\SnakeCli\Exceptions\GameOver;
use Thtg88\SnakeCli\Exceptions\GameQuit;

final class Game
{
    private bool $paused = false;
    private Board $board;
    private const WIDTH = 40;
    private const HEIGHT = 20;

    public function __construct(private readonly bool $debug = false)
    {
    }

    public function draw(): void
    {
        if ($this->paused) {
            return;
        }

        $this->clearScreen();

        echo $this->board->toString() . PHP_EOL;

        if ($this->debug) {
            echo "FOOD: {$this->board->foodToString()}" . PHP_EOL;
            echo $this->board->snakeDirection()->name . PHP_EOL;
            echo $this->board->snakeToString() . PHP_EOL;
        }
    }

    public function handleInput(string $input): void
    {
        match (trim($input)) {
            'w' => $this->board->moveSnakeUp(),
            'a' => $this->board->moveSnakeLeft(),
            's' => $this->board->moveSnakeDown(),
            'd' => $this->board->moveSnakeRight(),
            'p' => $this->pause(),
            'q' => $this->stop(),
            'r' => $this->resume(),
            default => $this->board->continueMovingSnake(),
        };
    }

    public function pause(): void
    {
        $this->paused = true;
    }

    public function resume(): void
    {
        $this->paused = false;
    }

    public function stop(): void
    {
        throw new GameQuit();
    }

    public function round(): void
    {
        if ($this->paused) {
            return;
        }

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
        $this->board = new Board(self::WIDTH, self::HEIGHT, $this->newSnake());
        $this->board->placeFood();
    }

    private function clearScreen(): void
    {
        if ($this->debug) {
            return;
        }

        echo "\e[H\e[J";
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
