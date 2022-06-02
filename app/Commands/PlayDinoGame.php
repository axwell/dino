<?php

namespace App\Commands;

use App\DominoGame\Game;
use LaravelZero\Framework\Commands\Command;

class PlayDinoGame extends Command
{

    protected const PLAYER_MAPPING = [
        2,
        3,
        4,
    ];

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'play-game';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Play a domino game';


    /**
     * @param Game $game
     *
     * @return void
     */
    public function handle(Game $game)
    {
        $this->info('Starting game...');

        $option = $this->menu('Welcome to domino game, please select the number of players', [
            '2 players',
            '3 players',
            '4 players',
        ])->open();

        $this->info("You have chosen the option number #$option");
        $game->start(self::PLAYER_MAPPING[$option], $this->output);

    }
}
