<?php

namespace App\Services\Crossword\Generate;

use  App\Services\Crossword\Line\Line;
use  App\Services\Crossword\Word;

/**
 * Генерация кроссворда в случайном порядке
 */
class Random extends Generate
{

    /**
     * @var Word
     */
    protected $prevWord;

    protected $currentLineType;

    protected function positionFirstWord()
    {
        $fun = ['getCenterCol', 'getCenterRow'];
        $centerRow = call_user_func([$this, array_random($fun)]);
        $mask = $centerRow->getMask();

        $word = $this->crossword->getWords()->getByMask($mask, true);

        if(!empty($word)) {
            $this->prevWord = $word;
            $this->currentLineType = Line::TYPE_COLUMN;

            $centerRow->position($word, true);
            return true;
        }
        return false;
    }

    protected function positionWord(Word $word)
    {
        if($this->currentLineType == Line::TYPE_ROW) {
            $line = $this->prevWord->getRows()->getRandom();
            $this->currentLineType = Line::TYPE_COLUMN;
        } else {
            $line = $this->prevWord->getColumns()->getRandom();
            $this->currentLineType = Line::TYPE_ROW;
        }

        if(!empty($line)) {
            $isPosition = $line->position($word, false);
            if($isPosition) {
                $this->prevWord = $word;
            }
        }
    }

}