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
                ['bar', \CPSIT\DenaCharts\DataProcessing\ChartProcessor::CHART_TYPE_BAR],
                ['line', \CPSIT\DenaCharts\DataProcessing\ChartProcessor::CHART_TYPE_LINE],
            ],
            'default' => \CPSIT\DenaCharts\DataProcessing\ChartProcessor::CHART_TYPE_BAR,
            'eval' => 'required'
        ],
    ],
];
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'tt_content', $tempColumns
);
// Configure the default backend fields for the chart content element
$GLOBALS['TCA']['tt_content']['palettes']['chart_imagesize'] =[
    'showitem' => 'imagewidth;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:imagewidth_formlabel, imageheight;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:imageheight_formlabel,'
];
$GLOBALS['TCA']['tt_content']['types']['denacharts_chart'] = [
    'showitem' => '
         --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.general;general,
         --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.header;header,
      --div--;LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tabs.chart,
        denacharts_data_file,denacharts_type,
        --palette--;;chart_imagesize,
      --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.appearance,
         --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.frames;frames,
      --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.access,
         --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.visibility;visibility,
         --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.access;access,
      --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.extended
'];
