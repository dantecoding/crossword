<?php

namespace App\Services\Crossword;

use App\Services\Crossword\Collection\Column;
use App\Services\Crossword\Collection\Row;
use App\Services\Crossword\Generate\Generate;
use App\Services\Crossword\Line\Column as LineCol;
use App\Services\Crossword\Line\Row as LineRow;
use App\Services\Crossword\Collection\Word;

/**
 * Генерирование кроссворда из списка слов.
 * Есть возможность генерировать кроссворд в рандомном порядке или на основе одного слова.
 * Библиотека написана так, что бы способы генерации можно было написать самому и легко добавить в код.
 */
class Crossword
{

    /**
     * @var int Количество колонок
     */
    protected $columnsCount;

    /**
     * @var int Количество строк
     */
    protected $rowsCount;

    /**
     * @var Column Коллекция колонок
     */
    protected $columns;

    /**
     * @var Row Коллекция строк
     */
    protected $rows;

    /**
     * @var Word Коллекция слов
     */
    protected $words;

    protected $questions;

    /**
     * @param int $colsCount
     * @param int $rowsCount
     * @param array $words
     * @param array $questions
     */
    public function __construct(int $colsCount, int $rowsCount, array $words, array $questions)
    {
        $this->setColumnsCount($colsCount);
        $this->setRowsCount($rowsCount);

        $this->words = new Word($words, $questions);
        $this->questions = $questions;

        $this->init();
    }

    /**
     * Инициализиция класса
     */
    public function init()
    {
        $this->generateFields();
    }

    /**
     * Автоматически генерирует кроссворд из списка слов
     * Тип генерации можно выбрать из класса CrosswordGenerate, там же можно посмотреть как написать свой тип.
     *
     * @param string $type 'Тип генерации (CrosswordGenerate::RANDOM, CrosswordGenerate::BASE_LINE, ...)'
     *
     * @return bool Сгенерирован кроссворд или нет
     */
    public function generate($type = Generate::TYPE_RANDOM)
    {
        $classGenerate = Generate::factory($type, $this);

        return $classGenerate->generate();
    }

    /**
     * На основе количества колонок и строк генерирует необходимое количество колонок, строк и полей
     */
    protected function generateFields()
    {
        $columnsCount = $this->getColumnsCount();
        $rowsCount = $this->getRowsCount();

        $columns = new Column();
        $rows = new Row();

        $first = true;
        for ($i = 1; $columnsCount >= $i; $i++) {
            $col = new LineCol($i, $this);
            $columns->addCol($col);

            for ($k = 1; $rowsCount >= $k; $k++) {
                if ($first) {
                    $row = new LineRow($k, $this);
                    $rows->addRow($row);
                }

                $_row = $rows->getByIndex($k);
                $field = new Field($col, $_row);

                $col->addField($field);
                $_row->addField($field);
            }

            $first = false;
        }

        $this->setRows($rows);
        $this->setColumns($columns);
    }

    /**
     * @return Word
     */
    public function getWords()
    {
        return $this->words;
    }

    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * @return Column Коллекция колонок
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param Column $columns
     */
    public function setColumns(Column $columns)
    {
        $this->columns = $columns;
    }

    /**
     * @return Row Коллекция строк
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @params Row Коллекция строк
     * @param Row $rows
     */
    public function setRows(Row $rows)
    {
        $this->rows = $rows;
    }

    /**
     * @return int Количество колонок
     */
    public function getColumnsCount()
    {
        return $this->columnsCount;
    }

    /**
     * @params int Количество колонок
     */
    public function setColumnsCount($columnsCount)
    {
        $this->columnsCount = (int)$columnsCount;
    }

    /**
     * @return int Количество строк
     */
    public function getRowsCount()
    {
        return $this->rowsCount;
    }

    /**
     * @params int Количество строк
     */
    public function setRowsCount($rowsCount)
    {
        $this->rowsCount = (int)$rowsCount;
    }

    public function toArray()
    {
        $array = [];
        $index = 0;
        foreach ($this->getRows() as $row) {
            foreach ($row->getFields() as $field) {
                $array[$index][] = ($field->getChar() ? $field->getChar() : null);
            }

            $index++;
        }

        return $array;
    }

}