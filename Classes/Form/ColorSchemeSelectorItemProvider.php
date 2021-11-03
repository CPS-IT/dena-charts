<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Form;

use CPSIT\DenaCharts\Domain\Model\Color;
use CPSIT\DenaCharts\Domain\Model\ColorScheme;
use CPSIT\DenaCharts\Domain\Repository\ColorSchemeRepository;

class ColorSchemeSelectorItemProvider
{
    protected ColorSchemeRepository $colorSchemeRepository;

    public function __construct(ColorSchemeRepository $colorSchemeRepository)
    {
        $this->colorSchemeRepository = $colorSchemeRepository;
    }

    public function provideColorSchemeSelectorItems(&$params): void
    {
        $params['items'] = array_values(array_map(
            fn(ColorScheme $colorScheme) => [$colorScheme->getId(), $colorScheme->getId()],
            $this->colorSchemeRepository->findAll()
        ));
    }

    public function provideColorSelectorItems(&$params): void
    {
        $row = $params['row'];
        if (!isset($row['denacharts_color_scheme'][0])) {
            return;
        }
        $colorSchemeId = $row['denacharts_color_scheme'][0];
        $colorScheme = $this->colorSchemeRepository->findById($colorSchemeId);
        $result = array_map(
            fn(Color $color) => [sprintf('%s (%s)', $color->getId(), $color->getValue()), $color->getId()],
            $colorScheme->getColors()
        );
        $params['items'] = $result;
    }
}
