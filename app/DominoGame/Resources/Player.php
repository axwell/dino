<?php

namespace App\DominoGame\Resources;

use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Exception\InvalidTerminalException;

class Player extends AbstractResource
{

    protected string $playerName;

    protected int $playerNr;

    public function __construct(int $playerNr)
    {
        $this->playerNr = $playerNr;
        $this->askForName();
    }

    public function askForName()
    {
        try {
            $itemCallable = function (CliMenu $menu) {
                $result = $menu->askText()
                    ->setPromptText(sprintf("Player %s, enter your name", $this->playerNr))
                    ->setPlaceholderText('Jane Doe')
                    ->setValidationFailedText('Please enter your name')
                    ->setValidator(function ($name) {
                        return !empty($name);
                    })->ask();

                $this->playerName = $result->fetch();
            };

            $menu = (new CliMenuBuilder)
                ->setTitle(sprintf('Player %s, Choose wisely', $this->playerNr))
                ->addItem('Enter your name, then select EXIT', $itemCallable)
                ->setGoBackButtonText('Back !')
                ->build();

            $menu->open();
        } catch (\Exception $e) {
            $this->askForName();
        }

    }

    /**
     * @return string
     */
    public function getPlayerName(): string
    {
        return $this->playerName;
    }

    /**
     * @param string $playerName
     *
     * @return Player
     */
    public function setPlayerName(string $playerName): Player
    {
        $this->playerName = $playerName;

        return $this;
    }

    /**
     * @return array
     */
    public function getDominoCards(): array
    {
        return $this->dominoCards;
    }

    /**
     * @param array $dominoCards
     *
     * @return Player
     */
    public function setDominoCards(array $dominoCards): Player
    {
        $this->dominoCards = $dominoCards;

        return $this;
    }

    public function addDominoCard(DominoItem $dominoItem): Player
    {
        $this->dominoCards[] = $dominoItem;

        return $this;
    }

    public function getMaxDouble(): int
    {
        $doubles = [];
        foreach ($this->dominoCards as $dominoCard)
        {
            if ($dominoCard->getBottom() == $dominoCard->getUpper()) {
                $doubles[] = $dominoCard->getBottom() + $dominoCard->getUpper();
            }
        }

        if ($doubles) {
            return max($doubles);
        } else {
            return 0;
        }
    }

    public function hasMatchingCard(DominoItem $dominoItem):? DominoItem
    {
        foreach ($this->dominoCards as $idx => $card)
        {
            if ($card->getUpper() == $dominoItem->getUpper() || $card->getBottom() == $dominoItem->getBottom()) {
                $dominoCard = array_splice($this->dominoCards, $idx, 1);
                return $dominoCard[0];
            }
        }

        return null;
    }

    public function calculateTotalPoints(): int
    {
        $total = 0;
        foreach ($this->dominoCards as $dominoCard) {
            $total+= $dominoCard->getUpper() + $dominoCard->getBottom();
        }

        return $total;
    }

}