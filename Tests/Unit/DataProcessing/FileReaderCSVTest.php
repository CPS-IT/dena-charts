<?php

namespace CPSIT\DenaCharts\Tests\Unit\DataProcessing;

use CPSIT\DenaCharts\DataProcessing\FileReaderCSV;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2018 Dirk Wenzel <wenzel@cps-it.de>
 *  All rights reserved
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the text file GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
class FileReaderCSVTest extends UnitTestCase
{
    /**
     * @var FileReaderCSV|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $subject;

    /**
     * @var TypoScriptService|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $typoScriptService;

    /**
     * @var ContentObjectRenderer|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $contentObjectRenderer;

    /**
     * set up subject
     */
    public function setUp()
    {
        $this->typoScriptService = $this->getMockBuilder(TypoScriptService::class)
            ->disableOriginalConstructor()
            ->setMethods(['convertTypoScriptArrayToPlainArray'])
            ->getMock();
        $this->subject = $this->getMockBuilder(FileReaderCSV::class)
            ->setConstructorArgs([$this->typoScriptService])
            ->setMethods(['dummy'])
            ->getMock();

        $this->contentObjectRenderer = $this->getMockBuilder(ContentObjectRenderer::class)
            ->disableOriginalConstructor()->getMock();
    }

    /**
     * @test
     */
    public function processConvertsTypoScriptArrayToPlainArray()
    {
        $typoScript = ['foo'];
        $this->typoScriptService->expects($this->once())
            ->method('convertTypoScriptArrayToPlainArray')
            ->with($typoScript);
        $this->subject->process(
            $this->contentObjectRenderer,
            [],
            $typoScript,
            ['data' => []],
        );
    }

    /**
     * provides data for processing file content
     */
    public function processConvertsFileContentToArrayDataProvider()
    {
        $contentWithWhiteSpace = <<<FCC
"label 1"
"content 1"   

FCC;

        $contentWithLineBreakInEnclosure = <<<BIE
"label 1"
"content 
1"
BIE;


        return [
            // fileContent, expectedRecords
            'empty file, empty result array' => [
                '', []
            ],
            'white space' => [
                $contentWithWhiteSpace,
                [
                    ['label 1'],
                    ['content 1']
                ]
            ],
            'line break in enclosure' => [
                $contentWithLineBreakInEnclosure,
                [
                    ['label 1'],
                    ['content '. PHP_EOL . '1']
                ]
            ]
        ];
    }

    /**
     * @test
     * @param string $fileContent
     * @param array $expectedRecords
     * @dataProvider processConvertsFileContentToArrayDataProvider
     */
    public function processConvertsFileContentToArray($fileContent, $expectedRecords)
    {
        $configuration = [];
        $contentElementData = [];

        $mockFile = $this->getMockBuilder(FileReference::class)
            ->disableOriginalConstructor()
            ->setMethods(['getContents'])
            ->getMock();
        $mockFile->expects($this->once())->method('getContents')
            ->will($this->returnValue($fileContent));

        $contentObjectConfiguration = [];
        $processedData = [
            'data' => $contentElementData,
            'file' => $mockFile,
        ];

        $this->typoScriptService->expects($this->once())
            ->method('convertTypoScriptArrayToPlainArray')
            ->will($this->returnValue($configuration));

        $dataAfterProcessing = $this->subject->process(
            $this->contentObjectRenderer,
            $contentObjectConfiguration,
            $configuration,
            $processedData
        );

        $this->assertSame(
            $dataAfterProcessing['csvData'],
            $expectedRecords
        );
    }
}
