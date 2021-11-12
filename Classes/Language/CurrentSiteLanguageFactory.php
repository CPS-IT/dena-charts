<?php

namespace CPSIT\DenaCharts\Language;

use TYPO3\CMS\Core\Site\Entity\SiteLanguage;

class CurrentSiteLanguageFactory
{
    public function __invoke(): ?SiteLanguage
    {
        return $GLOBALS['TYPO3_REQUEST']->getAttribute('language');
    }
}
