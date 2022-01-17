<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Domain\Builder\ChartBuilder;

use CPSIT\DenaCharts\Domain\Builder\ChartBuilder;
use CPSIT\DenaCharts\Domain\Builder\Aspect\AxisTitleAspect;
use CPSIT\DenaCharts\Domain\Model\ChartConfiguration;
use CPSIT\DenaCharts\Domain\Model\Chart;
use CPSIT\DenaCharts\Domain\Model\DataCell;
use TYPO3\CMS\Core\Utility\ArrayUtility;

class AreaChartBuilder extends ChartBuilder
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

        $data = $chart->getData();
        $options = $chart->getOptions();

        // Activate fill
        foreach($data['datasets'] as &$dataset) {
            $dataset['fill'] = 'origin';
        }

        // Remove data points
        $options['defaultPointRadius'] = 0;

        // Stack on Y axis
        $options = ArrayUtility::setValueByPath($options, ['scales','y','stacked'], true);

        $chart = $chart->withData($data)->withOptions($options);
        return $chart;
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
