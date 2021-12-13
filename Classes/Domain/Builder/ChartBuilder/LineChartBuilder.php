<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Domain\Builder\ChartBuilder;

use CPSIT\DenaCharts\Domain\Builder\ChartBuilder;
use CPSIT\DenaCharts\Domain\Builder\Aspect\AxisTitleAspect;
use CPSIT\DenaCharts\Domain\Model\ChartConfiguration;
use CPSIT\DenaCharts\Domain\Model\Chart;
use CPSIT\DenaCharts\Domain\Model\DataCell;

class LineChartBuilder extends ChartBuilder
{
    protected AxisTitleAspect $axisTitleProcessor;

    public function __construct(
        AxisTitleAspect $axisTitleProcessor
    ) {
        $this->axisTitleProcessor = $axisTitleProcessor;
    }

    protected function process(ChartConfiguration $chartConfiguration, Chart $chart): Chart
    {
        $chart = parent::process($chartConfiguration, $chart);
        $chart = $this->axisTitleProcessor->process($chartConfiguration, $chart);
        $chart = $this->processShowPoints($chartConfiguration, $chart);
        return $chart;
    }

    protected function processShowPoints(ChartConfiguration $chartConfiguration, Chart $chart): Chart
    {
        $options = $chart->getOptions();
        if ($chartConfiguration->isShowPoints()) {
            unset($options['defaultPointRadius']);
        } else {
            $options['defaultPointRadius'] = 0;
        }

        return $chart->withOptions($options);
    }

    protected function convertCell(DataCell $dataCell)
    {
        return [
            'x' => $dataCell->getRow()->getLabel(),
            'y' => $dataCell->getValue(),
            'highlight' => $dataCell->isHighlight(),
        ];
    }
}
