<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Domain\Model;

class ChartJsChart
{
    protected array $data;
    protected array $options;
    protected string $type;

    public function __construct(array $data, array $options, string $type)
    {
        $this->data = $data;
        $this->options = $options;
        $this->type = $type;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
