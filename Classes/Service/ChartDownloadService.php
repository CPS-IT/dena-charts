<?php

namespace CPSIT\DenaCharts\Service;

use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use ZipStream\ZipStream;

class ChartDownloadService
{
    public function streamChartZip(FileReference $file, ?string $source, string $basename = '')
    {
        $downloadFilename = $basename ?: $file->getNameWithoutExtension();

        $zipFilename = $downloadFilename . '.zip';
        $zip = new ZipStream(
            sendHttpHeaders: true,
            // enable output of HTTP headers
            outputName: $zipFilename
        );

        if (is_string($source) && !empty($source)) {
            $sourceFilename = LocalizationUtility::translate('source', 'dena_charts') . '.txt';
            $zip->addFile($sourceFilename, $source);
        }

        $csvFilename = $downloadFilename . '.csv';
        $zip->addFileFromPath($csvFilename, $file->getForLocalProcessing(false));

        $zip->finish();
    }
}
