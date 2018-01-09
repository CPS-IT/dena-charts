<?php

namespace CPSIT\DenaCharts\Tests\Unit\Domain\Model;
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

class DataTableTest extends UnitTestCase
{
    /**
     * @var DataTable|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $subject;

    /**
     * set up the subject
     */
    public function setUp()
    {
        $this->subject = $this->getMockBuilder(DataTable::class)
            ->setMethods(['dummy'])
            ->getMock();
    }

    /**
     * @test
     */
    public function getRowInitiallyReturnsEmptyObjectStorage() {
        $emptyStorage = new ObjectStorage();
        $this->assertEquals(
            $emptyStorage,
            $this->subject->getRows()
        );
    }

    /**
     * @test
     */
    public function setRowsForObjectStorageSetsRows() {
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
    public function addRowForObjectAddsRow() {
        $newRow = new DataRow();

        $this->subject->addRow($newRow);
        $this->assertContains(
            $newRow,
            $this->subject->getRows()
        );
    }

    /**
     * @test
     */
    public function rowCanBeAddedOnlyOnce() {
        $row = new DataRow();

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
    public function getColumnsInitiallyReturnsEmptyObjectStorage() {
        $this->assertInstanceOf(
            ObjectStorage::class,
            $this->subject->getColumns()
        );
    }
}
