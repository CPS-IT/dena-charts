<?php

namespace CPSIT\DenaCharts\Domain\Builder\ChartBuilder;

use CPSIT\DenaCharts\Domain\Builder\ChartBuilder;
use CPSIT\DenaCharts\Domain\Model\Chart;
use CPSIT\DenaCharts\Domain\Model\ChartConfiguration;

class PieChartBuilder extends ChartBuilder
{
    public function process(ChartConfiguration $chartConfiguration, Chart $chart): Chart
    {
        $chart = $chart->withData($this->transposeData($chart->getData()));
        $chart = parent::process($chartConfiguration, $chart);
        $yUnit = $chartConfiguration->getAxisYUnit();
        if (!empty($yUnit)) {
            // @extensionScannerIgnoreLine
            $options = $chart->getOptions();
            $options = array_merge_recursive($options, [
                'scales' => [
                    'y' => [
                        'unit' => $yUnit,
                        'display' => false,
                    ],
                ],
            ]);
            $chart = $chart->withOptions($options);
        }

        return $chart;
    }

    /**
     * @param array{labels: array<int, string>, datasets: array<int, array{label: string, data: array<int, mixed>}>} $data
     * @return array<string, mixed>
     */
    protected function transposeData(array $data): array
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
