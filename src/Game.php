<?php

namespace Thtg88\SnakeCli;

use React\EventLoop\Loop;
use React\EventLoop\TimerInterface;
use Thtg88\SnakeCli\Exceptions\GameOver;
use Thtg88\SnakeCli\Exceptions\GameQuit;

final class Game
{
    private Board $board;

    private const WIDTH = 40;
    private const HEIGHT = 20;
    public const WAIT = 0.75;

    private CliInput $input;
    private CliOutput $output;
    private GameControls $game_controls;
    private Score $score;

    public function __construct(private readonly bool $debug = false)
    {
        $this->board = new Board(self::WIDTH, self::HEIGHT, $this->newSnake());
        $this->game_controls = new GameControls($this->board);
        $this->output = new CliOutput($debug);
        $this->input = new CliInput($this->game_controls);
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

        $this->draw();

        Loop::addPeriodicTimer(self::WAIT, function (TimerInterface $timer) {
            try {
                $this->round();

                $this->draw();
            } catch (GameOver|GameQuit $e) {
                $this->output->writeError($e->getMessage());
                $this->input->closeStream();

                Loop::cancelTimer($timer);
                Loop::stop();
            }
        });
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
            // Don't put the snake right at the edge of the board
            rand(1, self::WIDTH - 2),
            rand(1, self::HEIGHT - 2),
        );
    }
}
