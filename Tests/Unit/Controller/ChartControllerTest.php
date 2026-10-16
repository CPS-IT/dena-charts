<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Tests\Unit\Controller;

use CPSIT\DenaCharts\Controller\ChartController;
use CPSIT\DenaCharts\Domain\Builder\ChartBuilder;
use CPSIT\DenaCharts\Domain\Factory\DataTableFactory;
use CPSIT\DenaCharts\Domain\Model\Chart;
use CPSIT\DenaCharts\Domain\Model\ChartConfiguration;
use CPSIT\DenaCharts\Domain\Repository\ChartConfigurationRepository;
use CPSIT\DenaCharts\Domain\Repository\ColorSchemeRepository;
use CPSIT\DenaCharts\Service\ChartDownloadService;
use CPSIT\DenaCharts\Service\FileReaderCSV;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Core\Http\ResponseFactory;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Http\StreamFactory;
use TYPO3\CMS\Core\Http\Uri;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewInterface;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class ChartControllerTest extends UnitTestCase
{
    protected bool $resetSingletonInstances = true;

    private ColorSchemeRepository&MockObject $colorSchemeRepository;

    private ChartController $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $configuration = $this->createMock(ChartConfiguration::class);
        $configuration->method('getType')->willReturn('column');
        $configuration->method('getAspectRatio')->willReturn('');
        $configuration->method('getDataFile')->willReturn($this->createMock(FileReference::class));
        $configurationRepository = $this->createMock(ChartConfigurationRepository::class);
        $configurationRepository->method('findByUid')->willReturn($configuration);
        $fileReader = $this->createMock(FileReaderCSV::class);
        $fileReader->method('getData')->willReturn([['', 'A'], ['x', '1']]);
        $this->colorSchemeRepository = $this->createMock(ColorSchemeRepository::class);

        $this->subject = new ChartController(
            $configurationRepository,
            $fileReader,
            new DataTableFactory(),
            $this->createMock(ChartDownloadService::class),
            $this->colorSchemeRepository,
        );
        $this->subject->injectResponseFactory(new ResponseFactory());
        $this->subject->injectStreamFactory(new StreamFactory());
    }

    #[Test]
    public function chartActionBuildsChartWithLanguageOfRequest(): void
    {
        $language = new SiteLanguage(1, 'de_DE.UTF-8', new Uri('/'), []);
        $this->prepareController($this->createRequest()->withAttribute('language', $language));

        $builder = $this->createMock(ChartBuilder::class);
        $builder->expects($this->once())->method('buildForConfiguration')
            ->with(self::anything(), self::anything(), self::identicalTo($language), self::anything())
            ->willReturn(new Chart('bar', [], []));
        GeneralUtility::addInstance(ChartBuilder::class, $builder);

        $response = $this->subject->chartAction();

        self::assertSame(200, $response->getStatusCode());
    }

    #[Test]
    public function chartActionProvidesRequestToColorSchemeRepository(): void
    {
        $request = $this->createRequest()
            ->withAttribute('site', new Site('main', 1, []))
            ->withAttribute('language', new SiteLanguage(0, 'en_US.UTF-8', new Uri('/'), []));
        $this->prepareController($request);
        $this->colorSchemeRepository->expects($this->once())->method('setColorSchemesFilePath')
            ->with(0, self::identicalTo($this->getRequest()));
        $builder = $this->createMock(ChartBuilder::class);
        $builder->method('buildForConfiguration')->willReturn(new Chart('bar', [], []));
        GeneralUtility::addInstance(ChartBuilder::class, $builder);

        $this->subject->chartAction();
    }

    private function createRequest(): ServerRequest
    {
        $data = [
            'uid' => 5,
            'denacharts_highlights' => '',
            'denacharts_download_filename' => '',
            'denacharts_allow_download' => 1,
            'bodytext' => '',
            'denacharts_show_datatable' => 0,
            'denacharts_source' => 'source',
            'denacharts_source_link' => '',
        ];
        $contentObject = new ContentObjectRenderer();
        $contentObject->data = $data;

        return (new ServerRequest('https://example.com/'))
            ->withAttribute('extbase', new ExtbaseRequestParameters())
            ->withAttribute('currentContentObject', $contentObject);
    }

    private function prepareController(ServerRequest $serverRequest): void
    {
        $view = $this->createMock(ViewInterface::class);
        $view->method('render')->willReturn('<html></html>');
        $settings = ['chartJsDefaults' => ['column' => ['builder' => ChartBuilder::class, 'type' => 'bar', 'options' => []]]];

        $this->setProperty('request', new Request($serverRequest));
        $this->setProperty('view', $view);
        $this->setProperty('settings', $settings);
    }

    private function getRequest(): Request
    {
        return (new \ReflectionProperty(ChartController::class, 'request'))->getValue($this->subject);
    }

    private function setProperty(string $name, mixed $value): void
    {
        (new \ReflectionProperty(ChartController::class, $name))->setValue($this->subject, $value);
    }
}
