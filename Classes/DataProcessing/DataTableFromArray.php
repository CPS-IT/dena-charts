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
use CPSIT\DenaCharts\Domain\Factory\DataTableFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 * Class DataTableFactoryFromArray
 * Creates a DataTable object from CSV data
 */
class DataTableFromArray implements DataProcessorInterface
{
    use TypoScriptServiceTrait;

    const DATA_TABLE_KEY = 'dataTable';

    protected DataTableFactory $dataTableFactory;

    public function __construct(DataTableFactory $dataTableFactory)
    {
        $this->dataTableFactory = $dataTableFactory;
    }

    /**
     * Process data: Reads CSV data from processedData array, builds a DataTable object from it and
     * stores it under a new key in the processedData
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
        if (
            !empty($processedData[FileReaderCSV::CSV_DATA_KEY])
            && is_array($processedData[FileReaderCSV::CSV_DATA_KEY])
        ) {
            $configuration = $this->typoScriptService->convertTypoScriptArrayToPlainArray(
                $processorConfiguration
            );
            $processedData[static::DATA_TABLE_KEY] = $this->dataTableFactory->fromArray(
                $processedData[FileReaderCSV::CSV_DATA_KEY], $configuration
            );
        }

        return $processedData;
    }
}
