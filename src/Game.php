<?php

namespace Thtg88\SnakeCli;

use Thtg88\SnakeCli\Exceptions\GameOver;
use Thtg88\SnakeCli\Exceptions\GameQuit;

final class Game
{
    private Board $board;

    private const WIDTH = 40;
    private const HEIGHT = 20;
    public const WAIT = 0.75;
    private CliOutput $output;
    private GameControls $game_controls;
    private Score $score;

    public function __construct(private readonly bool $debug = false)
    {
        $this->board = new Board(self::WIDTH, self::HEIGHT, $this->newSnake());
        $this->game_controls = new GameControls($this->board);
        $this->output = new CliOutput($debug);
        $this->score = new Score();
    }

    public function draw(): void
    {
        if ($this->game_controls->paused()) {
            return;
        }

        $this->output->write($this->board, $this->score);
    }

    public function round(): void
    {
        if ($this->game_controls->quitting()) {
            throw new GameQuit();
        }

        if ($this->game_controls->paused()) {
            return;
        }

        if (!$this->game_controls->actionPerformed()) {
            $this->board->continueMovingSnake();
        }

        if ($this->board->hasSnakeEaten()) {
            $this->board->placeFood();
            $this->board->snakeEatsFood();
            $this->score->increment();
        }

        if ($this->board->snakeCrashed()) {
            throw new GameOver();
        }

        // Reset at the end of the round
        $this->game_controls->resetActionPerformed();
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
