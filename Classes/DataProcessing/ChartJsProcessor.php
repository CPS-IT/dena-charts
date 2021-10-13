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

use CPSIT\DenaCharts\Common\TypoScriptServiceTrait;
use CPSIT\DenaCharts\Domain\Model\DataColumn;
use CPSIT\DenaCharts\Domain\Model\DataRow;
use CPSIT\DenaCharts\Domain\Model\DataTable;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 * Class ChartJsProcessor
 */
class ChartJsProcessor implements DataProcessorInterface
{
    protected TypoScriptService $typoScriptService;

    /**
     * Type 'Bar Chart'
     */
    const CHART_TYPE_BAR = 'bar';

    /**
     * Type 'Column Chart'
     */
    const CHART_TYPE_COLUMN = 'column';

    /**
     * Type 'Doughnut Chart'
     */
    const CHART_TYPE_DOUGHNUT = 'doughnut';

    /**
     * Type 'Line Chart'
     */
    const CHART_TYPE_LINE = 'line';

    /**
     * Type 'Pie Chart'
     */
    const CHART_TYPE_PIE = 'pie';

    /**
     * Type 'Radar Chart'
     */
    const CHART_TYPE_RADAR = 'radar';

    const CHART_TYPES = [
        self::CHART_TYPE_BAR,
        self::CHART_TYPE_COLUMN,
        self::CHART_TYPE_DOUGHNUT,
        self::CHART_TYPE_LINE,
        self::CHART_TYPE_PIE,
        self::CHART_TYPE_RADAR,
    ];

    public function __construct(TypoScriptService $typoScriptService)
    {
        $this->typoScriptService = $typoScriptService;
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
    ): array
    {
        $configuration = $this->typoScriptService->convertTypoScriptArrayToPlainArray(
            $processorConfiguration
        );
        $contentElementData = $processedData['data'];
        $chartConfiguration = $configuration;

        $data = $this->getChartData($processedData, $chartConfiguration);
        $options = json_encode($chartConfiguration['options']);
        $chartType = $chartConfiguration['type'];

        $processedData = array_replace_recursive($processedData, [
            'chart' => [
                'data' => $data,
                'options' => $options,
                'type' => $chartType
            ],
            'elementData' => $contentElementData,
        ]);

        return $processedData;
    }

    /**
     * @param array $processedData
     * @param array $configuration
     * @return string
     */
    protected function getChartData(array $processedData, ?array $configuration): string
    {
        $data = '';

        if (!empty($processedData[DataTableFromArray::DATA_TABLE_KEY] && $processedData[DataTableFromArray::DATA_TABLE_KEY] instanceof DataTable)) {
            /** @var DataTable $dataTable */
            $dataTable = $processedData[DataTableFromArray::DATA_TABLE_KEY];
            $dataSets = $this->createDataSets($dataTable);

            if (!empty($configuration['datasets'] && is_array($configuration['datasets']))) {
                $dataSets = $this->applyDataSetConfiguration($dataSets, $configuration['datasets']);
            }

            $headers = [];
            /** @var DataColumn $rows */
            foreach ($dataTable->getRows() as $rows) {
                $headers[] = $rows->getLabel();
            }

            $data = json_encode([
                'labels' => $headers,
                'datasets' => $dataSets,
            ]);
        }

        return $data;
    }

    /**
     * Creates data sets from data rows
     * Data sets are array build from DataRow objects.
     * There structure is specific for Chartjs
     *
     * @param DataTable $dataTable
     * @return array
     */
    protected function createDataSets(DataTable $dataTable): array
    {
        $dataColumns = $dataTable->getColumns();
        $dataSets = [];
        /**
         * @var  $column DataRow
         */
        foreach ($dataColumns as $column) {
            $dataSets[] = [
                // label might not be appropriate for all chart types
                'label' => $column->getLabel(),
                'data' => $column->getData()
            ];
        }

        return $dataSets;
    }

    /**
     * Applies a configuration to data sets
     *
     * @param array $dataSets Array of arrays
     * @param array $configuration
     * @return array Array of DataSet objects
     */
    protected function applyDataSetConfiguration(array $dataSets, array $configuration): array
    {
        foreach ($dataSets as $index => &$set) {
            // add configuration for backgroundColor, borderColor etc. for each data set
            if (!empty($configuration[$index]
                && is_array($configuration[$index]))) {
                foreach ($configuration[$index] as $key => $value) {
                    if (strpos($value, '|') !== false) {
                        $value = GeneralUtility::trimExplode('|', $value);
                    }
                    $set[$key] = $value;
                }
            }
        }

        return $dataSets;
    }
}
