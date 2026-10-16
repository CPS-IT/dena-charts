<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Tests\Functional\Configuration;

use CPSIT\DenaCharts\Form\ChartsDataTable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Backend\Form\NodeFactory;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class ChartContentTypesTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['cpsit/dena-charts'];

    /**
     * @return array<string, array{string}>
     */
    public static function chartTypeProvider(): array
    {
        $cTypes = [];
        foreach (['area', 'bar', 'column', 'doughnut', 'line', 'pie'] as $type) {
            $cTypes[$type] = ['denacharts_chart_' . $type];
        }

        return $cTypes;
    }

    #[Test]
    public function extensionIsLoaded(): void
    {
        self::assertTrue(GeneralUtility::makeInstance(PackageManager::class)->isPackageActive('dena_charts'));
    }

    #[Test]
    #[DataProvider('chartTypeProvider')]
    public function chartCTypeIsRegisteredInTca(string $cType): void
    {
        self::assertArrayHasKey($cType, $GLOBALS['TCA']['tt_content']['types']);
        self::assertStringContainsString('denacharts_data_file', $GLOBALS['TCA']['tt_content']['types'][$cType]['showitem']);
    }

    #[Test]
    #[DataProvider('chartTypeProvider')]
    public function chartCTypeIsSelectable(string $cType): void
    {
        $values = array_column($GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'], 'value');

        self::assertContains($cType, $values);
    }

    #[Test]
    #[DataProvider('chartTypeProvider')]
    public function chartCTypeHasRegisteredIcon(string $cType): void
    {
        $identifier = 'dena_charts-ctype-' . $cType;

        self::assertSame($identifier, $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes'][$cType]);
        self::assertTrue(GeneralUtility::makeInstance(IconRegistry::class)->isRegistered($identifier));
    }

    #[Test]
    public function typesUseOnlyExistingLanguageFiles(): void
    {
        foreach (['denacharts_chart_area', 'denacharts_chart_pie'] as $cType) {
            self::assertStringNotContainsString('locallang_ttc.xml', $GLOBALS['TCA']['tt_content']['types'][$cType]['showitem']);
        }
    }

    #[Test]
    public function dataTableNodeIsResolvedWithInjectedService(): void
    {
        $node = GeneralUtility::makeInstance(NodeFactory::class)->create([
            'renderType' => 'dena-charts-data-table',
            'tableName' => 'tt_content',
            'fieldName' => 'denacharts_data',
            'databaseRow' => ['uid' => 1],
            'parameterArray' => ['fieldConf' => ['config' => []]],
        ]);

        self::assertInstanceOf(ChartsDataTable::class, $node);
    }
}
