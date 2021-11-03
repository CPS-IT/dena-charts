<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\DataProcessing\ChartJsProcessor;

use CPSIT\DenaCharts\DataProcessing\ChartJsProcessor;
use CPSIT\DenaCharts\Domain\Model\ChartJsChart;

class LineChartProcessor extends ChartJsProcessor
{
    protected function processChart(ChartJsChart $chart, array $contentObject, array $configuration): ChartJsChart
    {
        parent::processChart($chart, $contentObject, $configuration);
        $showPoints = isset($contentObject['denacharts_show_points']) && (bool) $contentObject['denacharts_show_points'];
        $chart->setShowPoints($showPoints);
        return $chart;
    }
}
