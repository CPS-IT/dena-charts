<?php

namespace CPSIT\DenaCharts\Tests\Unit\Domain\Factory;

use CPSIT\DenaCharts\Domain\Factory\DataTableFactory;
use CPSIT\DenaCharts\Domain\Model\DataCell;
use CPSIT\DenaCharts\Domain\Model\DataColumn;
use CPSIT\DenaCharts\Domain\Model\DataRow;
use CPSIT\DenaCharts\Domain\Model\DataTable;
use DWenzel\T3extensionTools\Traits\UnitTests\ResetSingletonInstancesMacro;
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

class DataTableFactoryTest extends UnitTestCase
{
    use ResetSingletonInstancesMacro;

    /**
     * @var DataTableFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $subject;

    /**
     * set up subject
     */
    public function setUp(): void
    {
        $this->setResetSingletonInstances();
        $this->subject = new DataTableFactory();
    }

    /**
     * @test
     */
    public function fromArrayReturnsDataTable(): void
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
    public function fromArrayGeneratesColumnsFromFirstRow(): void
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
        foreach ($columns->toArray() as $index => $column) {
            $this->assertInstanceOf(
                DataColumn::class,
                $column
            );
            $this->assertEquals($index + 2, $column->getNumber());
            $this->assertContains(
                $column->getLabel(),
                $firstRow
            );
        }
    }


    /**
     * @test
     */
    public function fromArrayGeneratesRowsFromDataRows(): void
    {
        $data = [
            ['', 'foo', 'bar'],
            [1995, 1, 2],
            [1996, 3, 5]
        ];

        $dataTable = $this->subject->fromArray($data);
        $rows = $dataTable->getRows();

        $rowCount = count($data) - 1;
        $this->assertSame(
            $rowCount,
            $rows->count()
        );

        /** @var DataRow $row */
        foreach ($rows->toArray() as $index => $row) {
            $this->assertInstanceOf(
                DataRow::class,
                $row
            );
            $position = $rows->getPosition($row);
            $this->assertEquals($index + 2, $row->getNumber());
            $expected = $data[$position];
            array_shift($expected);
            $this->assertSame(
                array_map(fn (DataCell $dataCell) => $dataCell->getValue(), $row->getCells()),
                array_map(fn ($number) => (float)$number, $expected)
            );
        }
    }

    /**
     * @test
     */
    public function fromArrayReadsRowLabelsFromFirstColumn(): void
    {
        $data = [
            ['baz', 'foo', 'bar'],
            ['label 1', 1, 2],
            ['label 2', 3, 5]
        ];

        $dataTable = $this->subject->fromArray($data);
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
                array_map(fn (DataCell $dataCell) => $dataCell->getValue(), $row->getCells()),
                array_map(fn ($number) => (float)$number, $rawRow)
            );
        }
    }
}
