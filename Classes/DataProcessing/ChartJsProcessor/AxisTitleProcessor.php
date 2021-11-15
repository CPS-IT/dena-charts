<?php

namespace CPSIT\DenaCharts\DataProcessing\ChartJsProcessor;

use CPSIT\DenaCharts\Domain\Model\ChartJsChart;

class AxisTitleProcessor
{
    public function processAxisTitles(ChartJsChart $chart, array $contentObject): void
    {
        if (!empty($contentObject['denacharts_axis_title_x'])) {
            $chart->setXAxisTitle($contentObject['denacharts_axis_title_x']);
        }
        if (!empty($contentObject['denacharts_axis_title_y'])) {
            $chart->setYAxisTitle($contentObject['denacharts_axis_title_y']);
        }
    }
}
