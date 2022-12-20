<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Domain\Model;

class Color
{
    protected string $id;
    protected string $value;

    public function __construct(string $id, string $value)
    {
        $this->id = $id;
        $this->value = $value;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
