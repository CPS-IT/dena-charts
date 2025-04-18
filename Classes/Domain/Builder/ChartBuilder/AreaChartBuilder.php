<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Domain\Builder\ChartBuilder;

use CPSIT\DenaCharts\Domain\Builder\Aspect\ColorsAspect;
use CPSIT\DenaCharts\Domain\Builder\Aspect\ZoomAspect;
use CPSIT\DenaCharts\Domain\Builder\ChartBuilder;
use CPSIT\DenaCharts\Domain\Builder\Aspect\AxisTitleAspect;
use CPSIT\DenaCharts\Domain\Model\ChartConfiguration;
use CPSIT\DenaCharts\Domain\Model\Chart;
use CPSIT\DenaCharts\Domain\Model\DataCell;
use CPSIT\DenaCharts\Domain\Model\DataColumn;
use TYPO3\CMS\Core\Utility\ArrayUtility;

class AreaChartBuilder extends ChartBuilder
{
    protected AxisTitleAspect $axisTitleProcessor;

    protected ZoomAspect $zoomAspect;

    public function __construct(
        ColorsAspect $colorsProcessor,
        AxisTitleAspect $axisTitleProcessor,
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

        $data = $chart->getData();
        // @extensionScannerIgnoreLine
        $options = $chart->getOptions();

        // Activate fill
        foreach ($data['datasets'] as &$dataset) {
            $dataset['fill'] = 'origin';
        }

        // Remove data points
        $options['defaultPointRadius'] = 0;
        $options['pointHitRadius'] = 20;

        // Stack on Y axis
        $options = ArrayUtility::setValueByPath($options, ['scales','y','stacked'], true);

        // Respect the 'show grid lines' setting
        $options = ArrayUtility::setValueByPath(
            $options,
            ['scales', 'y', 'grid', 'drawOnChartArea'],
            $chartConfiguration->isShowGridLines(),
        );

        $chart = $chart->withData($data)->withOptions($options);
        $chart = $this->addSecondYAxis($chartConfiguration, $chart);
        $chart = $this->zoomAspect->process($chartConfiguration, $chart);
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

    protected function addSecondYAxis(ChartConfiguration $chartConfiguration, Chart $chart): Chart
    {
        $firstIndex = $chartConfiguration->getAxisY2FirstColumn();
        if (! isset($firstIndex)) {
            return $chart;
        }

        // @extensionScannerIgnoreLine
        $options = $chart->getOptions();
        $data = $chart->getData();

        // Add second y axis
        $options['scales']['y2'] = [
            'type' => 'linear',
            'position' => 'right',
            'grid' => [
                'drawOnChartArea' => false,
            ],
        ];

        // Set axis title
        $options = $this->axisTitleProcessor->setAxis(
            $options,
            'y2',
            $chartConfiguration->getAxisY2Title(),
            $chartConfiguration->getAxisY2Unit(),
        );

        // Assign datasets to new Y2 axis
        $firstDataSetIndex = $firstIndex - 1;
        for ($index = $firstDataSetIndex; $index < count($data['datasets']); $index++) {
            if (is_array($data['datasets'][$index])) {
                $data['datasets'][$index]['yAxisID'] = 'y2';
                $data['datasets'][$index]['fill'] = false;
            }
        }

        // Ensure that the lines (assumed to be in the last data columns) overlap the areas.
        $data['datasets'] = array_reverse($data['datasets']);
        $options = ArrayUtility::setValueByPath($options, ['plugins', 'legend', 'reverse'], true);

        $chart = $chart->withData($data)->withOptions($options);
        return $chart;
    }
}
