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
    use CompileWithRenderStatic;

    /**
     * Format the numeric value as a number with grouped thousands, decimal point and
     * precision.
     *
     * @return string The formatted number
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $language = GeneralUtility::makeInstance(CurrentSiteLanguageFactory::class)();
        $numberFormatter = NumberFormatter::create($language->getLocale(), NumberFormatter::DECIMAL);
        $stringToFormat = $renderChildrenClosure();
        return $numberFormatter->format((float) $stringToFormat);
    }
}
