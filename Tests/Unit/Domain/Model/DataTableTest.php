<?php

namespace CPSIT\DenaCharts\Tests\Unit\Domain\Model;

use CPSIT\DenaCharts\Domain\Factory\DataTableFactory;
use CPSIT\DenaCharts\Domain\Model\DataCell;
use CPSIT\DenaCharts\Domain\Model\DataRow;
use CPSIT\DenaCharts\Domain\Model\DataTable;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
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

class DataTableTest extends UnitTestCase
{
    /**
     * @var DataTable|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $subject;

    /**
     * set up the subject
     */
    public function setUp(): void
    {
        $this->subject = $this->getMockBuilder(DataTable::class)
            ->setMethods(['dummy'])
            ->getMock();
    }

    /**
     * @test
     */
    public function getRowInitiallyReturnsEmptyObjectStorage(): void
    {
        $emptyStorage = new ObjectStorage();
        $this->assertEquals(
            $emptyStorage,
            $this->subject->getRows()
        );
    }

    /**
     * @test
     */
    public function setRowsForObjectStorageSetsRows(): void
    {
        $rows = new ObjectStorage();
        $this->subject->setRows($rows);

        $this->assertSame(
            $rows,
            $this->subject->getRows()
        );
    }

    /**
     * @test
     */
    public function addRowForObjectAddsRow(): void
    {
        $newRow = new DataRow(
            1,
            'rowLabel',
            array_map(fn ($value) => new DataCell($value), [1, 2, 3]),
        );

        $this->subject->addRow($newRow);
        $this->assertContains(
            $newRow,
            $this->subject->getRows()
        );
    }

    /**
     * @test
     */
    public function rowCanBeAddedOnlyOnce(): void
    {
        $row = new DataRow(
            1,
            'rowLabel',
            array_map(fn ($value) => new DataCell($value), [1, 2, 3]),
        );

        $this->subject->addRow($row);
        $this->subject->addRow($row);

        $this->assertCount(
            1,
            $this->subject->getRows()
        );
    }

    /**
     * @test
     */
    public function getColumnsInitiallyReturnsEmptyObjectStorage(): void
    {
        $this->assertInstanceOf(
            ObjectStorage::class,
            $this->subject->getColumns()
        );
    }

    public function provideCasesForCellsById()
    {
        return [
            ['B2', 1.0],
            ['C2', 2.0],
            ['D2', 3.0],
            ['B3', 4.0],
            ['C3', 5.0],
            ['D3', 6.0],
        ];
    }

    /**
     * @test
     * @dataProvider provideCasesForCellsById
     */
    public function findsCellById(string $id, float $value): void
    {
        $rows = [
            ['Header', 'Header 1', 'Header 2', 'Header 3'],
            ['Row 1', 1, 2, 3],
            ['Row 2', 4, 5, 6],
            ['Row 3', 7, 8, 9],
        ];

        $dataTableFactory = new DataTableFactory();
        $dataTable = $dataTableFactory->fromArray($rows);

        $result = $dataTable->getCellById($id);
        self::assertEquals($id, $result->getId());
        self::assertEquals($value, $result->getValue());
    }
}
