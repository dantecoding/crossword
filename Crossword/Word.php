<?php

namespace App\Services\Crossword;

use App\Services\Crossword\Collection\Column;
use App\Services\Crossword\Collection\Row;
use App\Services\Crossword\Line\Row as LineRow;
use App\Services\Crossword\Line\Column as LineCol;

/**
 * Слово кроссворда
 */
class Word
{

    /**
     * @var string
     */
    protected $word;

    /**
     * @var Row|null
     */
    protected $rows = null;

    /**
     * @var Column|null
     */
    protected $columns = null;

    /**
     * @var null
     */
    protected $baseColumn = null;

    /**
     * @var null
     */
    protected $baseRow = null;

    /**
     * @var bool
     */
    protected $isUsed;

    protected $question;

    /**
     * @param $word
     * @param $question
     */
    public function __construct($word, $question)
    {
        $this->setWord($word);
        $this->setQuestion($question);

        $this->rows = new Row();
        $this->columns = new Column();

        $this->validate($word);
    }

    /**
     * @param LineCol $col
     */
    public function addCol(LineCol $col)
    {
        $this->columns->add($col);
    }

    /**
     * @return Column|null
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param LineRow $row
     */
    public function addRow(LineRow $row)
    {
        $this->rows->add($row);
    }

    /**
     * @return Row|null
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param LineCol $col
     */
    public function setBaseColumn(LineCol $col)
    {
        $this->baseColumn = $col;
    }

    /**
     * @return LineCol
     */
    public function getBaseColumn()
    {
        return $this->baseColumn;
    }

    /**
     * @param LineRow $col
     */
    public function setBaseRow(LineRow $col)
    {
        $this->baseRow = $col;
    }

    /**
     * @return LineRow
     */
    public function getBaseRow()
    {
        return $this->baseRow;
    }

    /**
     * @param $mask
     * @return int
     */
    public function inMask($mask)
    {
        return preg_match($mask, $this->getWord());
    }

    /**
     * @return string
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * @param $word
     */
    public function setWord($word)
    {
        $this->word = (string) trim(mb_strtolower($word, 'UTF-8'));
    }

    /**
     * @return bool
     */
    public function isUsed()
    {
        return $this->isUsed;
    }

    /**
     * @param $isUsed
     */
    public function setIsUsed($isUsed)
    {
        $this->isUsed = (bool) $isUsed;
    }

    /**
     * Валидация слова
     *
     * @param $word
     *
     * @throws Exception
     */
    protected function validate($word)
    {
        if(empty($word)) {
            throw new Exception('Слово не может быть пустым.');
        }

        if(!preg_match('/^[a-zа-я]+$/ui', $word)) {
            throw new Exception('Слово должно состоять из букв Русского и Английского алфавита. (' . $word . ')');
        }
    }

    /**
     * @return mixed
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param mixed $question
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    }

}
