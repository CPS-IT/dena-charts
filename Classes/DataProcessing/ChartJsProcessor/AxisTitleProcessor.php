<?php

namespace CPSIT\DenaCharts\DataProcessing\ChartJsProcessor;

use CPSIT\DenaCharts\Domain\Model\ChartJsChart;

class AxisTitleProcessor
{
    public function processAxisTitles(ChartJsChart $chart, array $contentObject): void
    {
        if (!empty($contentObject['denacharts_axis_x_title'])) {
            $chart->setXAxisTitle($contentObject['denacharts_axis_x_title']);
        }
        if (!empty($contentObject['denacharts_axis_y_title'])) {
            $chart->setYAxisTitle($contentObject['denacharts_axis_y_title']);
        }
    }
}
