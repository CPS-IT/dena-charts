<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\DataProcessing\ChartJsProcessor;

use CPSIT\DenaCharts\Domain\Model\ChartJsChart;

class BarChartProcessor extends \CPSIT\DenaCharts\DataProcessing\ChartJsProcessor
{
    protected AxisTitleProcessor $axisTitleProcessor;
    protected StackedProcessor $stackedProcessor;

    public function __construct(
        AxisTitleProcessor $axisTitleProcessor,
        StackedProcessor $stackedProcessor
    ) {
        $this->axisTitleProcessor = $axisTitleProcessor;
        $this->stackedProcessor = $stackedProcessor;
    }

    protected function processChart(ChartJsChart $chart, array $contentObject, array $configuration): ChartJsChart
    {
        parent::processChart($chart, $contentObject, $configuration);
        $this->axisTitleProcessor->processAxisTitles($chart, $contentObject);
        $this->stackedProcessor->processStacked($chart, $contentObject);
        return $chart;
    }
}
