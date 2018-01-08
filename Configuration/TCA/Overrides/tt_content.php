<?php

// Adds the content element to the "Type" dropdown
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
    [
        'LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:TCA.type.denacharts_chart',
        'denacharts_chart',
        'EXT:dena_charts/Resources/Public/Icons/ContentElements/chart.gif'
    ],
    'CType',
    'dena_charts'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'dena_charts',
    'Configuration/TypoScript/',
    'DENA Charts'
);

$tempColumns = [
    'denacharts_data_file' => [
        'label' => 'Data File',
        'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
            'denacharts_data_file', [
            'minitems' => 1,
            'maxitems' => 1,
            'eval' => 'required'
        ],
            'csv'
        )
    ],
    'denacharts_type' => [
        'exclude' => 0,
        'label' => 'Chart Type',
        'config' => [
            'type' => 'radio',
            'items' => [
                ['bar', \CPSIT\DenaCharts\DataProcessing\ChartJsProcessor::CHART_TYPE_BAR],
                ['line', \CPSIT\DenaCharts\DataProcessing\ChartJsProcessor::CHART_TYPE_LINE],
                ['pie', \CPSIT\DenaCharts\DataProcessing\ChartJsProcessor::CHART_TYPE_PIE],
                ['doughnut', \CPSIT\DenaCharts\DataProcessing\ChartJsProcessor::CHART_TYPE_DOUGHNUT],
                ['radar', \CPSIT\DenaCharts\DataProcessing\ChartJsProcessor::CHART_TYPE_RADAR],
            ],
            'default' => \CPSIT\DenaCharts\DataProcessing\ChartJsProcessor::CHART_TYPE_BAR,
            'eval' => 'required'
        ],
    ],
    'denacharts_aspect_ratio' => [
        'exclude' => 0,
        'label' => 'aspect ratio',
        'config' => [
            'type' => 'input',
            'default' => '16:9',
            'eval' => 'required'
        ]
    ],
    'denacharts_container_width' => [
        'exclude' => 0,
        'label' => 'container width',
        'config' => [
            'type' => 'input',
            'default' => '100%',
            'eval' => 'required'
        ]
    ]
];
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'tt_content', $tempColumns
);
// Configure the default backend fields for the chart content element
$GLOBALS['TCA']['tt_content']['palettes']['chart_imagesize'] =[
    'showitem' => 'denacharts_aspect_ratio,denacharts_container_width'
];
$GLOBALS['TCA']['tt_content']['types']['denacharts_chart'] = [
    'showitem' => '
         --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.general;general,
         --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.header;header,
      --div--;LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tabs.chart,
        denacharts_type,denacharts_data_file,
        --palette--;;chart_imagesize,
      --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.appearance,
         --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.frames;frames,
      --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.access,
         --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.visibility;visibility,
         --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.access;access,
      --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.extended
'];
