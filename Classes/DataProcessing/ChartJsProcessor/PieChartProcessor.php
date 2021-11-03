<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\DataProcessing\ChartJsProcessor;

use CPSIT\DenaCharts\Domain\Model\ChartJsChart\PieChartJsChart;

class PieChartProcessor extends \CPSIT\DenaCharts\DataProcessing\ChartJsProcessor
{
    protected string $chartClass = PieChartJsChart::class;
}
