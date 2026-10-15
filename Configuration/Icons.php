<?php

declare(strict_types=1);

use CPSIT\DenaCharts\Domain\Model\ChartConfiguration;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

$icons = [];
foreach (ChartConfiguration::CHART_TYPES as $chartType) {
    $cType = 'denacharts_chart_' . $chartType;
    $icons['dena_charts-ctype-' . $cType] = [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:dena_charts/Resources/Public/Backend/Icons/ctype/' . $cType . '.svg',
    ];
}

return $icons;
