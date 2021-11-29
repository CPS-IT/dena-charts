<?php

namespace CPSIT\DenaCharts\Domain\Model;

class ChartsJsAxis
{
    protected string $title = '';
    protected string $unit = '';

    public function __construct(string $title = '', string $unit = '')
    {
        $this->title = $title;
        $this->unit = $unit;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): void
    {
        $this->unit = $unit;
    }
}
