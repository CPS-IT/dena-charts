<?php

namespace CPSIT\DenaCharts\Tests\Unit\DataProcessing;

use CPSIT\DenaCharts\DataProcessing\FileReaderCSV;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Extbase\Service\TypoScriptService;
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
     * @var FileRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $fileRepository;

    /**
     * @var ContentObjectRenderer|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $contentObjectRenderer;

    /**
     * set up subject
     */
    public function setUp()
    {
        $this->subject = $this->getMockBuilder(FileReaderCSV::class)
            ->setMethods(['dummy'])
            ->getMock();
        $this->typoScriptService = $this->getMockBuilder(TypoScriptService::class)
            ->disableOriginalConstructor()
            ->setMethods(['convertTypoScriptArrayToPlainArray'])
            ->getMock();
        $this->subject->injectTypoScriptService($this->typoScriptService);

        $this->fileRepository = $this->getMockBuilder(FileRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findByRelation'])
            ->getMock();
        $this->subject->injectFileRepository($this->fileRepository);
        $this->contentObjectRenderer = $this->getMockBuilder(ContentObjectRenderer::class)
            ->disableOriginalConstructor()->getMock();
    }

    /**
     * @test
     */
    public function constructorInstantiatesFileRepository()
    {
        $this->assertAttributeInstanceOf(
            FileRepository::class,
            'fileRepository',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function constructorInstantiatesTypoScriptService()
    {
        $this->assertAttributeInstanceOf(
            TypoScriptService::class,
            'typoScriptService',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function typoScriptServiceCanBeInjected()
    {
        $this->subject = new FileReaderCSV();

        $this->subject->injectTypoScriptService($this->typoScriptService);
        $this->assertAttributeSame(
            $this->typoScriptService,
            'typoScriptService',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function fileRepositoryCanBeInjected()
    {
        $this->subject = new FileReaderCSV();

        $this->subject->injectFileRepository($this->fileRepository);
        $this->assertAttributeSame(
            $this->fileRepository,
            'fileRepository',
            $this->subject
        );
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
            []
        );
    }

    /**
     * provides data for file processing according a configuration
     * @return array
     */
    public function processGetsFileFromRepositoryDataProvider()
    {
        // contentElementData, $configuration, expectedProcessedData
        return [
            'empty configuration' => [
                ['uid' => 2], [], []
            ],
            'custom table name' => [
                ['uid' => 2],
                [
                    'tableName' => 'foo'
                ],
                []
            ],
            'custom field name' => [
                ['uid' => 2],
                [
                    'fieldName' => 'foo'
                ],
                []
            ]
        ];
    }

    /**
     * @test
     * @param array $contentElementData
     * @param array $configuration
     * @param array $expectedProcessedData
     * @dataProvider processGetsFileFromRepositoryDataProvider
     */
    public function processGetsFileFromRepository($contentElementData, $configuration, $expectedProcessedData)
    {
        $expectedTableName = FileReaderCSV::DEFAULT_RELATION_TABLE;
        $expectedFieldName = FileReaderCSV::DEFAULT_FIELD_NAME;
        if (!empty($configuration['tableName'])) {
            $expectedTableName = $configuration['tableName'];
        }
        if (!empty($configuration['fieldName'])) {
            $expectedFieldName = $configuration['fieldName'];
        }
        $contentObjectConfiguration = [];
        $processedData = [];
        $processedData['data'] = $contentElementData;

        $this->fileRepository->expects($this->once())
            ->method('findByRelation')
            ->with(
                $expectedTableName,
                $expectedFieldName,
                $contentElementData['uid']
            );
        $this->typoScriptService->expects($this->once())
            ->method('convertTypoScriptArrayToPlainArray')
            ->will($this->returnValue($configuration));

        $this->subject->process(
            $this->contentObjectRenderer,
            $contentObjectConfiguration,
            $configuration,
            $processedData
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

        $filesReturnedByRepository = [
            $mockFile
        ];
        $contentObjectConfiguration = [];
        $processedData = [];
        $processedData['data'] = $contentElementData;

        $this->fileRepository->expects($this->once())
            ->method('findByRelation')
            ->will($this->returnValue($filesReturnedByRepository));
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
