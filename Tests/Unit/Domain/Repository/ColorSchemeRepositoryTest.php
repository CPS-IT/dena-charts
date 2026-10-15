<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Tests\Unit\Domain\Repository;

use CPSIT\DenaCharts\Domain\Repository\ColorSchemeRepository;
use CPSIT\DenaCharts\Service\ColorSchemaFileService;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;
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
            public function __construct(private readonly string $file) {}

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
        self::assertArrayHasKey('dena-corporate-design', $colorSchemes);
        self::assertArrayHasKey('dena-orange', $colorSchemes);

        $cd = $colorSchemes['dena-corporate-design'];
        self::assertEquals('dena-corporate-design', $cd->getId());
        $cdColors = $cd->getColors();
        self::assertCount(9, $cdColors);

        $firstCdColor = $cdColors[0];
        self::assertEquals('Helles Orange', $firstCdColor->getId());
        self::assertEquals('#fbba00', $firstCdColor->getValue());
    }

    #[Test]
    public function usesDefaultFilePathWithoutPageAndRequest(): void
    {
        $subject = $this->createSubject();

        self::assertSame(ColorSchemaFileService::DEFAULT_PATH, $subject->getColorSchemesFilePath());
    }

    #[Test]
    public function usesFilePathOfSiteInRequest(): void
    {
        $site = new Site('main', 1, ['settings' => ['denaCharts' => ['colorSchemeFile' => 'EXT:site/colors.json']]]);
        $request = (new ServerRequest('https://example.com/'))->withAttribute('site', $site);
        $subject = $this->createSubject();

        $subject->setColorSchemesFilePath(0, $request);

        self::assertSame('EXT:site/colors.json', $subject->getColorSchemesFilePath());
    }

    private function createSubject(): ColorSchemeRepository
    {
        return new ColorSchemeRepository(new ColorSchemaFileService($this->createMock(SiteFinder::class)));
    }
}
