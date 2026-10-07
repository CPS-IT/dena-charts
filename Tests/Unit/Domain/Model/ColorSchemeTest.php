<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Tests\Unit\Domain\Model;

use CPSIT\DenaCharts\Domain\Model\Color;
use CPSIT\DenaCharts\Domain\Model\ColorScheme;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ColorSchemeTest extends TestCase
{
    public static function colorDataProvider(): array
    {
        return [
            [[]],
            [[new Color('A1', 'black'), new Color('A2', 'green')]],
        ];
    }

    #[DataProvider('colorDataProvider')]
    public function testGetAllColors(array $colors): void
    {
        $colorScheme = new ColorScheme('test', $colors);
        $resultColors = $colorScheme->getColors();
        self::assertCount(count($colors), $resultColors);
    }

    public static function someColorsDataProvider(): array
    {
        return [
            [
                [
                    new Color('A1', 'black'),
                    new Color('A2', 'green'),
                    new Color('A3', 'red'),
                ],
                ['A1', 'A2']
            ]
        ];
    }

    #[DataProvider('someColorsDataProvider')]
    public function testGetSomeColors(array $colors, array $idsToGet): void
    {
        $colorScheme = new ColorScheme('test', $colors);

        $resultColors = $colorScheme->getColors($idsToGet);

        $this->assertCount(count($idsToGet), $resultColors);
        foreach ($idsToGet as $index => $id) {
            $this->assertEquals($id, $resultColors[$index]->getId());
        }
    }

    public function testGetColorsInvalid(): void
    {
        $colorScheme = new ColorScheme('test', [new Color('abc', '123')]);

        $this->expectException(\InvalidArgumentException::class);

        $colorScheme->getColors(['doesnotexist', 'abc']);
    }
}
