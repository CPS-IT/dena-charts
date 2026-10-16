<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Tests\Functional\Controller;

use CPSIT\DenaCharts\Controller\ChartController;
use CPSIT\DenaCharts\Domain\Model\Chart;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Resource\StorageRepository;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\TypoScript\AST\Node\RootNode;
use TYPO3\CMS\Core\TypoScript\FrontendTypoScript;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewInterface;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class ChartControllerTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['cpsit/dena-charts'];

    protected function setUp(): void
    {
        parent::setUp();
        $this->importCSVDataSet(dirname(__DIR__) . '/Fixtures/Database/pages.csv');
    }

    /**
     * @param array<string, mixed> $row
     * @param array<string, mixed> $assigned
     */
    private function runChartAction(array $row, array &$assigned): ResponseInterface
    {
        $site = new Site('test', 1, [
            'base' => '/',
            'settings' => ['denaCharts' => ['colorSchemeFile' => 'EXT:dena_charts/Tests/Functional/Fixtures/Files/colorschemes.json']],
            'languages' => [
                ['languageId' => 0, 'title' => 'Deutsch', 'locale' => 'de_DE.UTF-8', 'base' => '/'],
            ],
        ]);
        $contentObject = new ContentObjectRenderer();
        $contentObject->data = $row;
        $frontendTypoScript = new FrontendTypoScript(new RootNode(), [], [], []);
        $frontendTypoScript->setSetupArray([]);
        $serverRequest = (new ServerRequest('https://example.com/'))
            ->withAttribute('frontend.typoscript', $frontendTypoScript)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE)
            ->withAttribute('site', $site)
            ->withAttribute('language', $site->getDefaultLanguage())
            ->withAttribute('extbase', new ExtbaseRequestParameters())
            ->withAttribute('currentContentObject', $contentObject);

        $view = $this->createMock(ViewInterface::class);
        $view->method('assignMultiple')->willReturnCallback(
            static function (array $values) use (&$assigned, $view): ViewInterface {
                $assigned = $values;

                return $view;
            }
        );
        $view->method('render')->willReturn('<html></html>');
        $this->get(ConfigurationManagerInterface::class)->setRequest($serverRequest);
        $controller = GeneralUtility::makeInstance(ChartController::class);
        $this->setProperty($controller, 'request', new Request($serverRequest));
        $this->setProperty($controller, 'view', $view);
        $this->setProperty($controller, 'settings', [
            'chartJsDefaults' => [
                'column' => [
                    'builder' => \CPSIT\DenaCharts\Domain\Builder\ChartBuilder\BarChartBuilder::class,
                    'type' => 'bar',
                    'options' => [],
                ],
            ],
        ]);

        return $controller->chartAction();
    }

    #[Test]
    public function chartActionBuildsChartFromContentElementWithLanguageAndColorSchemeOfRequest(): void
    {
        $assigned = [];
        $response = $this->runChartAction($this->createChartContentElement(), $assigned);

        self::assertSame(200, $response->getStatusCode());
        self::assertInstanceOf(Chart::class, $assigned['chart']);
        $chart = $assigned['chart'];
        self::assertSame('bar', $chart->getType());
        self::assertSame('de-DE', $chart->getOptions()['locale']);
        self::assertSame(['Gebäude A', 'Gebäude B'], array_column($chart->getData()['datasets'], 'label'));
        self::assertSame('#123456', $chart->getData()['datasets'][0]['backgroundColor']);
        self::assertSame('Quelle', $assigned['source']);
    }

    #[Test]
    public function chartActionTreatsMissingHighlightsAsEmptyList(): void
    {
        $row = $this->createChartContentElement();
        $row['denacharts_highlights'] = null;
        $assigned = [];

        $response = $this->runChartAction($row, $assigned);

        self::assertSame(200, $response->getStatusCode());
        self::assertSame([], $assigned['chart']->getOptions()['highlights'] ?? []);
    }

    /**
     * @return array<string, mixed>
     */
    private function createChartContentElement(): array
    {
        $storageRepository = $this->get(StorageRepository::class);
        $storage = $storageRepository->findByUid($storageRepository->createLocalStorage('fileadmin', 'fileadmin/', 'relative'));
        $file = $storage->addFile(
            dirname(__DIR__) . '/Fixtures/Files/chart.csv',
            $storage->getRootLevelFolder(),
            'chart.csv',
            removeOriginal: false,
        );

        $row = [
            'uid' => 1,
            'pid' => 1,
            'CType' => 'denacharts_chart_column',
            'denacharts_data_file' => 1,
            'denacharts_color_scheme' => 'dena-corporate-design',
            'denacharts_colors' => '',
            'denacharts_highlights' => '',
            'denacharts_download_filename' => '',
            'denacharts_allow_download' => 1,
            'denacharts_show_datatable' => 0,
            'denacharts_source' => 'Quelle',
            'denacharts_source_link' => '',
            'bodytext' => '',
        ];
        $connectionPool = $this->get(ConnectionPool::class);
        $connectionPool->getConnectionForTable('tt_content')->insert('tt_content', $row);
        $connectionPool->getConnectionForTable('sys_file_reference')->insert('sys_file_reference', [
            'uid' => 1,
            'pid' => 1,
            'uid_local' => $file->getUid(),
            'uid_foreign' => 1,
            'tablenames' => 'tt_content',
            'fieldname' => 'denacharts_data_file',
        ]);

        return $row;
    }

    private function setProperty(object $object, string $name, mixed $value): void
    {
        (new \ReflectionProperty(ChartController::class, $name))->setValue($object, $value);
    }
}
