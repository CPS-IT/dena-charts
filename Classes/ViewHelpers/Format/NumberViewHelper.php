<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\ViewHelpers\Format;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class NumberViewHelper extends AbstractViewHelper
{
    /**
     * Format the numeric value as a number with grouped thousands, decimal point and
     * precision, using the locale of the current site language.
     */
    public function render(): string
    {
        $language = $this->renderingContext->getAttribute(ServerRequestInterface::class)->getAttribute('language');
        $numberFormatter = \NumberFormatter::create((string)$language->getLocale(), \NumberFormatter::DECIMAL);

        return $numberFormatter->format((float)$this->renderChildren());
    }
}
