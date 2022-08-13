<?php

require __DIR__.'/../vendor/autoload.php';

use React\EventLoop\Loop;
use React\EventLoop\TimerInterface;
use React\Stream\ReadableResourceStream;
use Thtg88\SnakeCli\Exceptions\GameOver;
use Thtg88\SnakeCli\Exceptions\GameQuit;
use Thtg88\SnakeCli\Game;

$game = new Game();

$game->start();

$game->draw();

$stream = new ReadableResourceStream(STDIN, null, -1);

$stream->on('data', function ($chunk) use ($game) {
    $game->handleInput($chunk);
});

Loop::addPeriodicTimer(Game::WAIT, static function (TimerInterface $timer) use ($game, $stream) {
    try {
        $game->round();

        $game->draw();
    } catch (GameOver|GameQuit $e) {
        echo $e->getMessage() . PHP_EOL;

        Loop::cancelTimer($timer);

        $stream->close();

        Loop::stop();
    }
});
