<?php

namespace Thtg88\SnakeCli\Io;

use React\Stream\ReadableResourceStream;
use Thtg88\SnakeCli\GameControls;

final class CliInput
{
    private ReadableResourceStream $stream;

    public function __construct(private readonly GameControls $game_controls)
    {
        $this->stream = new ReadableResourceStream(STDIN);

        $this->stream->on('data', function ($chunk) {
            $this->handleInput($chunk);
        });
    }

    public function closeStream(): void
    {
        $this->stream->close();
    }

    public function handleInput(string $input): void
    {
        match (trim(strtolower($input))) {
            'w' => $this->game_controls->moveSnakeUp(),
            'a' => $this->game_controls->moveSnakeLeft(),
            's' => $this->game_controls->moveSnakeDown(),
            'd' => $this->game_controls->moveSnakeRight(),
            'p' => $this->game_controls->pause(),
            'q' => $this->game_controls->quit(),
            'r' => $this->game_controls->resume(),
            default => $this->game_controls->noOp(),
        };

        $this->game_controls->performAction();
    }
}
