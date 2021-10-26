<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Domain\Model;

use TYPO3\CMS\Core\Utility\ArrayUtility;

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

    public function setShowPoints(bool $showPoints)
    {
        if ($showPoints) {
            unset($this->options['pointRadius']);
        } else {
            $this->options['pointRadius'] = 0;
        }
    }

    public function setStacked(bool $stacked)
    {
        foreach(['x', 'y'] as $axis) {
            $this->options = ArrayUtility::setValueByPath($this->options, ['scales', $axis, 'stacked'], $stacked);
        }
    }
}
