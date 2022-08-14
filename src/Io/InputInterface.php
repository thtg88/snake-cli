<?php

namespace Thtg88\SnakeCli\Io;

use Thtg88\SnakeCli\GameControls;

interface InputInterface
{
    public function starting(): void;
    public function quitting(): void;
    public function setGameControls(GameControls $game_controls): void;
}
