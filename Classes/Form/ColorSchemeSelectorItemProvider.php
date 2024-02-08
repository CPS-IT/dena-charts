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
        $pid = $this->getRecordPid($params['row']);
        $this->colorSchemeRepository->setColorSchemesFilePath($pid);
        $params['items'] = array_values(array_map(
            fn(ColorScheme $colorScheme) => [$colorScheme->getId(), $colorScheme->getId()],
            $this->colorSchemeRepository->findAll()
        ));
    }

    public function provideColorSelectorItems(&$params): void
    {
        $pid = $this->getRecordPid($params['row']);
        $this->colorSchemeRepository->setColorSchemesFilePath($pid);
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

    /**
     * See TYPO3 Bug https://forge.typo3.org/issues/95814
     */
    private function getRecordPid(array $row): int
    {
        $pid = 0;
        if (isset($row['pid'])) {
            $pid = $row['pid'];
        }

        if ($pid < 0) {
            $parentRec = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord(
                'tt_content',
                abs($pid),
                'pid'
            );
            return $parentRec['pid'];
        }
        return $pid;
    }
}
