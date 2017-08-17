<?php

namespace  App\Services\Crossword\Generate\BaseLine;

use App\Services\Crossword\Collection\Word;
use  App\Services\Crossword\Generate\Generate;
use  App\Services\Crossword\Line\Line;
use App\Services\Crossword\Word as OneWord;

/**
 * Генерация кроссворда на основе 1 слова
 */
abstract class BaseLine extends Generate
{

    /**
     * @var Word
     */
    protected $firstWord;

    /**
     * @return Line
     */
    abstract protected function getCenterLine();

    /**
     * @return Line
     */
    abstract protected function getBaseLine();

    protected function positionFirstWord()
    {
        $centerLine = $this->getCenterLine();
        $mask = $centerLine->getMask();

        $word = $this->crossword->getWords()->getByMask($mask, true);

        if(!empty($word)) {
            $this->firstWord = $word;

            $centerLine->position($word, true, Line::PLACE_LEFT);
            return true;
        }
        return false;
    }

    protected function positionWord(OneWord $word)
    {
        $line = $this->getBaseLine();

        if(!empty($line)) {
            $line->position($word, false);
        }
    }

}