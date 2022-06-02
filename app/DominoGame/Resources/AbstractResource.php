<?php

namespace App\DominoGame\Resources;

abstract class AbstractResource
{
    /**
     * @var DominoItem[]
     */
    protected array $dominoCards = [];

    public function withdrawCard():? DominoItem
    {
        if (count($this->dominoCards) >= 1) {
            shuffle($this->dominoCards);

            $dominoCard = array_splice($this->dominoCards, 0, 1);
            return $dominoCard[0];
        }

        return null;
    }
}