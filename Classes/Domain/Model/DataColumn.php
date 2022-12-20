<?php

namespace CPSIT\DenaCharts\Domain\Model;

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

class DataColumn
{
    protected int $number;

    protected string $label = '';

    /** @var DataCell[] */
    protected $cells = [];

    /**
     * @param int $index
     * @param string $label
     * @param DataCell[] $cells
     */
    public function __construct(int $index, string $label, array $cells)
    {
        $this->number = $index;
        $this->label = $label;
        $this->cells = $cells;

        foreach ($this->cells as &$cell) {
            $cell->setColumn($this);
        }
    }

    public function getDataSetNumber(): int
    {
        return $this->number - 1;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getCells(): array
    {
        return $this->cells;
    }

    public function getLetters(): string
    {
        $residue = $this->getNumber() - 1;
        for ($result = ''; $residue >= 0; $residue = intval($residue / 26) - 1) {
            $result = chr($residue%26 + 0x41) . $result;
        }
        return $result;
    }

    public static function getColumnIndexForLetters(string $columnLetters): int
    {
        for ($i = 0, $result = 0, $length = strlen($columnLetters); $i < $length; $i++) {
            $result = $result * 26 + ord($columnLetters[$i]) - 0x40;
        }
        return $result - 1;
    }
}
