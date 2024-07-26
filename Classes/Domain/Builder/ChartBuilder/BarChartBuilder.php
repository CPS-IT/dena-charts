<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Domain\Builder\ChartBuilder;

use CPSIT\DenaCharts\Domain\Builder\Aspect\ColorsAspect;
use CPSIT\DenaCharts\Domain\Builder\Aspect\ZoomAspect;
use CPSIT\DenaCharts\Domain\Builder\ChartBuilder;
use CPSIT\DenaCharts\Domain\Builder\Aspect\AxisTitleAspect;
use CPSIT\DenaCharts\Domain\Model\ChartConfiguration;
use CPSIT\DenaCharts\Domain\Model\Chart;
use TYPO3\CMS\Core\Utility\ArrayUtility;

class BarChartBuilder extends ChartBuilder
{
    protected AxisTitleAspect $axisTitleProcessor;

    protected ZoomAspect $zoomAspect;

    public function __construct(
        ColorsAspect $colorsProcessor,
        AxisTitleAspect  $axisTitleProcessor,
        ZoomAspect $zoomAspect
    ) {
        parent::__construct($colorsProcessor);
        $this->axisTitleProcessor = $axisTitleProcessor;
        $this->zoomAspect = $zoomAspect;
    }

    protected function process(ChartConfiguration $chartConfiguration, Chart $chart): Chart
    {
        $chart = parent::process($chartConfiguration, $chart);
        $chart = $this->axisTitleProcessor->process($chartConfiguration, $chart);
        if ($chartConfiguration->getType() === ChartConfiguration::CHART_TYPE_COLUMN) {
            $chart = $this->zoomAspect->process($chartConfiguration, $chart);
        } else {
            $chart = $this->zoomAspect->process($chartConfiguration, $chart, ['y']);
        }
        $chart = $this->processStacked($chartConfiguration, $chart);
        return $chart;
    }

    public function processStacked(ChartConfiguration $chartConfiguration, Chart $chart): Chart
    {
        // @extensionScannerIgnoreLine
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
