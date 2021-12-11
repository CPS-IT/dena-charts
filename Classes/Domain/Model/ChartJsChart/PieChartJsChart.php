<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Domain\Model\ChartJsChart;

use CPSIT\DenaCharts\Domain\Model\ChartJsChart;
use CPSIT\DenaCharts\Domain\Model\Color;

class PieChartJsChart extends ChartJsChart
{
    public function __construct(array $data, array $options, string $type)
    {
        $this->data = $this->transposeData($data);
        $this->options = $options;
        $this->type = $type;
    }

    protected function transposeData($data)
    {
        $result = [];

        $result['labels'] = array_column($data['datasets'], 'label');
        foreach ($data['labels'] as $label) {
            $result['datasets'][] = [
                'label' => $label,
                'data' => [],
            ];
        }
        foreach ($data['datasets'] as $y => $sourceDataset) {
            foreach ($sourceDataset['data'] as $x => $value) {
                $result['datasets'][$x]['data'][$y] = $value;
            }
        }

        return $result;
    }

    /**
     * @param Color[] $colors
     */
    public function applyColors(array $colors): void
    {
        foreach ($this->data['datasets'] as &$dataset) {
            $nColors = count($colors);
            $dataCount = count($dataset['data']);
            $colorArray = [];
            for ($index = 0; $index < $dataCount; $index++) {
                $colorArray[] = $colors[$index % $nColors]->getValue();
            }
            $dataset['backgroundColor'] = $colorArray;
        }
    }
}
