<?php

namespace App\Services\Crossword\Collection;

use App\Services\Crossword\Iterator;

/**
 * Коллекция для колонок, строк, слов.
 */
class Collection implements \IteratorAggregate
{

    /**
     * @var array
     */
    protected $items = array();

    /**
     * @return Iterator
     */
    public function getIterator() {
        return new Iterator($this->items);
    }

    /**
     * Добавление нового элемента
     *
     * @param $value
     * @param null $key
     */
    public function add($value, $key = null) {
        if(!is_null($key)) {
            $this->items[$key] = $value;
        } else {
            $this->items[] = $value;
        }
    }

    /**
     * @return bool Проверка на отсутствие элементов
     */
    public function notEmpty() {
        return !empty($this->items);
    }

    /**
     * @return bool
     */
    public function getRandom() {
        if(!empty($this->items)) {
            $randKey = array_rand($this->items);
            return $this->items[$randKey];
        }
        return false;
    }

    /**
     * @param int $index
     * @return string
     * @throws \Exception
     */
    public function getByIndex($index) {
        if(array_key_exists($index, $this->items)) {
            return $this->items[$index];
        }
        throw new \Exception('Неверно задан индекс. (' . $index . ')');
    }

}
