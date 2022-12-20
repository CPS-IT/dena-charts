<?php

namespace CPSIT\DenaCharts\Service;

use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use ZipStream\Option\Archive;
use ZipStream\ZipStream;

class ChartDownloadService
{
    public function streamChartZip(FileReference $file, ?string $source, string $basename = '')
    {
        $downloadFilename = $basename ?: $file->getNameWithoutExtension();

        $options = new Archive();
        $options->setSendHttpHeaders(true);

        $zipFilename = $downloadFilename . '.zip';
        $zip = new ZipStream($zipFilename, $options);

        if (is_string($source) && !empty($source)) {
            $sourceFilename = LocalizationUtility::translate('source', 'dena_charts') . '.txt';
            $zip->addFile($sourceFilename, $source);
        }

        $csvFilename = $downloadFilename . '.csv';
        $zip->addFileFromPath($csvFilename, $file->getForLocalProcessing(false));

        $zip->finish();
    }
}
