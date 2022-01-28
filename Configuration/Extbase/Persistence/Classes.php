<?php
declare(strict_types = 1);

return [
    \CPSIT\DenaCharts\Domain\Model\ChartConfiguration::class => [
        'tableName' => 'tt_content',
        'properties' => [
            'type' => [
                'fieldName' => 'CType',
            ],
            'dataFile' => [
                'fieldName' => 'denacharts_data_file'
            ],
            'axisXTitle' => [
                'fieldName' => 'denacharts_axis_x_title',
            ],
            'axisXUnit' => [
                'fieldName' => 'denacharts_axis_x_unit',
            ],
            'axisY2FirstColumn' => [
                'fieldName' => 'denacharts_axis_y2_first_column',
            ],
            'axisY2Title' => [
                'fieldName' => 'denacharts_axis_y2_title',
            ],
            'axisY2Unit' => [
                'fieldName' => 'denacharts_axis_y2_unit',
            ],
            'axisYTitle' => [
                'fieldName' => 'denacharts_axis_y_title',
            ],
            'axisYUnit' => [
                'fieldName' => 'denacharts_axis_y_unit',
            ],
            'aspectRatio' => [
                'fieldName' => 'denacharts_aspect_ratio',
            ],
            'containerWidth' => [
                'fieldName' => 'denacharts_container_width',
            ],
            'stack' => [
                'fieldName' => 'denacharts_stack',
            ],
            'colorScheme' => [
                'fieldName' => 'denacharts_color_scheme',
            ],
            'colors' => [
                'fieldName' => 'denacharts_colors',
            ],
            'showGridLines' => [
                'fieldName' => 'denacharts_show_gridlines',
            ],
            'showPoints' => [
                'fieldName' => 'denacharts_show_points',
            ],
        ],
    ],
];
