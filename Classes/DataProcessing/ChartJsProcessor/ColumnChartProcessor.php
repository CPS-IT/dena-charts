<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\DataProcessing\ChartJsProcessor;

use CPSIT\DenaCharts\Domain\Model\ChartJsChart;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;

class ColumnChartProcessor extends \CPSIT\DenaCharts\DataProcessing\ChartJsProcessor
{
    protected StackedProcessor $stackedProcessor;

    public function __construct(
        TypoScriptService $typoScriptService,
        StackedProcessor $stackedProcessor
    ) {
        parent::__construct($typoScriptService);
        $this->stackedProcessor = $stackedProcessor;
    }

    protected function processChart(ChartJsChart $chart, array $contentObject, array $configuration): ChartJsChart
    {
        $this->stackedProcessor->processStacked($chart, $contentObject);
        return $chart;
    }
}
