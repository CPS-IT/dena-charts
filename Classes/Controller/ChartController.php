<?php

namespace CPSIT\DenaCharts\Controller;

use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\ContentObject\ContentDataProcessor;

class ChartController extends ActionController
{
    protected ContentDataProcessor $contentDataProcessor;

    protected TypoScriptService $typoScriptService;

    public function __construct(
        ContentDataProcessor $contentDataProcessor,
        TypoScriptService $typoScriptService
    ) {
        $this->contentDataProcessor = $contentDataProcessor;
        $this->typoScriptService = $typoScriptService;
    }

    public function chartAction()
    {
        // as a legacy, most of the  logic of this action is implemented as fluid data processors. Run those processors
        $contentObjectRenderer = $this->configurationManager->getContentObject();
        $configuration = $this->configurationManager->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_FRAMEWORK);
        $viewConfiguration = $this->typoScriptService->convertPlainArrayToTypoScriptArray($configuration['view']);
        $variables = $this->contentDataProcessor->process(
            $contentObjectRenderer,
            $viewConfiguration,
            ['data' => $contentObjectRenderer->data]
        );

        $this->view->assignMultiple($variables);
    }
}
