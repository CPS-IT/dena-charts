<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Domain\Model\ChartJsChart;

use CPSIT\DenaCharts\Domain\Model\ChartJsChart;
use CPSIT\DenaCharts\Domain\Model\Color;

class PieChartJsChart extends ChartJsChart
{
    /**
     * @param Color[] $colors
     */
    public function applyColors(array $colors): void
    {
        foreach($this->data['datasets'] as &$dataset) {
            $nColors = count($colors);
            $dataCount = count($dataset['data']);
            $colorArray = [];
            for($index = 0; $index < $dataCount; $index++) {
                $colorArray[] = $colors[$index % $nColors]->getValue();
            }
            $dataset['backgroundColor'] = $colorArray;
        }
    }
}
