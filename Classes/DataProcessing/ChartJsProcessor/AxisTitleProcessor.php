<?php

namespace CPSIT\DenaCharts\DataProcessing\ChartJsProcessor;

use CPSIT\DenaCharts\Domain\Model\ChartJsChart;
use CPSIT\DenaCharts\Domain\Model\ChartsJsAxis;

class AxisTitleProcessor
{
    public function processAxisTitles(ChartJsChart $chart, array $contentObject): void
    {
        $chart->setXAxis(new ChartsJsAxis(
            $contentObject['denacharts_axis_x_title'] ?? '',
            $contentObject['denacharts_axis_x_unit'] ?? '',
        ));
        $chart->setYAxis(new ChartsJsAxis(
            $contentObject['denacharts_axis_y_title'] ?? '',
            $contentObject['denacharts_axis_y_unit'] ?? '',
        ));
    }
}
