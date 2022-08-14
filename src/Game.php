<?php

namespace Thtg88\SnakeCli;

use Thtg88\SnakeCli\Exceptions\GameOver;
use Thtg88\SnakeCli\Exceptions\GameQuit;

final class Game
{
    private bool $paused = false;
    private bool $just_moved_direction = false;
    private bool $quitting = false;
    private Board $board;
    private const WIDTH = 40;
    private const HEIGHT = 20;
    public const WAIT = 0.75;
    private CliOutput $output;

    public function __construct(private readonly bool $debug = false)
    {
        $this->board = new Board(self::WIDTH, self::HEIGHT, $this->newSnake());
        $this->output = new CliOutput($debug);
    }

    public function draw(): void
    {
        if ($this->paused) {
            return;
        }

        $this->output->write($this->board, $this->score);
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

        $this->just_moved_direction = true;
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
        $this->quitting = true;
    }

    public function round(): void
    {
        if ($this->quitting) {
            throw new GameQuit();
        }

        if ($this->paused) {
            return;
        }

        if (!$this->just_moved_direction) {
            $this->board->continueMovingSnake();
        }

        if ($this->board->hasEaten()) {
            $this->board->placeFood();
            $this->board->snakeEatsFood();
        }

        if ($this->board->snakeCrashed()) {
            throw new GameOver();
        }

        // Reset at the end of the round
        $this->just_moved_direction = false;
    }

    public function start(): void
    {
        $this->board->placeFood();
    }

    private function newSnake(): Snake
    {
        return new Snake(
            $this->randomSnakeBlock(),
            SnakeDirection::random(),
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
