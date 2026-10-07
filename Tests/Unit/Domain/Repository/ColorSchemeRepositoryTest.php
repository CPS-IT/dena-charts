<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Tests\Unit\Domain\Repository;

use CPSIT\DenaCharts\Domain\Repository\ColorSchemeRepository;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class ColorSchemeRepositoryTest extends UnitTestCase
{
    protected ColorSchemeRepository $colorSchemeRepository;

    protected function setUp(): void
    {
        parent::setUp();
        // getFileAbsFileName() only accepts paths below the project or public path
        $target = Environment::getPublicPath() . '/typo3temp/var/tests/colorschemes.json';
        copy(dirname(__DIR__, 4) . '/Resources/Private/colorschemes.json', $target);
        $this->testFilesToDelete[] = $target;
        $this->colorSchemeRepository = new class ($target) extends ColorSchemeRepository {
            public function __construct(private readonly string $file)
            {
            }

            public function getColorSchemesFilePath(): string
            {
                return $this->file;
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
