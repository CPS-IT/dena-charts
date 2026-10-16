<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Tests\Unit\Configuration;

use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class IconsTest extends UnitTestCase
{
    /**
     * @return array<string, array{provider: string, source: string}>
     */
    private function loadIcons(): array
    {
        return require dirname(__DIR__, 3) . '/Configuration/Icons.php';
    }

    #[Test]
    public function registersOneSvgIconPerChartType(): void
    {
        $icons = $this->loadIcons();

        self::assertSame(
            array_map(static fn(string $type): string => 'dena_charts-ctype-denacharts_chart_' . $type, ['area', 'bar', 'column', 'doughnut', 'line', 'pie']),
            array_keys($icons),
        );
        foreach ($icons as $icon) {
            self::assertSame(SvgIconProvider::class, $icon['provider']);
        }
    }

    #[Test]
    public function iconSourceFilesExist(): void
    {
        foreach ($this->loadIcons() as $icon) {
            self::assertFileExists(str_replace('EXT:dena_charts/', dirname(__DIR__, 3) . '/', $icon['source']));
        }
    }
}
