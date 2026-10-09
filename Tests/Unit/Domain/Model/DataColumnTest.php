<?php

namespace CPSIT\DenaCharts\Tests\Unit\Domain\Model;

use CPSIT\DenaCharts\Domain\Model\DataCell;
use CPSIT\DenaCharts\Domain\Model\DataColumn;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

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

class DataColumnTest extends UnitTestCase
{
    /**
     * @var DataColumn
     */
    protected $subject;

    /**
     * set up subject
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->subject = new DataColumn(
            32,
            'label',
            array_map(fn ($value) => new DataCell($value), [1, 2, 3])
        );
    }

    #[Test]
    public function getLabelInitiallyReturnsString(): void
    {
        $this->assertSame(
            'label',
            $this->subject->getLabel()
        );
    }

    #[Test]
    public function getLettersReturnsLetterCode(): void
    {
        $this->assertSame(
            'AF',
            $this->subject->getLetters(),
        );
    }

    public static function provideIndexForLettersTestCases(): array
    {
        return [
            ['A', 0],
            ['B', 1],
            ['Z', 25],
            ['AA', 26],
            ['AF', 31],
        ];
    }

    #[DataProvider('provideIndexForLettersTestCases')]
    public function testGetIndexForLetters(string $columnLetters, int $expectedIndex): void
    {
        $result = DataColumn::getColumnIndexForLetters($columnLetters);
        $this->assertEquals($expectedIndex, $result);
    }
}
