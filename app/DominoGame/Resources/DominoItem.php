<?php

namespace App\DominoGame\Resources;

class DominoItem
{

    protected int $upper;

    protected int $bottom;

    public function __construct($upper, $bottom)
    {
        $this->upper  = $upper;
        $this->bottom = $bottom;
    }

    /**
     * @return int
     */
    public function getUpper(): int
    {
        return $this->upper;
    }

    /**
     * @param int $upper
     *
     * @return DominoItem
     */
    public function setUpper(int $upper): DominoItem
    {
        $this->upper = $upper;

        return $this;
    }

    /**
     * @return int
     */
    public function getBottom(): int
    {
        return $this->bottom;
    }

    /**
     * @param int $bottom
     *
     * @return DominoItem
     */
    public function setBottom(int $bottom): DominoItem
    {
        $this->bottom = $bottom;

        return $this;
    }
}