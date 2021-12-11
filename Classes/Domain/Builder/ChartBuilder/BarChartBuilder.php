<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Domain\Builder\ChartBuilder;

use CPSIT\DenaCharts\Domain\Builder\ChartBuilder;
use CPSIT\DenaCharts\Domain\Builder\Aspect\AxisTitleAspect;
use CPSIT\DenaCharts\Domain\Model\ChartConfiguration;
use CPSIT\DenaCharts\Domain\Model\Chart;
use TYPO3\CMS\Core\Utility\ArrayUtility;

class BarChartBuilder extends ChartBuilder
{
    protected AxisTitleAspect $axisTitleProcessor;

    public function __construct(
        AxisTitleAspect  $axisTitleProcessor
    ) {
        $this->axisTitleProcessor = $axisTitleProcessor;
    }

    protected function process(ChartConfiguration $chartConfiguration, Chart $chart): Chart
    {
        $chart = parent::process($chartConfiguration, $chart);
        $chart = $this->axisTitleProcessor->process($chartConfiguration, $chart);
        $chart = $this->processStacked($chartConfiguration, $chart);
        return $chart;
    }

    public function processStacked(ChartConfiguration $chartConfiguration, Chart $chart): Chart
    {
        $options = $chart->getOptions();
        foreach (['x', 'y'] as $axis) {
            $options = ArrayUtility::setValueByPath(
                $options,
                ['scales', $axis, 'stacked'],
                $chartConfiguration->isStack()
            );
        }
        return $chart->withOptions($options);
    }
}
