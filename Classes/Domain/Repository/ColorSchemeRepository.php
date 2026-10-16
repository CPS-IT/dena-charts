<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Domain\Repository;

use CPSIT\DenaCharts\Domain\Model\Color;
use CPSIT\DenaCharts\Domain\Model\ColorScheme;
use CPSIT\DenaCharts\Service\ColorSchemaFileService;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ColorSchemeRepository
{
    /**
     * @var string
     */
    protected string $colorSchemesFilePath = '';

    public function __construct(private readonly ColorSchemaFileService $colorSchemaFileService) {}

    /**
     * @return ColorScheme[]
     */
    public function findAll(): array
    {
        $path = GeneralUtility::getFileAbsFileName($this->getColorSchemesFilePath());
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

    public function getColorSchemesFilePath(): string
    {
        if (empty($this->colorSchemesFilePath)) {
            $this->setColorSchemesFilePath(0);
        }
        return $this->colorSchemesFilePath;
    }

    public function setColorSchemesFilePath(int $pid = 0, ?ServerRequestInterface $request = null): void
    {
        $this->colorSchemesFilePath = $this->colorSchemaFileService->getColorScheme($pid, $request);
    }
}
