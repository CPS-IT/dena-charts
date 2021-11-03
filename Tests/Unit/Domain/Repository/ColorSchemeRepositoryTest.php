<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Tests\Unit\Domain\Repository;

use CPSIT\DenaCharts\Domain\Repository\ColorSchemeRepository;
use Nimut\TestingFramework\TestCase\UnitTestCase;

class ColorSchemeRepositoryTest extends UnitTestCase
{
    protected ColorSchemeRepository $colorSchemeRepository;

    protected function setUp()
    {
        parent::setUp();
        $this->colorSchemeRepository = new ColorSchemeRepository();
    }


    public function testFindAll()
    {
        $colorSchemes = $this->colorSchemeRepository->findAll();
        self::assertCount(4, $colorSchemes);
        self::arrayHasKey('dena-corporate-design', $colorSchemes);
        self::arrayHasKey('dena-orange', $colorSchemes);

        $cd = $colorSchemes['dena-corporate-design'];
        $this->assertEquals('dena-corporate-design', $cd->getId());
        $cdColors = $cd->getColors();
        self::assertIsArray($cdColors);
        self::assertCount(9, $cdColors);

        $firstCdColor = $cdColors[0];
        $this->assertEquals('1', $firstCdColor->getId());
        $this->assertEquals('#fbba00', $firstCdColor->getValue());
    }
}
