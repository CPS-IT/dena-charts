<?php

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'dena_charts',
    'Configuration/TypoScript/',
    'DENA Charts'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', [
    'denacharts_data_file' => [
        'label' => 'LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tt_content.denacharts_data_file',
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
    'denacharts_allow_download' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tt_content.denacharts_allow_download',
        'config' => [
            'type' => 'check',
            'renderType' => 'checkboxToggle',
            'items' => [[0 => '', 1 => '',]],
            'default' => 1,
        ]
    ],
    'denacharts_download_filename' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tt_content.denacharts_download_filename',
        'description' => 'LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tt_content.denacharts_download_filename.description',
        'config' => [
            'type' => 'input',
            'eval' => 'trim,alphanum_x',
        ]
    ],
    'denacharts_axis_x_title' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tt_content.denacharts_axis_x_title',
        'config' => [
            'type' => 'input',
        ]
    ],
    'denacharts_axis_x_unit' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tt_content.denacharts_axis_x_unit',
        'config' => [
            'type' => 'input',
        ]
    ],
    'denacharts_axis_y_title' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tt_content.denacharts_axis_y_title',
        'config' => [
            'type' => 'input',
        ]
    ],
    'denacharts_axis_y_unit' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tt_content.denacharts_axis_y_unit',
        'config' => [
            'type' => 'input',
        ]
    ],
    'denacharts_axis_y2_first_column' => [
        'label' => 'LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tt_content.denacharts_axis_y2_first_column',
        'description' => 'LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tt_content.denacharts_axis_y2_first_column.description',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'itemsProcFunc' => \CPSIT\DenaCharts\Form\ColumnSelectorItemProvider::class . '->provideColumnSelectorItems',
        ],
    ],
    'denacharts_axis_y2_title' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tt_content.denacharts_axis_y2_title',
        'config' => [
            'type' => 'input',
        ]
    ],
    'denacharts_axis_y2_unit' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tt_content.denacharts_axis_y2_unit',
        'config' => [
            'type' => 'input',
        ]
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
    'denacharts_enable_zoom' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tt_content.denacharts_enable_zoom',
        'description' => 'LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tt_content.denacharts_enable_zoom.description',
        'config' => [
            'type' => 'check',
            'renderType' => 'checkboxToggle',
            'items' => [[0 => '', 1 => '',]],
            'default' => 1,
        ]
    ],
    'denacharts_source' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tt_content.denacharts_source',
        'config' => [
            'type' => 'input',
            'eval' => 'required,trim',
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
    'denacharts_show_gridlines' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tt_content.denacharts_show_gridlines',
        'config' => [
            'type' => 'check',
            'renderType' => 'checkboxToggle',
            'items' => [[0 => '', 1 => '',]],
            'default' => 1,
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
    'denacharts_show_datatable' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tt_content.denacharts_show_datatable',
        'config' => [
            'type' => 'check',
            'renderType' => 'checkboxToggle',
            'items' => [[0 => '', 1 => '',]],
            'default' => 0,
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
    'denacharts_data' => [
        'label' => 'LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tt_content.denacharts_data',
        'config' => [
            'type' => 'none',
            'renderType' => 'dena-charts-data-table',
            'pass_content' => true,
        ],
    ],
    'denacharts_highlights' => [
        'label' => 'LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tt_content.denacharts_highlights',
        'description' => 'LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tt_content.denacharts_highlights.description',
        'config' => [
            'type' => 'text'
        ],
    ],
]);

// Configure the default palettes for the chart content element
$GLOBALS['TCA']['tt_content']['palettes']['chart_axis'] = [
    'showitem' => 'denacharts_axis_x_title,denacharts_axis_x_unit,denacharts_axis_y_title,denacharts_axis_y_unit',
];

$GLOBALS['TCA']['tt_content']['palettes']['chart_download'] = [
    'showitem' => 'denacharts_allow_download,denacharts_download_filename'
];

$GLOBALS['TCA']['tt_content']['palettes']['chart_imagesize'] = [
    'showitem' => 'denacharts_aspect_ratio,denacharts_container_width',
];
$GLOBALS['TCA']['tt_content']['palettes']['chart_source'] = [
    'showitem' => 'denacharts_source,denacharts_source_link',
];

$GLOBALS['TCA']['tt_content']['palettes']['chart_axis_y2'] = [
    'showitem' => 'denacharts_axis_y2_title,denacharts_axis_y2_unit,--linebreak--,denacharts_axis_y2_first_column',
];

// Register separate CTypes for all chart types
foreach (\CPSIT\DenaCharts\Domain\Model\ChartConfiguration::CHART_TYPES as $chartType) {
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
            --palette--;;chart_download,
            denacharts_show_datatable,
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
    '--palette--;;chart_axis',
    implode(',', array_map(fn (string $chartType) => 'denacharts_chart_' . $chartType, [
        \CPSIT\DenaCharts\Domain\Model\ChartConfiguration::CHART_TYPE_AREA,
        \CPSIT\DenaCharts\Domain\Model\ChartConfiguration::CHART_TYPE_BAR,
        \CPSIT\DenaCharts\Domain\Model\ChartConfiguration::CHART_TYPE_COLUMN,
        \CPSIT\DenaCharts\Domain\Model\ChartConfiguration::CHART_TYPE_LINE,
    ])),
    'after:denacharts_data_file',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    'denacharts_axis_y_unit',
    implode(',', array_map(fn (string $chartType) => 'denacharts_chart_' . $chartType, [
        \CPSIT\DenaCharts\Domain\Model\ChartConfiguration::CHART_TYPE_DOUGHNUT,
        \CPSIT\DenaCharts\Domain\Model\ChartConfiguration::CHART_TYPE_PIE,
    ])),
    'after:denacharts_data_file',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    'denacharts_enable_zoom',
    implode(',', array_map(fn (string $chartType) => 'denacharts_chart_' . $chartType, [
        \CPSIT\DenaCharts\Domain\Model\ChartConfiguration::CHART_TYPE_AREA,
        \CPSIT\DenaCharts\Domain\Model\ChartConfiguration::CHART_TYPE_BAR,
        \CPSIT\DenaCharts\Domain\Model\ChartConfiguration::CHART_TYPE_COLUMN,
        \CPSIT\DenaCharts\Domain\Model\ChartConfiguration::CHART_TYPE_LINE,
    ])),
    'after:denacharts_color_scheme',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    implode(',', [
        'denacharts_show_gridlines',
        '--div--;LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tabs.chartdata',
        'denacharts_data',
        '--palette--;;chart_axis_y2',
    ]),
    'denacharts_chart_' . \CPSIT\DenaCharts\Domain\Model\ChartConfiguration::CHART_TYPE_AREA,
    'after:denacharts_show_datatable',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    implode(',', [
        'denacharts_show_points',
        '--div--;LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:tabs.chartdata',
        'denacharts_data',
        'denacharts_highlights',
    ]),
    'denacharts_chart_' . \CPSIT\DenaCharts\Domain\Model\ChartConfiguration::CHART_TYPE_LINE,
    'after:denacharts_show_datatable',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    'denacharts_stack',
    implode(',', array_map(fn (string $chartType) => 'denacharts_chart_' . $chartType, [
        \CPSIT\DenaCharts\Domain\Model\ChartConfiguration::CHART_TYPE_COLUMN,
        \CPSIT\DenaCharts\Domain\Model\ChartConfiguration::CHART_TYPE_BAR,
    ])),
    'after:--palette--;;chart_imagesize',
);
