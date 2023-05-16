<?php

namespace CPSIT\DenaCharts\Tests\Unit\Domain\Model;

use CPSIT\DenaCharts\Domain\Model\DataCell;
use CPSIT\DenaCharts\Domain\Model\DataRow;
use Nimut\TestingFramework\TestCase\UnitTestCase;

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
class DataRowTest extends UnitTestCase
{
    /**
     * @var DataRow|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $subject;

    /**
     * set up subject
     */
    public function setUp(): void
    {
        $this->subject = new DataRow(
            42,
            'Label',
            array_map(fn ($value) => new DataCell($value), [0.4, 1.2, 2]),
        );
    }

    /**
     * @test
     */
    public function getDataReturnsData()
    {
        $this->assertEquals(
            [0.4, 1.2, 2],
            $this->subject->getData()
        );
    }

    /**
     * @test
     */
    public function getLabelReturnsLabel()
    {
        $this->assertSame(
            'Label',
            $this->subject->getLabel()
        );
    }
}
