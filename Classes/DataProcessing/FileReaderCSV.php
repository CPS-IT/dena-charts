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
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 * Class FileReaderCSV
 * Reads CSV data from a given file (relation) and returns them as array
 */
class FileReaderCSV implements DataProcessorInterface
{
    const CSV_DATA_KEY = 'csvData';

    protected TypoScriptService $typoScriptService;

    public function __construct(
        TypoScriptService $typoScriptService
    )
    {
        $this->typoScriptService = $typoScriptService;
    }

    /**
     * Process data: Reads CSV data from a file relation
     * Per default a single file will be retrieved from file repository determined by
     *
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
        if ($processedData['file'] instanceof FileReference) {
            $processedData[static::CSV_DATA_KEY] = $this->getData($processedData['file'], $configuration);
        }

        return $processedData;
    }

    /**
     * Returns the raw data from the data file (a CSV file) as array
     *
     * @param FileReference $file
     * @param array $configuration
     * @return array Array of records. First row contains headers.
     */
    protected function getData(FileReference $file, array $configuration): array
    {
        $fileContentRaw = rtrim($file->getContents());
        $fileContent = $this->fixEncoding($fileContentRaw);
        $delimiter = ';';
        $enclosure = '"';
        $escape = "\\";

        $rows = array_filter(str_getcsv($fileContent, "\n"));

        $records = array_map(function ($d) use ($delimiter, $enclosure, $escape) {
            return str_getcsv($d, $delimiter, $enclosure, $escape);
        }, $rows);

        return $records;
    }

    /**
     * @param string $fileContentRaw
     * @return string UTF-8 encoded string
     */
    protected function fixEncoding(string $fileContentRaw)
    {
        $encoding = mb_detect_encoding($fileContentRaw, 'UTF-8, ISO-8859-1', true);
        $fileContent = mb_convert_encoding($fileContentRaw, 'UTF-8', $encoding);
        return $fileContent;
    }
}
