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
        $this->colorSchemeRepository = new ColorSchemeRepository();
    }


    public function testFindAll()
    {
        $this->markTestSkipped(
            'Skipped test, because of `ValueError: Path cannot be empty` due to `EXT:dena_charts/Resources/Private/colorschemes.json`
            is not available in UnitTest context of not running real TYPO3'
        );
        
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
