<?php

namespace App\DominoGame;

use Symfony\Component\Console\Output\OutputInterface;

interface GameInterface
{
    public function start(int $nrOfPlayers, OutputInterface $output);
}