<?php

namespace CPSIT\DenaCharts\Tests\Unit\DataProcessing;

use CPSIT\DenaCharts\DataProcessing\DataTableFromArray;
use CPSIT\DenaCharts\DataProcessing\FileReaderCSV;
use CPSIT\DenaCharts\Domain\Factory\DataTableFactory;
use CPSIT\DenaCharts\Domain\Model\DataTable;
use Nimut\TestingFramework\TestCase\UnitTestCase;
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
class DataTableFromArrayTest extends UnitTestCase
{
    /**
     * @var DataTableFromArray|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $subject;

    /**
     * @var TypoScriptService|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $typoScriptService;

    /**
     * @var DataTableFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $dataTableFactory;

    /**
     * @var ContentObjectRenderer|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $contentObjectRenderer;

    /**
     * set up subject
     */
    public function setUp()
    {
        $this->subject = $this->getMockBuilder(DataTableFromArray::class)
            ->setMethods(['dummy'])
            ->getMock();
        $this->typoScriptService = $this->getMockBuilder(TypoScriptService::class)
            ->disableOriginalConstructor()
            ->setMethods(['convertTypoScriptArrayToPlainArray'])
            ->getMock();
        $this->subject->injectTypoScriptService($this->typoScriptService);

        $this->dataTableFactory = $this->getMockBuilder(DataTableFactory::class)
            ->setMethods(['fromArray'])
            ->getMock();
        $this->subject->injectDataTableFactory($this->dataTableFactory);
        $this->contentObjectRenderer = $this->getMockBuilder(ContentObjectRenderer::class)
            ->disableOriginalConstructor()->getMock();
    }

    /**
     * @test
     */
    public function constructorInstantiatesDataTableFactory()
    {
        $this->assertAttributeInstanceOf(
            DataTableFactory::class,
            'dataTableFactory',
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
        $this->subject = new DataTableFromArray();

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
    public function dataTableFactoryCanBeInjected()
    {
        $this->subject = new DataTableFromArray();

        $this->subject->injectDataTableFactory($this->dataTableFactory);
        $this->assertAttributeSame(
            $this->dataTableFactory,
            'dataTableFactory',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function processConvertsTypoScriptArrayToPlainArray()
    {
        $initialData = [
            FileReaderCSV::CSV_DATA_KEY => ['foo']
        ];

        $typoScript = ['foo'];
        $this->typoScriptService->expects($this->once())
            ->method('convertTypoScriptArrayToPlainArray')
            ->with($typoScript);
        $this->subject->process(
            $this->contentObjectRenderer,
            [],
            $typoScript,
            $initialData
        );
    }

    /**
     * @test
     */
    public function processReturnsProcessedDataUnchangedIfCsvDataIsNotSet()
    {
        $contentObjectConfiguration = [];
        $processorConfiguration = [];
        $initialData = ['foo'];

        $processedData = $this->subject->process(
            $this->contentObjectRenderer,
            $contentObjectConfiguration,
            $processorConfiguration,
            $initialData
        );

        $this->assertSame(
            $initialData,
            $processedData
        );
    }

    /**
     * @test
     */
    public function processGetsDataTableFromFactory()
    {
        $csvData = ['foo'];
        $contentObjectConfiguration = [];
        $processorConfiguration = ['bar'];
        $initialData = [
            FileReaderCSV::CSV_DATA_KEY => $csvData
        ];
        $mockDataTable = $this->getMockBuilder(DataTable::class)
            ->getMock();

        $this->dataTableFactory->expects($this->once())
            ->method('fromArray')
            ->with($csvData, $processorConfiguration)
            ->will($this->returnValue($mockDataTable));
        $this->typoScriptService->expects($this->once())
            ->method('convertTypoScriptArrayToPlainArray')
            ->will($this->returnValue($processorConfiguration));

        $processedData = $this->subject->process(
            $this->contentObjectRenderer,
            $contentObjectConfiguration,
            $processorConfiguration,
            $initialData
        );

        $this->assertArrayHasKey(
            DataTableFromArray::DATA_TABLE_KEY,
            $processedData
        );
        $this->assertSame(
            $mockDataTable,
            $processedData[DataTableFromArray::DATA_TABLE_KEY]
        );
    }
}
