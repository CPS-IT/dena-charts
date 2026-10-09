<?php

namespace CPSIT\DenaCharts\Tests\Unit\Service;

use CPSIT\DenaCharts\Service\FileReaderCSV;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3\CMS\Core\Resource\FileReference;

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
     * @var FileReaderCSV
     */
    protected $subject;

    /**
     * set up subject
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->subject = new FileReaderCSV();
    }

    /**
     * provides data for processing file content
     */
    public static function processConvertsFileContentToArrayDataProvider(): array
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

    #[Test]
    #[DataProvider('processConvertsFileContentToArrayDataProvider')]
    public function processConvertsFileContentToArray(string $fileContent, array $expectedRecords): void
    {
        $mockFile = $this->getMockBuilder(FileReference::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getContents'])
            ->getMock();
        $mockFile->expects($this->once())->method('getContents')
            ->willReturn($fileContent);

        $data = $this->subject->getData($mockFile);

        $this->assertSame($expectedRecords, $data);
    }
}
