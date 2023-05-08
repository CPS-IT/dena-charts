<?php

namespace CPSIT\DenaCharts\Service;

use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class ColorSchemaFileService
{
    const DEFAULT_PATH = 'EXT:dena_charts/Resources/Private/colorschemes.json';

    public function getColorScheme(int $page = 0): string
    {
        $colorSchemeFile = self::DEFAULT_PATH;

        $site = $this->getSiteByPid($page) ?? $this->getSiteFromRequest();

        if (isset($site->getConfiguration()['settings']['denaCharts']['colorSchemeFile'])) {
            $colorSchemeFile = $site->getConfiguration()['settings']['denaCharts']['colorSchemeFile'];
        }
        return $colorSchemeFile;
    }

    public function getSiteByPid(int $page = 0): ?Site
    {
        try {
            return GeneralUtility::makeInstance(SiteFinder::class)->getSiteByPageId($page);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getSiteFromRequest(): ?Site
    {
        $request = $GLOBALS['TYPO3_REQUEST'];
        $site = $request->getAttribute('site');
        return ($site instanceof Site) ? $site : null;
    }
}
