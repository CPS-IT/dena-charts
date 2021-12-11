<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Domain\Model;

class Chart
{
    protected string $type;
    protected array $options;
    protected array $data;

    public function __construct(string $type, array $options, array $data)
    {
        $this->type = $type;
        $this->options = $options;
        $this->data = $data;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function withOptions(array $options): Chart
    {
        return new Chart(
            $this->type,
            $options,
            $this->data,
        );
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function withData(array $data)
    {
        return new Chart(
            $this->type,
            $this->options,
            $data,
        );
    }
}
