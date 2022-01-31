<?php

namespace CPSIT\DenaCharts\Domain\Builder\Aspect;

use CPSIT\DenaCharts\Domain\Model\Chart;
use CPSIT\DenaCharts\Domain\Model\ChartConfiguration;

class ZoomAspect
{
    public function process(
        ChartConfiguration $chartConfiguration,
        Chart $chart,
        array $primaryZoomAxes = ['y'],
        array $secondaryZoomAxes = ['x']
    ): Chart {
        if (! $chartConfiguration->isZoomEnabled()) {
            return $chart;
        }

        $overScaleMode = implode('', $secondaryZoomAxes);
        $mode = implode('', array_merge($primaryZoomAxes, $secondaryZoomAxes));

        return $chart->withOptions(array_replace_recursive($chart->getOptions(), [
            'plugins' => [
                'zoom' => [
                    'pan' => [
                        'enabled' => true,
                    ],
                    'zoom' => [
                        'overScaleMode' => $overScaleMode,
                        'wheel' => [
                            'enabled' => true,
                            'speed' => 0.02,
                        ],
                        'pinch' => [
                            'enabled' => true,
                        ],
                        'mode' => $mode,
                    ],
                ],
            ],
        ]));
    }
}
