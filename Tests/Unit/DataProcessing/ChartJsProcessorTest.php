<?php

namespace CPSIT\DenaCharts\Tests\Unit\DataProcessing;

use CPSIT\DenaCharts\DataProcessing\ChartJsProcessor;
use CPSIT\DenaCharts\DataProcessing\ChartJsProcessor\ColorsProcessor;
use CPSIT\DenaCharts\DataProcessing\DataTableFromArray;
use CPSIT\DenaCharts\Domain\Model\DataTable;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
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
class ChartJsProcessorTest extends UnitTestCase
{
    /**
     * @var ChartJsProcessor|\PHPUnit_Framework_MockObject_MockObject
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

        $this->colorsProcessor = $this->getMockBuilder(ColorsProcessor::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->subject = new ChartJsProcessor();
        $this->subject->injectTypoScriptService($this->typoScriptService);
        $this->subject->injectColorsProcessor($this->colorsProcessor);

        $this->contentObjectRenderer = $this->getMockBuilder(ContentObjectRenderer::class)
            ->disableOriginalConstructor()->getMock();
    }

    /**
     * @test
     */
    public function processConvertsTypoScriptArrayToPlainArray()
    {
        $typoScript = ['foo', 'type' => 'pie'];
        $this->typoScriptService->expects($this->once())
            ->method('convertTypoScriptArrayToPlainArray')
            ->with($typoScript)
            ->willReturn($typoScript);
        $this->subject->process(
            $this->contentObjectRenderer,
            [],
            $typoScript,
            [
                'data' => [],
                DataTableFromArray::DATA_TABLE_KEY => new DataTable(),
            ]
        );
    }

    /**
     * provides data for processing
     */
    public function processGetsChartDataFromCSVDataProvider() {
        return [
            'empty CSV data' => [
                [
                    'data' => [],
                    DataTableFromArray::DATA_TABLE_KEY => new DataTable(),
                ],
                ['options' => 'foo'],
                '@todo'
            ]
        ];
    }

    /**
     * @test
     * @param array $processedData
     * @param array $localConfiguration
     * @param string $expectedChartData
     * @dataProvider processGetsChartDataFromCSVDataProvider
     */
    public function processGetsChartDataFromCSV($processedData, $localConfiguration, $expectedChartData) {
        $contentObjectConfiguration = [];
        $processorConfiguration = ['type' => 'pie'];

        $this->typoScriptService->expects($this->once())
            ->method('convertTypoScriptArrayToPlainArray')
            ->with($processorConfiguration)
            ->will($this->returnValue($processorConfiguration));

        $this->subject->process(
            $this->contentObjectRenderer,
            $contentObjectConfiguration,
            $processorConfiguration,
            $processedData
        );
    }
}
