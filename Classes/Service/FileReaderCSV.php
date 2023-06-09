<?php

namespace CPSIT\DenaCharts\Service;

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

/**
 * Class FileReaderCSV
 * Reads CSV data from a given file (relation) and returns them as array
 */
class FileReaderCSV
{
    /**
     * Returns the raw data from the data file (a CSV file) as array
     *
     * @param FileReference $file
     * @return array Array of records. First row contains headers.
     */
    public function getData(FileReference $file): array
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
        $encoding = \mb_detect_encoding($fileContentRaw, 'UTF-8, ISO-8859-1', true);
        $fileContent = \mb_convert_encoding($fileContentRaw, 'UTF-8', $encoding);
        return $fileContent;
    }
}
