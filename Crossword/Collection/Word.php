<?php

namespace App\Services\Crossword\Collection;

use App\Services\Crossword\Word as OneWord;
/**
 * Коллекция слов
 */
class Word extends Collection
{

    /**
     * @param array $words
     * @param array $questions
     */
    public function __construct(array $words = [], array  $questions = [])
    {
        foreach($words as $key => $word)
        {
            parent::add(new OneWord($word, $questions[$key]));
        }
    }

    /**
     * Возвращает случайное слово по маске
     *
     * @param string $mask Pattern
     * @param bool $most Наибольшее слово
     * @return bool|OneWord
     */
    public function getByMask($mask, $most = false) {
        $words = new Word();
        foreach($this as $word) {
            if($word->inMask($mask)) {
                $words->add($word);
            }
        }
        if($most) {
            $most = null;
            foreach($words as $word) {
                if(empty($most)) {
                    $most = $word;
                } elseif(mb_strlen($word->getWord(), 'UTF-8') > mb_strlen($most->getWord(), 'UTF-8')) {
                    $most = $word;
                }
            }
            return $most;
        }
        return $words->getRandom();
    }

    /**
     * @return Word Коллекция не использованных слов
     */
    public function notUsed()
    {
        $words = new Word();
        foreach($this->getwords() as $word) {
            if(!$word->isUsed()) {
                $words->add($word);
            }
        }
        return $words;
    }

    /**
     * @return bool|OneWord Случайное слово из коллекции
     */
    public function getRandom() {
        $words = $this->getWords();
        if(!empty($words)) {
            $randKey = array_rand($words);
            return $words[$randKey];
        }
        return false;
    }

    /**
     * @return array
     */
    public function getWords() {
        return $this->items;
    }

}
