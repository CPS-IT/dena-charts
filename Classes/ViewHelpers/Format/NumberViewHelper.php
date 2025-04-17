<?php

namespace CPSIT\DenaCharts\ViewHelpers\Format;

use CPSIT\DenaCharts\Language\CurrentSiteLanguageFactory;
use NumberFormatter;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

class NumberViewHelper extends AbstractViewHelper
{
    /**
     * Format the numeric value as a number with grouped thousands, decimal point and
     * precision.
     *
     * @return string The formatted number
     */
    public function render()
    {
        $language = GeneralUtility::makeInstance(CurrentSiteLanguageFactory::class)();
        $numberFormatter = NumberFormatter::create($language->getLocale(), NumberFormatter::DECIMAL);
        $stringToFormat = $this->renderChildren();
        return $numberFormatter->format((float) $stringToFormat);
    }
}
