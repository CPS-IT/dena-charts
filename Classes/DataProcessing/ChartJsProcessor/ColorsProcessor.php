<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\DataProcessing\ChartJsProcessor;

use CPSIT\DenaCharts\Domain\Model\ChartJsChart;
use CPSIT\DenaCharts\Domain\Repository\ColorSchemeRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ColorsProcessor
{
    protected ColorSchemeRepository $colorSchemeRepository;

    public function __construct(ColorSchemeRepository $colorSchemeRepository)
    {
        $this->colorSchemeRepository = $colorSchemeRepository;
    }

    public function processColors(ChartJsChart $chart, array $contentObject): void
    {
        $colorSchemeId = $contentObject['denacharts_color_scheme'];
        $colorScheme = $this->colorSchemeRepository->findById($colorSchemeId);
        $colorIds = GeneralUtility::trimExplode(',', $contentObject['denacharts_colors'], true);
        $colors = empty($colorIds) ? $colorScheme->getColors() : $colorScheme->getColors($colorIds);
        $chart->applyColors($colors);
    }
}
