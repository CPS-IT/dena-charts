<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Tests\Unit\ViewHelpers\Format;

use CPSIT\DenaCharts\ViewHelpers\Format\NumberViewHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Http\Uri;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

class NumberViewHelperTest extends UnitTestCase
{
    /**
     * @return array<string, array{string, string, string}>
     */
    public static function formattedNumberProvider(): array
    {
        return [
            'german' => ['de_DE.UTF-8', '1234.5', '1.234,5'],
            'english' => ['en_US.UTF-8', '1234.5', '1,234.5'],
        ];
    }

    #[Test]
    #[DataProvider('formattedNumberProvider')]
    public function formatsNumberWithLocaleOfRequestLanguage(string $locale, string $value, string $expected): void
    {
        $language = new SiteLanguage(0, $locale, new Uri('/'), []);
        $request = (new ServerRequest('https://example.com/'))->withAttribute('language', $language);

        self::assertSame($expected, $this->createSubject($request, $value)->render());
    }

    private function createSubject(ServerRequestInterface $request, string $value): NumberViewHelper
    {
        $renderingContext = $this->createMock(RenderingContextInterface::class);
        $renderingContext->method('getAttribute')->with(ServerRequestInterface::class)->willReturn($request);

        $subject = new NumberViewHelper();
        $subject->setRenderingContext($renderingContext);
        $subject->setRenderChildrenClosure(static fn(): string => $value);

        return $subject;
    }
}
