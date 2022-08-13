<?php

namespace Thtg88\SnakeCli;

final class Board
{
    private const BRICK_BLOCK = '🟧';
    private const EMPTY_BLOCK = '⬛️';

    private Food $food;

    public function __construct(
        public readonly int $width,
        public readonly int $height,
        public readonly Snake $snake,
    ) {
    }

    public function continueMovingSnake(): void
    {
        $this->snake->continueMoving();
    }

    public function foodToString(): string
    {
        return $this->food->toString();
    }

    public function hasEaten(): bool
    {
        $snake_head = $this->snake->head();

        return $snake_head->x === $this->food->x &&
            $snake_head->y === $this->food->y;
    }

    public function moveSnakeUp(): void
    {
        $this->snake->moveUp();
    }

    public function moveSnakeRight(): void
    {
        $this->snake->moveRight();
    }

    public function moveSnakeDown(): void
    {
        $this->snake->moveDown();
    }

    public function moveSnakeLeft(): void
    {
        $this->snake->moveLeft();
    }

    public function placeFood(): void
    {
        do {
            $this->food = $this->newFood();
        } while ($this->snake->isAt($this->food->x, $this->food->y));
    }

    public function snakeCrashed(): bool
    {
        $snake_head = $this->snake->head();

        return $snake_head->x >= $this->width ||
            $snake_head->y >= $this->height ||
            $snake_head->x < 0 ||
            $snake_head->y < 0;
    }

    public function snakeDirection(): SnakeDirection
    {
        return $this->snake->direction();
    }

    public function snakeEatsFood(): void
    {
        $this->snake->eat();
    }

    public function snakeHead(): SnakeBlock
    {
        return $this->snake->head();
    }

    public function snakeToString(): string
    {
        return $this->snake->toString();
    }

    public function toArray(): array
    {
        // Width + 2 to fill the borders on the sides
        $tiles = [array_fill(0, $this->width + 2, self::BRICK_BLOCK)];

        for ($y = 0; $y < $this->height; $y++) {
            $tiles[$y + 1][0] = self::BRICK_BLOCK;

            for ($x = 0; $x < $this->width; $x++) {
                if ($this->snake->isAt($x, $y)) {
                    $tiles[$y + 1][$x + 1] = SnakeBlock::BLOCK;
                } else if ($this->food->isAt($x, $y)) {
                    $tiles[$y + 1][$x + 1] = Food::BLOCK;
                } else {
                    $tiles[$y + 1][$x + 1] = self::EMPTY_BLOCK;
                }
            }

            $tiles[$y + 1][$x + 1] = self::BRICK_BLOCK;
        }

        $tiles[] = array_fill(0, $this->width + 2, self::BRICK_BLOCK);

        return $tiles;
    }

    public function toString(): string
    {
        return implode('', array_map(
            fn (array $tiles) => implode('', $tiles) . PHP_EOL,
            $this->toArray(),
        ));
    }

    private function newFood(): Food
    {
        return new Food(
            rand(0, $this->width - 1),
            rand(0, $this->height - 1),
        );
    }
}
