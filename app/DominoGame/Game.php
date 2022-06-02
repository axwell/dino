<?php

namespace App\DominoGame;


use App\DominoGame\Resources\DominoItem;
use App\DominoGame\Resources\DominoTable;
use App\DominoGame\Resources\Player;
use Symfony\Component\Console\Output\OutputInterface;

class Game implements GameInterface
{

    protected DominoTable $dominoTable;

    protected array $cardsOnTable;

    protected int $currentPlayerIdx;

    /**
     * @var Player[]
     */
    protected array $players;

    public function __construct(DominoTable $table)
    {
        $this->dominoTable = $table;
    }

    protected function init(int $nrOfPlayers)
    {
        // Initialize players and domino items
        for($i = 1; $i <= $nrOfPlayers; $i++) {
            $player = new Player($i);
            $player->setDominoCards($this->dominoTable->pickPlayerCards());
            $this->players[] = $player;
        }
    }

    public function start(int $nrOfPlayers, OutputInterface $output)
    {
        $this->init($nrOfPlayers);
        $firstPlayer = $this->findPlayerBiggestDouble();

        $dominoCard           = $firstPlayer->withdrawCard();
        $this->cardsOnTable[] = $dominoCard;

        $output->info(
            sprintf(
                "Player %s is first and plays card: %s,%s",
                $firstPlayer->getPlayerName(),
                $dominoCard->getUpper(),
                $dominoCard->getBottom()
            )
        );
        $output->info($this->drawTable());

        $turns = 0;
        while ($turns < 27) {
            $turns++;
            $this->nextPlayer();

            $player = $this->currentPlayer();

            $playingCard = $player->hasMatchingCard(last($this->cardsOnTable));
            if ($playingCard) {
                $this->cardsOnTable[] = $playingCard;
                $output->info(
                    sprintf(
                        'Player %s plays card: %s,%s',
                        $player->getPlayerName(),
                        $playingCard->getUpper(),
                        $playingCard->getBottom()
                    )
                );
            } else {
                $newCard = $this->dominoTable->withdrawCard();

                if ($newCard) {
                    $player->addDominoCard($newCard);
                    $output->info(
                        sprintf(
                        'Player %s picked a new card from the stack %s,%s',
                            $player->getPlayerName(),
                            $newCard->getUpper(),
                            $newCard->getBottom()
                        )
                    );
                }
            }

            $output->info($this->drawTable());
        }

        $this->getWinner($output);
    }

    public function drawTable(): string
    {
        $output = 'Table is now: ';
        /** @var DominoItem $card */
        foreach ($this->cardsOnTable as $card){
            $output .= sprintf(' %s,%s ', $card->getUpper(), $card->getBottom());
        }

        return $output;
    }

    public function nextPlayer()
    {
        if ($this->currentPlayerIdx >= count($this->players) - 1) {
            $this->currentPlayerIdx = 0;
        } else {
            $this->currentPlayerIdx++;
        }
    }

    public function currentPlayer(): Player
    {
        return $this->players[$this->currentPlayerIdx];
    }

    protected function findPlayerBiggestDouble(): Player
    {
        $players = [];
        foreach ($this->players as $idx => $player)
        {
            $maxDouble = $player->getMaxDouble();
            $players[$idx] = $maxDouble;
        }

        $maxVal = max($players);
        $key    = array_search($maxVal, $players);

        $this->currentPlayerIdx = $key;

        return $this->players[$key];
    }

    protected function getWinner($output): void
    {
        $topPlayers = [];
        foreach ($this->players as $idx => $player)
        {
            $topPlayers[$idx] = $player->calculateTotalPoints();
        }

        asort($topPlayers);

        foreach (array_keys($topPlayers) as $playerIdx)
        {
            $player =  $this->players[$playerIdx];
            $output->info(
                sprintf(
                    'Player %s total %s points',
                    $player->getPlayerName(),
                    $player->calculateTotalPoints()
                )
            );
        }
    }

}