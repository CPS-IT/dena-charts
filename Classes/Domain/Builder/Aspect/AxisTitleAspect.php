<?php

namespace CPSIT\DenaCharts\Domain\Builder\Aspect;

use CPSIT\DenaCharts\Domain\Model\ChartConfiguration;
use CPSIT\DenaCharts\Domain\Model\Chart;
use TYPO3\CMS\Core\Utility\ArrayUtility;

class AxisTitleAspect implements ChartBuilderAspect
{
    public function process(ChartConfiguration $chartConfiguration, Chart $chart): Chart
    {
        // @extensionScannerIgnoreLine
        $options = $chart->getOptions();
        $options = $this->setAxis($options, 'x', $chartConfiguration->getAxisXTitle(), $chartConfiguration->getAxisXUnit());
        $options = $this->setAxis($options, 'y', $chartConfiguration->getAxisYTitle(), $chartConfiguration->getAxisYUnit());
        return $chart->withOptions($options);
    }

    public function setAxis(array $options, string $axis, string $title, string $unit): array
    {
        $axisTitle = $title;
        if (!empty($unit)) {
            $options = ArrayUtility::setValueByPath($options, ['scales', $axis, 'unit'], $unit);
            $axisTitle .= sprintf(' [%s]', $unit);
        }

        $options = ArrayUtility::setValueByPath($options, ['scales', $axis, 'title', 'display'], !empty($axisTitle));
        $options = ArrayUtility::setValueByPath($options, ['scales', $axis, 'title', 'text'], $axisTitle);

        return $options;
    }
}
