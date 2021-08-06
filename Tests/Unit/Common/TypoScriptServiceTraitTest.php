<?php

namespace CPSIT\DenaCharts\Tests\Unit\DataProcessing;

use CPSIT\DenaCharts\Common\TypoScriptServiceTrait;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;

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
class TypoScriptServiceTraitTest extends UnitTestCase
{
    /**
     * @var TypoScriptServiceTrait|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $subject;

    /**
     * @var TypoScriptService|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $typoScriptService;

    /**
     * set up subject
     */
    public function setUp()
    {
        $this->subject = $this->getMockBuilder(TypoScriptServiceTrait::class)
            ->setMethods(['dummy'])
            ->getMockForTrait();
        $this->typoScriptService = $this->getMockBuilder(TypoScriptService::class)
            ->disableOriginalConstructor()
            ->setMethods(['convertTypoScriptArrayToPlainArray'])
            ->getMock();
    }

    /**
     * @test
     */
    public function typoScriptServiceCanBeInjected()
    {
        $this->subject->injectTypoScriptService($this->typoScriptService);
        $this->assertAttributeSame(
            $this->typoScriptService,
            'typoScriptService',
            $this->subject
        );
    }
}
