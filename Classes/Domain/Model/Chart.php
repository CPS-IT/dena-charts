<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Domain\Model;

class Chart
{
    protected string $type;
    /** @var array<string, mixed> */
    protected array $options;

    /** @var array<string, mixed> */
    protected array $data;

    /**
     * @param array<string, mixed> $options
     * @param array<string, mixed> $data
     */
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

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array<string, mixed> $options
     */
    public function withOptions(array $options): Chart
    {
        return new Chart(
            $this->type,
            $options,
            $this->data,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function withData(array $data): Chart
    {
        return new Chart(
            $this->type,
            $this->options,
            $data,
        );
    }
}
