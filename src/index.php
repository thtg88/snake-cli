<?php

require __DIR__.'/../vendor/autoload.php';

use React\EventLoop\Loop;
use React\EventLoop\TimerInterface;
use React\Stream\ReadableResourceStream;
use Thtg88\SnakeCli\Exceptions\GameOver;
use Thtg88\SnakeCli\Game;

$game = new Game(true);

$game->clearScreen();

$game->start();

$game->draw();

$stream = new ReadableResourceStream(STDIN, null, -1);

$stream->on('data', function ($chunk) use ($game) {
    $game->handleInput($chunk);
});

Loop::addPeriodicTimer(1, static function (TimerInterface $timer) use ($game, $stream) {
    $game->clearScreen();

    try {
        $game->round();

        $game->draw();
    } catch (GameOver $e) {
        echo $e->getMessage() . PHP_EOL;

        Loop::cancelTimer($timer);

        $stream->close();

        Loop::stop();
    }
});

