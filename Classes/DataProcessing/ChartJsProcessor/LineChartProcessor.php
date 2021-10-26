<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\DataProcessing\ChartJsProcessor;

use CPSIT\DenaCharts\DataProcessing\ChartJsProcessor;
use CPSIT\DenaCharts\Domain\Model\ChartJsChart;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;

class LineChartProcessor extends ChartJsProcessor
{
    public function __construct(TypoScriptService $typoScriptService)
    {
        parent::__construct($typoScriptService);
    }

    protected function processChart(ChartJsChart $chart, array $contentObject, array $configuration)
    {
        $showPoints = isset($contentObject['denacharts_show_points']) && (bool) $contentObject['denacharts_show_points'];
        $chart->setShowPoints($showPoints);
        return $chart;
    }
}
