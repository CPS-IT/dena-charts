<?php
if (!defined('TYPO3')) {
    die('Access denied.');
}

(function (string $extKey) {
    // register backend icons
    (function (string $extKey) {
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
        $iconsBasePath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($extKey) . 'Resources/Public/Backend/Icons';
        foreach (['ctype'] as $type) {
            $iconGlob = $iconsBasePath . '/' . $type . '/*.svg';
            foreach (glob($iconGlob) as $file) {
                $value = basename($file, '.svg');
                $iconRegistry->registerIcon(
                    sprintf('%s-%s-%s', $extKey, $type, $value),
                    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
                    ['source' => $file],
                );
            }
        }
    })($extKey);
})('dena_charts');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'CPSIT.DenaCharts',
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
