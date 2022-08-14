<?php

namespace Thtg88\SnakeCli\Io;

use React\Stream\ReadableResourceStream;
use Thtg88\SnakeCli\GameControls;
use Thtg88\SnakeCli\SnakeDirection;

final class CliInput
{
    private ReadableResourceStream $stream;

    public function __construct(private readonly GameControls $game_controls)
    {
        $this->stream = new ReadableResourceStream(STDIN);
    }

    public function starting(): void
    {
        $this->stream->on('data', function ($chunk) {
            $this->handleInput($chunk);
        });
    }

    public function quitting(): void
    {
        $this->stream->close();
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
