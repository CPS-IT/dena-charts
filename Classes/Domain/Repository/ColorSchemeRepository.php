<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Domain\Repository;

use CPSIT\DenaCharts\Domain\Model\Color;
use CPSIT\DenaCharts\Domain\Model\ColorScheme;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

class ColorSchemeRepository
{
    /**
     * @return ColorScheme[]
     */
    public function findAll(): array
    {
        $path = ExtensionManagementUtility::extPath('dena_charts') . '/Resources/Private/colorschemes.json';
        $content = json_decode(file_get_contents($path));

        $schemes = [];
        foreach ($content as $schemeId => $colorsData) {
            $colors = [];
            foreach ($colorsData as $colorId => $hexCode) {
                $colors[] = new Color($colorId, $hexCode);
            }
            $schemes[$schemeId] = new ColorScheme($schemeId, $colors);
        }
        return $schemes;
    }

    public function findById(string $colorSchemeId): ?ColorScheme
    {
        return $this->findAll()[$colorSchemeId] ?? null;
    }
}
