<?php

namespace Thtg88\SnakeCli;

use SplDoublyLinkedList;
use Thtg88\SnakeCli\Exceptions\WrongDirection;

final class Snake
{
    private SplDoublyLinkedList $blocks;

    public function __construct(
        private SnakeBlock $starting_block,
        private SnakeDirection $direction,
    ) {
        $this->blocks = new SplDoublyLinkedList();
        $this->blocks->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);
        $this->blocks->push($starting_block);
    }

    public function continueMoving(): void
    {
        match ($this->direction) {
            SnakeDirection::UP, SnakeDirection::RIGHT, SnakeDirection::DOWN, SnakeDirection::LEFT => $this->move($this->direction),
            default => throw new WrongDirection(),
        };
    }

    public function direction(): SnakeDirection
    {
        return $this->direction;
    }

    public function eat(): void
    {
        $this->pushBlock();
    }

    public function head(): SnakeBlock
    {
        return $this->blocks->top();
    }

    public function isAt(int $x, int $y): bool
    {
        $this->blocks->rewind();

        while ($this->blocks->valid()) {
            /** @var SnakeBlock */
            $block = $this->blocks->current();

            if ($block->x === $x && $block->y === $y) {
                return true;
            }

            $this->blocks->next();
        }

        $this->blocks->rewind();

        return false;
    }

    public function move(SnakeDirection $direction): void
    {
        $this->direction = $direction;
        $this->pushBlock();
        // Remove last block of the queue
        $this->blocks->shift();
    }

    public function toString(): string
    {
        $snake = [];

        $this->blocks->rewind();

        while ($this->blocks->valid()) {
            /** @var SnakeBlock */
            $block = $this->blocks->current();

            $snake[] = "{$this->blocks->key()}: {$block->toString()}";

            $this->blocks->next();
        }

        $this->blocks->rewind();

        return implode(PHP_EOL, $snake);
    }

    /**
     * Adds a block on top of the queue in the current direction.
     */
    private function pushBlock(): void
    {
        $top = $this->head();

        $x = match ($this->direction) {
            SnakeDirection::RIGHT => $top->x + 1,
            SnakeDirection::LEFT => $top->x - 1,
            default => $top->x,
        };

        $y = match ($this->direction) {
            SnakeDirection::UP => $top->y - 1,
            SnakeDirection::DOWN => $top->y + 1,
            default => $top->y,
        };

        $this->blocks->push(new SnakeBlock($x, $y));
    }
}
