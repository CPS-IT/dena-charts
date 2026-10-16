<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Tests\Unit\Domain\Model;

use CPSIT\DenaCharts\Domain\Model\Color;
use CPSIT\DenaCharts\Domain\Model\ColorScheme;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ColorSchemeTest extends TestCase
{
    /**
     * @return array<int, array<int, Color[]>>
     */
    public static function colorDataProvider(): array
    {
        return [
            [[]],
            [[new Color('A1', 'black'), new Color('A2', 'green')]],
        ];
    }

    /**
     * @param Color[] $colors
     */
    #[DataProvider('colorDataProvider')]
    public function testGetAllColors(array $colors): void
    {
        $colorScheme = new ColorScheme('test', $colors);
        $resultColors = $colorScheme->getColors();
        self::assertCount(count($colors), $resultColors);
    }

    /**
     * @return array<int, array{Color[], string[]}>
     */
    public static function someColorsDataProvider(): array
    {
        return [
            [
                [
                    new Color('A1', 'black'),
                    new Color('A2', 'green'),
                    new Color('A3', 'red'),
                ],
                ['A1', 'A2'],
            ],
        ];
    }

    /**
     * @param Color[] $colors
     * @param string[] $idsToGet
     */
    #[DataProvider('someColorsDataProvider')]
    public function testGetSomeColors(array $colors, array $idsToGet): void
    {
        $colorScheme = new ColorScheme('test', $colors);

        $resultColors = $colorScheme->getColors($idsToGet);

        self::assertCount(count($idsToGet), $resultColors);
        foreach ($idsToGet as $index => $id) {
            self::assertEquals($id, $resultColors[$index]->getId());
        }
    }

    public function testGetColorsInvalid(): void
    {
        $colorScheme = new ColorScheme('test', [new Color('abc', '123')]);

        $this->expectException(\InvalidArgumentException::class);

        $colorScheme->getColors(['doesnotexist', 'abc']);
    }
}
