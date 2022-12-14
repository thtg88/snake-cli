<?php

namespace Thtg88\SnakeCli\Io;

use React\EventLoop\Loop;
use React\EventLoop\TimerInterface;
use Thtg88\SnakeCli\GameControls;
use Thtg88\SnakeCli\SnakeDirection;

final class CliImmediateInput implements InputInterface
{
    private GameControls $game_controls;
    private TimerInterface $timer;

    private const INPUT_WAIT_MICRO_SECONDS = 20_000;
    private const INPUT_TIMER = 0.1;

    public function __construct()
    {
        readline_callback_handler_install('', function () { });
    }

    public function starting(): void
    {
        $this->timer = Loop::addPeriodicTimer(self::INPUT_TIMER, function () {
            $read_streams = [STDIN];
            $write_streams = null;
            $except_streams = null;
            $number_of_characters = stream_select(
                $read_streams,
                $write_streams,
                $except_streams,
                0,
                self::INPUT_WAIT_MICRO_SECONDS,
            );
            if (!$number_of_characters || !in_array(STDIN, $read_streams)) {
                return;
            }

            $character = stream_get_contents(STDIN, 1);
            if (!$character) {
                return;
            }

            $this->handleInput($character);
        });
    }

    public function quitting(): void
    {
        readline_callback_handler_remove();
        Loop::stop($this->timer);
    }

    public function setGameControls(GameControls $game_controls): void
    {
        $this->game_controls = $game_controls;
    }

    private function handleInput(string $input): void
    {
        match (trim(strtolower($input))) {
            'w' => $this->game_controls->moveSnake(SnakeDirection::UP),
            'a' => $this->game_controls->moveSnake(SnakeDirection::LEFT),
            's' => $this->game_controls->moveSnake(SnakeDirection::DOWN),
            'd' => $this->game_controls->moveSnake(SnakeDirection::RIGHT),
            'p' => $this->game_controls->pause(),
            'q' => $this->game_controls->quit(),
            'r' => $this->game_controls->resume(),
            default => $this->game_controls->noOp(),
        };

        $this->game_controls->performAction();
    }
}
