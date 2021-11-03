<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\DataProcessing\ChartJsProcessor;

use CPSIT\DenaCharts\Domain\Model\ChartJsChart;

class BarChartProcessor extends \CPSIT\DenaCharts\DataProcessing\ChartJsProcessor
{
    protected StackedProcessor $stackedProcessor;

    public function __construct(
        StackedProcessor $stackedProcessor
    ) {
        $this->stackedProcessor = $stackedProcessor;
    }

    protected function processChart(ChartJsChart $chart, array $contentObject, array $configuration): ChartJsChart
    {
        parent::processChart($chart, $contentObject, $configuration);
        $this->stackedProcessor->processStacked($chart, $contentObject);
        return $chart;
    }
}
