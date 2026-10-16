<?php

if (!defined('TYPO3')) {
    die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'DenaCharts',
    'Chart',
    [
        \CPSIT\DenaCharts\Controller\ChartController::class => implode(',', [
            'chart',
            'download',
        ]),
    ],
    [],
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1639416111638] = [
    'nodeName' => 'dena-charts-data-table',
    'priority' => '70',
    'class' => \CPSIT\DenaCharts\Form\ChartsDataTable::class,
];
