<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Domain\Builder\Aspect;

use CPSIT\DenaCharts\Domain\Model\ChartConfiguration;
use CPSIT\DenaCharts\Domain\Model\Chart;
use CPSIT\DenaCharts\Domain\Repository\ColorSchemeRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ColorsAspect implements ChartBuilderAspect
{
    protected ColorSchemeRepository $colorSchemeRepository;

    public function __construct(ColorSchemeRepository $colorSchemeRepository)
    {
        $this->colorSchemeRepository = $colorSchemeRepository;
    }

    public function process(ChartConfiguration $chartConfiguration, Chart $chart): Chart
    {
        $colorSchemeId = $chartConfiguration->getColorScheme();
        $colorScheme = $this->colorSchemeRepository->findById($colorSchemeId);
        $colorIds = GeneralUtility::trimExplode(',', $chartConfiguration->getColors(), true);
        $colors = empty($colorIds) ? $colorScheme->getColors() : $colorScheme->getColors($colorIds);

        if (in_array($chartConfiguration->getType(), ['doughnut', 'pie'])) {
            $chart = $this->applyColorsToX($chart, $colors);
        } else {
            $chart = $this->applyColorsToY($chart, $colors);
        }

        return $chart;
    }

    protected function applyColorsToX(Chart $chart, array $colors): Chart
    {
        $data = $chart->getData();
        foreach ($data['datasets'] as &$dataset) {
            $nColors = count($colors);
            $dataCount = count($dataset['data']);
            $colorArray = [];
            for ($index = 0; $index < $dataCount; $index++) {
                $colorArray[] = $colors[$index % $nColors]->getValue();
            }
            $dataset['backgroundColor'] = $colorArray;
        }
        $chart = $chart->withData($data);
        return $chart;
    }

    protected function applyColorsToY(Chart $chart, array $colors): Chart
    {
        $data = $chart->getData();
        $nColors = count($colors);
        foreach ($data['datasets'] as $index => &$dataset) {
            $color = $colors[$index % $nColors];
            $value = $color->getValue();
            $update = [
                'backgroundColor' => $value,
                'borderColor' => $value,
            ];
            foreach ($update as $key => $updateValue) {
                $dataset[$key] = $updateValue;
            }
        }
        return $chart->withData($data);
    }
}
