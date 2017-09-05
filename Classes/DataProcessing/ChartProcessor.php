<?php

namespace CPSIT\DenaCharts\DataProcessing;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2017 Dirk Wenzel <wenzel@cps-it.de>
 *  All rights reserved
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the text file GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\TypoScriptService;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 * Class ChartProcessor
 */
class ChartProcessor implements DataProcessorInterface
{
    /**
     * Type 'Bar Chart'
     */
    const CHART_TYPE_BAR = 'bar';

    /**
     * Type 'Line Chart'
     */
    const CHART_TYPE_LINE = 'line';

    /**
     * Type 'Doughnut Chart'
     */
    const CHART_TYPE_DOUGHNUT = 'doughnut';

    /**
     * Type 'Pie Chart'
     */
    const CHART_TYPE_PIE = 'pie';

    /**
     * Type 'Radar Chart'
     */
    const CHART_TYPE_RADAR = 'radar';

    /**
     * @var FileRepository
     */
    protected $fileRepository;

    /**
     * @var TypoScriptService
     */
    protected $typoScriptService;

    /**
     *  Default JavaScript Library Provider
     */
    const DEFAULT_LIBRARY_PROVIDER = 'chartjs.org';

    /**
     * Name of the data file field (containing a reference to a CSV file)
     */
    const DATA_FILE_FIELD = 'denacharts_data_file';

    /**
     * Prefix for the canvas id property
     */
    const CANVAS_ID_PREFIX = 'tx-dena-charts-canvas-';

    /**
     * Prefix for the variable name in which the chart object is saved
     */
    const CHART_VARIABLE_PREFIX = 'txDenaChartsChart';

    /**
     * Template for the canvas tag
     */
    const CANVAS_TEMPLATE = '<canvas id="%1s" width="%2$u" height="%3$u"></canvas>';

    /**
     * Template for the JavaScript tag
     */
    const CHART_JS_TEMPLATE = '<script type="text/javascript"> 
    var ctx = document.getElementById("%1s").getContext("2d");
    
    var %2s= new Chart(ctx, {
        type: \'%3s\',
        data: %4s,
        options: %5s
    });
    </script>';

    /**
     * ChartProcessor constructor.
     */
    public function __construct()
    {
        $this->fileRepository = GeneralUtility::makeInstance(FileRepository::class);
        $this->typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);
    }

    /**
     * Process data for the content element "Chart"
     *
     * @param ContentObjectRenderer $cObj The data of the content element or page
     * @param array $contentObjectConfiguration The configuration of Content Object
     * @param array $processorConfiguration The configuration of this processor
     * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
     * @return array the processed data as key/value store
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    )
    {
        $configuration = $this->typoScriptService->convertTypoScriptArrayToPlainArray(
            $processorConfiguration
        );
        $contentElementData = $processedData['data'];

        $type = $contentElementData['denacharts_type'];
        $csvData = $this->getData($contentElementData);
        $headers = $csvData[0];
        array_shift($csvData);

        $dataSets = [];
        foreach ($csvData as $index => $row) {
            $set = [
                // label might not be appropriate for all chart types
                'label' => $contentElementData['header'],
                'data' => $row
            ];
            // add configuration for backgroundColor, borderColor etc. for each data set
            if (!empty($configuration['default'][$type]['datasets'][$index]
                && is_array($configuration['default'][$type]['datasets'][$index]))) {
                foreach ($configuration['default'][$type]['datasets'][$index] as $key => $value) {
                    if (strpos($value, '|') !== false) {
                        $value = GeneralUtility::trimExplode('|', $value);
                    }
                    $set[$key] = $value;
                }
            }
            $dataSets[] = $set;
        }
        $data = json_encode([
            'labels' => $headers,
            'datasets' => $dataSets,
        ]);
        $options = json_encode($configuration['default'][$type]['options']);

        $canvasId = static::CANVAS_ID_PREFIX . $contentElementData['uid'];
        $chartVariableName = static::CHART_VARIABLE_PREFIX . $contentElementData['uid'];
        $javaScript = sprintf(
            static::CHART_JS_TEMPLATE,
            $canvasId,
            $chartVariableName,
            $type,
            $data,
            $options
        );
        $processedData = [
            'canvas' => $this->getCanvasTag($contentElementData),
            'elementData' => $contentElementData,
            'chartScript' => $javaScript
        ];


        return $processedData;
    }

    /**
     * Returns a canvas tag for the chart.
     *
     * @param $contentElementData
     * @return string canvas HTML tag
     */
    protected function getCanvasTag($contentElementData)
    {
        $ratio = GeneralUtility::trimExplode(':', $contentElementData['denacharts_aspect_ratio'], true);
        $canvasWidth = $ratio[0];
        $canvasHeight = $ratio[1];
        $canvasId = static::CANVAS_ID_PREFIX . $contentElementData['uid'];

        $canvas = sprintf(static::CANVAS_TEMPLATE, $canvasId, $canvasWidth, $canvasHeight);
        return $canvas;
    }

    /**
     * Returns the raw data from the data file (a CSV file) as array
     *
     * @param $contentElementData
     * @return array Array of records. First row contains headers.
     */
    protected function getData($contentElementData)
    {
        $fileContent = '';
        $files = $this->fileRepository->findByRelation('tt_content', 'denacharts_data_file', $contentElementData['uid']);
        if (!empty($files)) {
            /** @var FileReference $file */
            $file = $files[0];
            $fileContent = rtrim($file->getContents());
        }
        $delimiter = ',';
        $enclosure = '"';
        $escape = "\\";

        $rows = array_filter(str_getcsv($fileContent, "\n"));

        $records = array_map(function ($d) use ($delimiter, $enclosure, $escape) {
            return str_getcsv($d, $delimiter, $enclosure, $escape);
        }, $rows);

        return $records;
    }
}
