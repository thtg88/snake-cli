<?php

namespace Thtg88\SnakeCli;

final class Board
{
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

    public function hasSnakeEaten(): bool
    {
        $snake_head = $this->snake->head();

        return $snake_head->x === $this->food->x &&
            $snake_head->y === $this->food->y;
    }

    public function moveSnake(SnakeDirection $direction): void
    {
        // Can't move back onto yourself
        if ($this->snakeDirection() === SnakeDirection::opposite($direction)) {
            return;
        }

        $this->snake->move($direction);
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
        $tiles = [array_fill(0, $this->width + 2, Tile::BRICK)];

        for ($y = 0; $y < $this->height; $y++) {
            $tiles[$y + 1][0] = Tile::BRICK;

            for ($x = 0; $x < $this->width; $x++) {
                if ($this->snake->isAt($x, $y)) {
                    $tiles[$y + 1][$x + 1] = Tile::SNAKE;
                } else if ($this->food->isAt($x, $y)) {
                    $tiles[$y + 1][$x + 1] = Tile::FRUIT;
                } else {
                    $tiles[$y + 1][$x + 1] = Tile::EMPTY;
                }
            }

            $tiles[$y + 1][$x + 1] = Tile::BRICK;
        }

        $tiles[] = array_fill(0, $this->width + 2, Tile::BRICK);

        return $tiles;
    }

    private function newFood(): Food
    {
        return new Food(
            rand(0, $this->width - 1),
            rand(0, $this->height - 1),
        );
    }
}
