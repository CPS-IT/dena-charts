<?php

namespace CPSIT\DenaCharts\Service;

use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use ZipStream\Option\Archive;
use ZipStream\ZipStream;

class ChartDownloadService
{
    public function streamChartZip(?FileReference $file, string $source)
    {
        $downloadFilename = $file->getNameWithoutExtension();

        $options = new Archive();
        $options->setSendHttpHeaders(true);

        $zipFilename = $downloadFilename . '.zip';
        $zip = new ZipStream($zipFilename, $options);

        $sourceFilename = LocalizationUtility::translate('source', 'dena_charts') . '.txt';
        $zip->addFile($sourceFilename, $source);

        $csvFilename = $downloadFilename . '.csv';
        $zip->addFileFromPath($csvFilename, $file->getForLocalProcessing(false));

        $zip->finish();
    }
}
