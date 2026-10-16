<?php

namespace CPSIT\DenaCharts\Domain\Builder\Aspect;

use CPSIT\DenaCharts\Domain\Model\Chart;
use CPSIT\DenaCharts\Domain\Model\ChartConfiguration;

interface ChartBuilderAspect
{
    public function process(ChartConfiguration $chartConfiguration, Chart $chart): Chart;
}
