<?php

namespace CPSIT\DenaCharts\Tests\Unit\Domain\Factory;

use CPSIT\DenaCharts\Domain\Factory\DataTableFactory;
use CPSIT\DenaCharts\Domain\Model\DataColumn;
use CPSIT\DenaCharts\Domain\Model\DataRow;
use CPSIT\DenaCharts\Domain\Model\DataTable;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

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
class DataTableFactoryTest extends UnitTestCase
{
    /**
     * @var DataTableFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $subject;

    /**
     * set up subject
     */
    public function setUp()
    {
        $this->subject = new DataTableFactory();
    }

    /**
     * @test
     */
    public function fromArrayReturnsDataTable()
    {
        $data = [];
        $this->assertInstanceOf(
            DataTable::class,
            $this->subject->fromArray($data)
        );
    }

    /**
     * @test
     */
    public function fromArrayGeneratesColumnsFromFirstRow()
    {
        $data = [
            ['foo', 'bar']
        ];
        $firstRow = $data[0];

        $dataTable = $this->subject->fromArray($data);
        $columns = $dataTable->getColumns();

        $this->assertCount(
            count($firstRow)-1,
            $columns
        );
        /** @var DataColumn $column */
        foreach ($columns as $column) {
            $this->assertInstanceOf(
                DataColumn::class,
                $column
            );
            $this->assertContains(
                $column->getLabel(),
                $firstRow
            );
        }
    }


    /**
     * @test
     */
    public function fromArrayGeneratesRowsFromDataRows()
    {
        $data = [
            ['foo', 'bar'],
            [1, 2],
            [3, 5]
        ];

        $dataTable = $this->subject->fromArray($data);
        $rows = $dataTable->getRows();

        $rowCount = count($data) - 1;
        $this->assertSame(
            $rowCount,
            $rows->count()
        );

        /** @var DataRow $row */
        foreach ($rows as $row) {
            $this->assertInstanceOf(
                DataRow::class,
                $row
            );
            $position = $rows->getPosition($row);
            $this->assertSame(
                $row->getData(),
                array_map(fn ($number) => (float)$number, $data[$position])
            );
        }
    }

    /**
     * @test
     */
    public function fromArrayReadsRowLabelsFromFirstColumn()
    {
        $data = [
            ['baz', 'foo', 'bar'],
            ['label 1', 1, 2],
            ['label 2', 3, 5]
        ];
        $configuration = [
            'labelFromFirstColumn' => 1
        ];

        $dataTable = $this->subject->fromArray($data, $configuration);
        $rows = $dataTable->getRows();

        /** @var DataRow $row */
        foreach ($rows as $row) {
            $position = $rows->getPosition($row);
            $rawRow = $data[$position];

            $this->assertSame(
                $rawRow[0],
                $row->getLabel()
            );

            array_shift($rawRow);
            $this->assertSame(
                $row->getData(),
                array_map(fn ($number) => (float)$number, $rawRow)
            );
        }
    }
}
