<?php

namespace Thtg88\SnakeCli\Io;

use React\EventLoop\Loop;
use React\EventLoop\TimerInterface;
use Thtg88\SnakeCli\GameControls;
use Thtg88\SnakeCli\SnakeDirection;

final class DumbAutoInput implements InputInterface
{
    private const WAIT = 1;

    private GameControls $game_controls;
    private TimerInterface $timer;

    public function __construct()
    {
    }

    public function starting(): void
    {
        $this->timer = Loop::addPeriodicTimer(self::WAIT, function () {
            $this->moveRandomly();
        });
    }

    public function quitting(): void
    {
        Loop::cancelTimer($this->timer);
    }

    public function setGameControls(GameControls $game_controls): void
    {
        $this->game_controls = $game_controls;
    }

    private function moveRandomly(): void
    {
        $this->game_controls->moveSnake(SnakeDirection::random());
    }
}
