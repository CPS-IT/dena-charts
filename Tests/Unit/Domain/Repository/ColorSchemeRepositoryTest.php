<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Tests\Unit\Domain\Repository;

use CPSIT\DenaCharts\Domain\Repository\ColorSchemeRepository;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class ColorSchemeRepositoryTest extends UnitTestCase
{
    protected ColorSchemeRepository $colorSchemeRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->colorSchemeRepository = new class () extends ColorSchemeRepository {
            public function getColorSchemesFilePath(): string
            {
                return dirname(__DIR__, 4) . '/Resources/Private/colorschemes.json';
            }
        };
    }


    public function testFindAll(): void
    {
        $colorSchemes = $this->colorSchemeRepository->findAll();
        self::assertCount(5, $colorSchemes);
        self::arrayHasKey('dena-corporate-design', $colorSchemes);
        self::arrayHasKey('dena-orange', $colorSchemes);

        $cd = $colorSchemes['dena-corporate-design'];
        $this->assertEquals('dena-corporate-design', $cd->getId());
        $cdColors = $cd->getColors();
        self::assertIsArray($cdColors);
        self::assertCount(9, $cdColors);

        $firstCdColor = $cdColors[0];
        $this->assertEquals('Helles Orange', $firstCdColor->getId());
        $this->assertEquals('#fbba00', $firstCdColor->getValue());
    }
}
