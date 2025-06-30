<?php

namespace CPSIT\DenaCharts\Controller;

use CPSIT\DenaCharts\Domain\Factory\DataTableFactory;
use CPSIT\DenaCharts\Domain\Model\ChartConfiguration;
use CPSIT\DenaCharts\Domain\Repository\ChartConfigurationRepository;
use CPSIT\DenaCharts\Service\ChartDownloadService;
use CPSIT\DenaCharts\Service\FileReaderCSV;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\NullResponse;

class ChartController extends ActionController
{
    protected ChartConfigurationRepository $chartConfigurationRepository;

    protected FileReaderCSV $fileReaderCSV;

    protected DataTableFactory $dataTableFactory;

    protected ChartDownloadService $chartDownloadService;

    protected SiteLanguage $currentLanguage;

    public function __construct(
        ChartConfigurationRepository $chartConfigurationRepository,
        FileReaderCSV $fileReaderCSV,
        DataTableFactory $dataTableFactory,
        ChartDownloadService $chartDownloadService,
        SiteLanguage $currentLanguage
    ) {
        $this->chartConfigurationRepository = $chartConfigurationRepository;
        $this->fileReaderCSV = $fileReaderCSV;
        $this->dataTableFactory = $dataTableFactory;
        $this->chartDownloadService = $chartDownloadService;
        $this->currentLanguage = $currentLanguage;
    }

    public function chartAction(): ResponseInterface
    {
        $contentObjectData = $this->request->getAttribute('currentContentObject')->data;

        $uid = (int)$contentObjectData['uid'];
        $chartConfiguration = $this->chartConfigurationRepository->findByUid($uid);
        $csvContents = $this->fileReaderCSV->getData($chartConfiguration->getDataFile());

        $highlightsString = str_replace(["\n", ',', ';'], ',', $contentObjectData['denacharts_highlights']);
        $highlights = GeneralUtility::trimExplode(',', $highlightsString, true);

        $dataTable = $this->dataTableFactory->fromArray($csvContents, $highlights);

        $builderConfiguration = $this->settings['chartJsDefaults'][$chartConfiguration->getType()];
        $builder = GeneralUtility::makeInstance($builderConfiguration['builder']);
        $chartJsChart = $builder->buildForConfiguration(
            $chartConfiguration,
            $dataTable,
            $this->currentLanguage,
            $builderConfiguration,
        );

        $pngDownloadBaseName = $contentObjectData['denacharts_download_filename'] ?: $chartConfiguration->getDataFile()->getNameWithoutExtension();
        $pngDownloadFileName = $pngDownloadBaseName . '.png';

        $this->view->assignMultiple([
            'chart' => $chartJsChart,
            'uid' => $uid,
            'chartRatio' => $chartConfiguration->getAspectRatio(),
            'dataTable' => $dataTable,
            'allowDownload' => (bool) $contentObjectData['denacharts_allow_download'],
            'pngDownloadFilename' => $pngDownloadFileName,
            'description' => (string) $contentObjectData['bodytext'],
            'showDataTable' => (bool) $contentObjectData['denacharts_show_datatable'],
            'source' => (string) $contentObjectData['denacharts_source'],
            'sourceLink' => (string) $contentObjectData['denacharts_source_link'],
        ]);
        return $this->htmlResponse();
    }

    public function downloadAction(int $id): ResponseInterface
    {
        $contentObjectData = $this->request->getAttribute('currentContentObject')->data;
        $contentObjectUid = $contentObjectData['uid'];
        if ($contentObjectUid !== $id) {
            return new NullResponse();
        }

        $source = $contentObjectData['denacharts_source'];
        $chartConfiguration = $this->chartConfigurationRepository->findByUid($contentObjectUid);
        $file = $chartConfiguration->getDataFile();

        $basename = $contentObjectData['denacharts_download_filename'] ?? '';
        $this->chartDownloadService->streamChartZip($file, $source, $basename);
        exit(0);
    }
}
