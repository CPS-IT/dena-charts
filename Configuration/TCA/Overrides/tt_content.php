<?php

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'dena_charts',
    'Configuration/TypoScript/',
    'DENA Charts'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', [
    'denacharts_data_file' => [
        'label' => 'Data File',
        'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
            'denacharts_data_file',
            [
                'minitems' => 1,
                'maxitems' => 1,
                'eval' => 'required'
            ],
            'csv'
        )
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
    ],
    'denacharts_source' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tt_content.denacharts_source',
        'config' => [
            'type' => 'input',
        ]
    ],
    'denacharts_source_link' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tt_content.denacharts_source_link',
        'config' => [
            'type' => 'input',
            'renderType' => 'inputLink',
        ]
    ],
    'denacharts_show_points' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tt_content.denacharts_show_points',
        'config' => [
            'type' => 'check',
            'renderType' => 'checkboxToggle',
            'items' => [[0 => '', 1 => '',]],
            'default' => 1,
        ]
    ],
    'denacharts_stack' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tt_content.denacharts_stack',
        'config' => [
            'type' => 'check',
            'renderType' => 'checkboxToggle',
            'items' => [[0 => '', 1 => '',]],
        ]
    ],
    'denacharts_color_scheme' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tt_content.denacharts_color_scheme',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'itemsProcFunc' => \CPSIT\DenaCharts\Form\ColorSchemeSelectorItemProvider::class . '->provideColorSchemeSelectorItems',
        ],
        'onChange' => 'reload',
    ],
    'denacharts_colors' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tt_content.denacharts_colors',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectMultipleSideBySide',
            'itemsProcFunc' => \CPSIT\DenaCharts\Form\ColorSchemeSelectorItemProvider::class . '->provideColorSelectorItems',
            'multiple' => true,
        ],
    ],
]);

// Configure the default palettes for the chart content element
$GLOBALS['TCA']['tt_content']['palettes']['chart_imagesize'] = [
    'showitem' => 'denacharts_aspect_ratio,denacharts_container_width',
];
$GLOBALS['TCA']['tt_content']['palettes']['chart_source'] = [
    'showitem' => 'denacharts_source,denacharts_source_link',
];


// Register separate CTypes for all chart types
foreach(\CPSIT\DenaCharts\DataProcessing\ChartJsProcessor::CHART_TYPES as $chartType) {
    $cType = 'denacharts_chart_' . $chartType;
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
        [
            'LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tt_content.CType.I.' . $cType,
            $cType,
            'dena_charts-ctype-' . $cType,
        ],
        'CType',
        'dena_charts'
    );

    $GLOBALS['TCA']['tt_content']['types'][$cType] = [
        'showitem' => '
             --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.general;general,
             --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.header;header,
          --div--;LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tabs.chart,
            denacharts_data_file,
            denacharts_color_scheme,
            denacharts_colors,
            --palette--;;chart_source,
            bodytext,
            --palette--;;chart_imagesize,
          --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.appearance,
             --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.frames;frames,
          --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.access,
             --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.visibility;visibility,
             --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.access;access,
          --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.extended
        ',
        'columnsOverrides' => [
            'bodytext' => [
                'label' => 'LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tt_content.bodytext',
                'config' => [
                    'enableRichtext' => true,
                ],
            ],
        ],
    ];
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    'denacharts_show_points',
    'denacharts_chart_' . \CPSIT\DenaCharts\DataProcessing\ChartJsProcessor::CHART_TYPE_LINE,
    'after:--palette--;;chart_imagesize',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    'denacharts_stack',
    implode(',', array_map(fn (string $chartType) => 'denacharts_chart_' . $chartType, [
        \CPSIT\DenaCharts\DataProcessing\ChartJsProcessor::CHART_TYPE_COLUMN,
        \CPSIT\DenaCharts\DataProcessing\ChartJsProcessor::CHART_TYPE_BAR,
    ])),
    'after:--palette--;;chart_imagesize',
);
