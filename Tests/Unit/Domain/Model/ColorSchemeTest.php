<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Tests\Unit\Domain\Model;

use CPSIT\DenaCharts\Domain\Model\Color;
use CPSIT\DenaCharts\Domain\Model\ColorScheme;
use PHPUnit\Framework\TestCase;

class ColorSchemeTest extends TestCase
{
    public function colorDataProvider(): array
    {
        return [
            [[]],
            [[new Color('A1', 'black'), new Color('A2', 'green')]],
        ];
    }

    /**
     * @dataProvider colorDataProvider
     */
    public function testGetAllColors(array $colors)
    {
        $colorScheme = new ColorScheme('test', $colors);
        $resultColors = $colorScheme->getColors();
        self::assertCount(count($colors), $resultColors);
    }

    public function someColorsDataProvider(): array
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

    /**
     * @dataProvider someColorsDataProvider
     */
    public function testGetSomeColors(array $colors, array $idsToGet)
    {
        $colorScheme = new ColorScheme('test', $colors);

        $resultColors = $colorScheme->getColors($idsToGet);

        $this->assertCount(count($idsToGet), $resultColors);
        foreach ($idsToGet as $index => $id) {
            $this->assertEquals($id, $resultColors[$index]->getId());
        }
    }

    public function testGetColorsInvalid()
    {
        $colorScheme = new ColorScheme('test', [new Color('abc', '123')]);

        $this->expectException(\InvalidArgumentException::class);

        $colorScheme->getColors(['doesnotexist', 'abc']);
    }
}
