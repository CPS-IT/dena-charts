<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\DataProcessing\ChartJsProcessor;

use CPSIT\DenaCharts\Domain\Model\ChartJsChart;

class StackedProcessor
{
    public function processStacked(ChartJsChart $chart, array $contentObject): void
    {
        if (isset($contentObject['denacharts_stack']) && (bool)$contentObject['denacharts_stack']) {
            $chart->setStacked(true);
        }
    }
}
