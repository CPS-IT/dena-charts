<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Tests\Unit\Service;

use CPSIT\DenaCharts\Service\ColorSchemaFileService;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class ColorSchemaFileServiceTest extends UnitTestCase
{
    private SiteFinder&MockObject $siteFinder;

    private ColorSchemaFileService $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->siteFinder = $this->createMock(SiteFinder::class);
        $this->subject = new ColorSchemaFileService($this->siteFinder);
    }

    #[Test]
    public function returnsDefaultPathWithoutPageAndRequest(): void
    {
        $this->siteFinder->expects($this->never())->method('getSiteByPageId');

        self::assertSame(ColorSchemaFileService::DEFAULT_PATH, $this->subject->getColorScheme());
    }

    #[Test]
    public function returnsFileOfSiteFoundByPage(): void
    {
        $this->siteFinder->method('getSiteByPageId')->with(7)->willReturn($this->createSite('EXT:site/page.json'));

        self::assertSame('EXT:site/page.json', $this->subject->getColorScheme(7));
    }

    #[Test]
    public function returnsFileOfSiteFromRequestWithoutPage(): void
    {
        $request = $this->createRequest($this->createSite('EXT:site/request.json'));

        self::assertSame('EXT:site/request.json', $this->subject->getColorScheme(0, $request));
    }

    #[Test]
    public function pageSiteWinsOverRequestSite(): void
    {
        $this->siteFinder->method('getSiteByPageId')->willReturn($this->createSite('EXT:site/page.json'));
        $request = $this->createRequest($this->createSite('EXT:site/request.json'));

        self::assertSame('EXT:site/page.json', $this->subject->getColorScheme(7, $request));
    }

    #[Test]
    public function fallsBackToRequestSiteWhenPageHasNoSite(): void
    {
        $this->siteFinder->method('getSiteByPageId')->willThrowException(new SiteNotFoundException('none', 1));
        $request = $this->createRequest($this->createSite('EXT:site/request.json'));

        self::assertSame('EXT:site/request.json', $this->subject->getColorScheme(7, $request));
    }

    #[Test]
    public function returnsDefaultPathWhenPageHasNoSiteAndNoRequest(): void
    {
        $this->siteFinder->method('getSiteByPageId')->willThrowException(new SiteNotFoundException('none', 1));

        self::assertSame(ColorSchemaFileService::DEFAULT_PATH, $this->subject->getColorScheme(7));
    }

    #[Test]
    public function returnsDefaultPathWhenRequestHasNoSite(): void
    {
        self::assertSame(
            ColorSchemaFileService::DEFAULT_PATH,
            $this->subject->getColorScheme(0, new ServerRequest('https://example.com/')),
        );
    }

    #[Test]
    public function returnsDefaultPathWhenSiteHasNoColorSchemeSetting(): void
    {
        $request = $this->createRequest(new Site('main', 1, ['settings' => []]));

        self::assertSame(ColorSchemaFileService::DEFAULT_PATH, $this->subject->getColorScheme(0, $request));
    }

    private function createSite(string $colorSchemeFile): Site
    {
        return new Site('main', 1, ['settings' => ['denaCharts' => ['colorSchemeFile' => $colorSchemeFile]]]);
    }

    private function createRequest(Site $site): ServerRequestInterface
    {
        return (new ServerRequest('https://example.com/'))->withAttribute('site', $site);
    }
}
