<?php

namespace App\DominoGame\Resources;

class DominoTable extends AbstractResource
{

    protected const  DOMINO_CARDS = [
        '0,1', '0,2', '0,3', '0,4', '0,5', '0,6', '1,2', '1,3', '1,4', '1,5', '1,6', '2,3', '2,4', '2,5',
        '2,6', '3,4', '3,5', '3,6', '4,5', '4,6', '5,6', '0,0', '1,1', '2,2', '3,3', '4,4', '5,5', '6,6'
    ];

    public function __construct()
    {
        $this->initializeTable();
    }

    protected function initializeTable(): array
    {

        foreach ($this::DOMINO_CARDS as $item) {
            $values = explode(',', $item);
            $domino = new DominoItem((int)$values[0], (int)$values[1]);
            $this->dominoCards[] = $domino;
        }

        return $this->dominoCards;
    }

    protected function shuffle()
    {
        shuffle($this->dominoCards);
    }

    public function pickPlayerCards(int $length = 7):? array
    {
        $this->shuffle();
        return array_splice($this->dominoCards, 0, $length);
    }

}