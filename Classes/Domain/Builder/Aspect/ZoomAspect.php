<?php

namespace CPSIT\DenaCharts\Domain\Builder\Aspect;

use CPSIT\DenaCharts\Domain\Model\Chart;
use CPSIT\DenaCharts\Domain\Model\ChartConfiguration;

class ZoomAspect
{
    public function process(
        ChartConfiguration $chartConfiguration,
        Chart $chart,
        array $primaryZoomAxes = ['x'],
        array $secondaryZoomAxes = []
    ): Chart {
        if (! $chartConfiguration->isZoomEnabled()) {
            return $chart;
        }

        $mode = implode('', array_merge($primaryZoomAxes, $secondaryZoomAxes));

        $options = [
            'plugins' => [
                'zoom' => [
                    'pan' => [
                        'enabled' => true,
                    ],
                    'zoom' => [
                        'wheel' => [
                            'enabled' => true,
                            'speed' => 0.001,
                        ],
                        'pinch' => [
                            'enabled' => true,
                        ],
                        'mode' => $mode,
                    ],
                ],
            ],
        ];

        if (!empty($secondaryZoomAxes)) {
            $options['plugins']['zoom']['zoom']['overScaleMode'] = implode('', $secondaryZoomAxes);
        }

        return $chart->withOptions(array_replace_recursive($chart->getOptions(), $options));
    }
}
