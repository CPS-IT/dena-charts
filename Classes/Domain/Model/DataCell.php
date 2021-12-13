<?php

namespace CPSIT\DenaCharts\Domain\Model;

class DataCell
{
    protected float $value;

    protected bool $highlight = false;

    protected DataRow $row;

    protected DataColumn $column;

    public function __construct(float $value)
    {
        $this->value = $value;
    }

    public function getId(): string
    {
        return $this->column->getLetters() . $this->row->getNumber();
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function isHighlight(): bool
    {
        return $this->highlight;
    }

    public function setHighlight(bool $highlight): void
    {
        $this->highlight = $highlight;
    }

    public function getRow(): DataRow
    {
        return $this->row;
    }

    public function getColumn(): DataColumn
    {
        return $this->column;
    }

    public function setRow(DataRow $row): void
    {
        $this->row = $row;
    }

    public function setColumn(DataColumn $column): void
    {
        $this->column = $column;
    }
}
