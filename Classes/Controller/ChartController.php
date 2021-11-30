<?php

namespace CPSIT\DenaCharts\Controller;

use CPSIT\DenaCharts\Service\ChartDownloadService;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\ContentObject\ContentDataProcessor;

class ChartController extends ActionController
{
    protected ChartDownloadService  $chartDownloadService;

    protected ContentDataProcessor $contentDataProcessor;

    protected FileRepository $fileRepository;

    protected TypoScriptService $typoScriptService;

    public function __construct(
        ChartDownloadService $chartDownloadService,
        ContentDataProcessor $contentDataProcessor,
        FileRepository $fileRepository,
        TypoScriptService $typoScriptService
    ) {
        $this->chartDownloadService = $chartDownloadService;
        $this->contentDataProcessor = $contentDataProcessor;
        $this->fileRepository = $fileRepository;
        $this->typoScriptService = $typoScriptService;
    }

    public function chartAction()
    {
        $contentObjectRenderer = $this->configurationManager->getContentObject();
        $contentObjectData = $contentObjectRenderer->data;

        $file = $this->getFile($contentObjectData['uid']);

        // as a legacy, most of the  logic of this action is implemented as fluid data processors. Run those processors
        $configuration = $this->configurationManager->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_FRAMEWORK);
        $viewConfiguration = $this->typoScriptService->convertPlainArrayToTypoScriptArray($configuration['view']);
        $variables = $this->contentDataProcessor->process(
            $contentObjectRenderer,
            $viewConfiguration,
            [
                'data' => $contentObjectData,
                'file' => $file,
            ]
        );

        $this->view->assignMultiple($variables);
    }

    public function downloadAction()
    {
        $contentObjectData = $this->configurationManager->getContentObject()->data;
        $source = $contentObjectData['denacharts_source'];
        $file = $this->getFile($contentObjectData['uid']);

        $this->chartDownloadService->streamChartZip($file, $source);
        exit(0);
    }

    protected function getFile(int $contentElementUid): ?FileReference
    {
        $files = $this->fileRepository->findByRelation('tt_content', 'denacharts_data_file', $contentElementUid);
        if (empty($files)) {
            return null;
        }
        return $files[0];
    }
}
