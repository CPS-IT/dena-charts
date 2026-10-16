<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Service;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;

final class ColorSchemaFileService
{
    public const DEFAULT_PATH = 'EXT:dena_charts/Resources/Private/colorschemes.json';

    private const SETTING = 'denaCharts.colorSchemeFile';

    public function __construct(private readonly SiteFinder $siteFinder) {}

    public function getColorScheme(int $page = 0, ?ServerRequestInterface $request = null): string
    {
        $site = $this->getSiteByPid($page) ?? $this->getSiteFromRequest($request);

        return $site?->getSettings()->get(self::SETTING) ?? self::DEFAULT_PATH;
    }

    private function getSiteByPid(int $page): ?Site
    {
        if ($page === 0) {
            return null;
        }

        try {
            return $this->siteFinder->getSiteByPageId($page);
        } catch (\Exception) {
            return null;
        }
    }

    private function getSiteFromRequest(?ServerRequestInterface $request): ?Site
    {
        $site = $request?->getAttribute('site');

        return $site instanceof Site ? $site : null;
    }
}
