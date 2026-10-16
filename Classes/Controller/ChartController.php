<?php

namespace CPSIT\DenaCharts\Controller;

use CPSIT\DenaCharts\Domain\Builder\ChartBuilder;
use CPSIT\DenaCharts\Domain\Factory\DataTableFactory;
use CPSIT\DenaCharts\Domain\Model\ChartConfiguration;
use CPSIT\DenaCharts\Domain\Repository\ChartConfigurationRepository;
use CPSIT\DenaCharts\Domain\Repository\ColorSchemeRepository;
use CPSIT\DenaCharts\Service\ChartDownloadService;
use CPSIT\DenaCharts\Service\FileReaderCSV;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\NullResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ChartController extends ActionController
{
    protected ChartConfigurationRepository $chartConfigurationRepository;

    protected FileReaderCSV $fileReaderCSV;

    protected DataTableFactory $dataTableFactory;

    protected ChartDownloadService $chartDownloadService;

    protected ColorSchemeRepository $colorSchemeRepository;

    public function __construct(
        ChartConfigurationRepository $chartConfigurationRepository,
        FileReaderCSV $fileReaderCSV,
        DataTableFactory $dataTableFactory,
        ChartDownloadService $chartDownloadService,
        ColorSchemeRepository $colorSchemeRepository
    ) {
        $this->chartConfigurationRepository = $chartConfigurationRepository;
        $this->fileReaderCSV = $fileReaderCSV;
        $this->dataTableFactory = $dataTableFactory;
        $this->chartDownloadService = $chartDownloadService;
        $this->colorSchemeRepository = $colorSchemeRepository;
    }

    public function chartAction(): ResponseInterface
    {
        $contentObjectData = $this->request->getAttribute('currentContentObject')->data;

        $uid = (int)$contentObjectData['uid'];
        /** @var ChartConfiguration $chartConfiguration */
        $chartConfiguration = $this->chartConfigurationRepository->findByUid($uid);
        $csvContents = $this->fileReaderCSV->getData($chartConfiguration->getDataFile());

        $highlightsString = str_replace(["\n", ',', ';'], ',', (string)($contentObjectData['denacharts_highlights'] ?? ''));
        $highlights = GeneralUtility::trimExplode(',', $highlightsString, true);

        $dataTable = $this->dataTableFactory->fromArray($csvContents, $highlights);

        $this->colorSchemeRepository->setColorSchemesFilePath(0, $this->request);

        $builderConfiguration = $this->settings['chartJsDefaults'][$chartConfiguration->getType()];
        $builderClass = $builderConfiguration['builder'];
        if (!is_a($builderClass, ChartBuilder::class, true)) {
            throw new \UnexpectedValueException(
                sprintf('Chart builder "%s" must extend %s', $builderClass, ChartBuilder::class),
                1760600001
            );
        }
        $builder = GeneralUtility::makeInstance($builderClass);
        $chartJsChart = $builder->buildForConfiguration(
            $chartConfiguration,
            $dataTable,
            $this->request->getAttribute('language'),
            $builderConfiguration,
        );

        $pngDownloadBaseName = $contentObjectData['denacharts_download_filename'] ?: $chartConfiguration->getDataFile()->getNameWithoutExtension();
        $pngDownloadFileName = $pngDownloadBaseName . '.png';

        $this->view->assignMultiple([
            'chart' => $chartJsChart,
            'uid' => $uid,
            'chartRatio' => $chartConfiguration->getAspectRatio(),
            'dataTable' => $dataTable,
            'allowDownload' => (bool)$contentObjectData['denacharts_allow_download'],
            'pngDownloadFilename' => $pngDownloadFileName,
            'description' => (string)$contentObjectData['bodytext'],
            'showDataTable' => (bool)$contentObjectData['denacharts_show_datatable'],
            'source' => (string)$contentObjectData['denacharts_source'],
            'sourceLink' => (string)$contentObjectData['denacharts_source_link'],
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
        /** @var ChartConfiguration $chartConfiguration */
        $chartConfiguration = $this->chartConfigurationRepository->findByUid($contentObjectUid);
        $file = $chartConfiguration->getDataFile();

        $basename = $contentObjectData['denacharts_download_filename'] ?? '';
        $this->chartDownloadService->streamChartZip($file, $source, $basename);
        exit(0);
    }
}
