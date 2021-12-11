<?php

namespace CPSIT\DenaCharts\Domain\Builder\Aspect;

use CPSIT\DenaCharts\Domain\Model\ChartConfiguration;
use CPSIT\DenaCharts\Domain\Model\Chart;

interface ChartBuilderAspect
{
    public function process(ChartConfiguration $chartConfiguration, Chart $chart): Chart;
}
