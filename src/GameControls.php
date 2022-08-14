<?php

namespace Thtg88\SnakeCli;

final class GameControls
{
    private bool $paused = false;
    private bool $just_performed_action = false;
    private bool $quitting = false;

    public function __construct(private readonly Board $board)
    {
    }

    public function actionPerformed(): bool
    {
        return $this->just_performed_action;
    }

    public function performAction(): void
    {
        $this->just_performed_action = true;
    }

    public function resetActionPerformed(): void
    {
        $this->just_performed_action = false;
    }

    public function moveSnake(SnakeDirection $direction): void
    {
        $this->board->moveSnake($direction);
    }

    public function noOp(): void
    {
    }

    public function pause(): void
    {
        $this->paused = true;
    }

    public function paused(): bool
    {
        return $this->paused;
    }

    public function resume(): void
    {
        $this->paused = false;
    }

    public function resumed(): bool
    {
        return !$this->paused;
    }

    public function quit(): void
    {
        $this->quitting = true;
    }

    public function quitting(): bool
    {
        return $this->quitting;
    }
}
