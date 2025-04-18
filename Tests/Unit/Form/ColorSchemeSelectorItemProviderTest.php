<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Tests\Unit\Form;

use CPSIT\DenaCharts\Domain\Model\Color;
use CPSIT\DenaCharts\Domain\Model\ColorScheme;
use CPSIT\DenaCharts\Domain\Repository\ColorSchemeRepository;
use CPSIT\DenaCharts\Form\ColorSchemeSelectorItemProvider;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class ColorSchemeSelectorItemProviderTest extends UnitTestCase
{
    protected ColorSchemeSelectorItemProvider $colorSchemeSelectorItemProvider;

    protected function setUp(): void
    {
        parent::setUp();
        $colorSchemeRepository = $this->createMock(ColorSchemeRepository::class);
        $colorSchemeRepository
            ->method('findById')
            ->with('testscheme B')
            ->willReturn(
                new ColorScheme('testscheme B', [
                    new Color('B1', 'black'),
                    new Color('B2', 'yellow'),
                ]),
            );
        $colorSchemeRepository
            ->method('findAll')
            ->willReturn([
                new ColorScheme('testscheme A', [
                    new Color('A1', 'white'),
                ]),
                new ColorScheme('testscheme B', [
                    new Color('B1', 'black'),
                    new Color('B2', 'yellow'),
                ]),
            ]);
        $this->colorSchemeSelectorItemProvider = new ColorSchemeSelectorItemProvider($colorSchemeRepository);
    }

    public function testProvideColorSchemeSelectorItems(): void
    {
        $params = ['row' => ['pid' => 0]];
        $this->colorSchemeSelectorItemProvider->provideColorSchemeSelectorItems($params);

        $this->assertEquals([
            ['testscheme A', 'testscheme A'],
            ['testscheme B', 'testscheme B'],
        ], $params['items']);
    }


    public function testProvideColorSelectorItems(): void
    {
        $params = ['row' => ['denacharts_color_scheme' => ['testscheme B']]];

        $this->colorSchemeSelectorItemProvider->provideColorSelectorItems($params);

        $this->assertIsArray($params['items']);
        $this->assertCount(2, ($params['items']));
        $this->assertEquals('B2', ($params['items'][1][1]));
        $this->assertEquals('B2 (yellow)', ($params['items'][1][0]));
    }
}
