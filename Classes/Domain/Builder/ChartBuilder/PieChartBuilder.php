<?php

namespace CPSIT\DenaCharts\Domain\Builder\ChartBuilder;

use CPSIT\DenaCharts\Domain\Builder\ChartBuilder;
use CPSIT\DenaCharts\Domain\Model\ChartConfiguration;
use CPSIT\DenaCharts\Domain\Model\Chart;

class PieChartBuilder extends ChartBuilder
{
    public function process(ChartConfiguration $chartConfiguration, Chart $chart): Chart
    {
        $chart = $chart->withData($this->transposeData($chart->getData()));
        $chart = parent::process($chartConfiguration, $chart);
        $yUnit = $chartConfiguration->getAxisYUnit();
        if (!empty($yUnit)) {
            $options = $chart->getOptions();
            $options = array_merge_recursive($options, [
                'scales' => [
                    'y' => [
                        'unit' => $yUnit,
                        'display' => false,
                    ]
                ]
            ]);
            $chart = $chart->withOptions($options);
        }

        return $chart;
    }

    protected function transposeData($data)
    {
        $result = [];

        $result['labels'] = array_column($data['datasets'], 'label');
        foreach ($data['labels'] as $label) {
            $result['datasets'][] = [
                'label' => $label,
                'data' => [],
            ];
        }
        foreach ($data['datasets'] as $y => $sourceDataset) {
            foreach ($sourceDataset['data'] as $x => $value) {
                $result['datasets'][$x]['data'][$y] = $value;
            }
        }

        return $result;
    }
}
