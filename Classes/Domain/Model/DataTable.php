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

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Class DataTable
 */
class DataTable
{
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<CPSIT\DenaCharts\Domain\Model\DataRow>
     */
    protected ObjectStorage $rows;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<CPSIT\DenaCharts\Domain\Model\DataColumn>
     */
    protected ObjectStorage $columns;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initializeStorageObjects();
    }

    /**
     * initialize object
     */
    public function initializeStorageObjects()
    {
        $this->rows = new ObjectStorage();
        $this->columns = new ObjectStorage();
    }

    /**
     * Gets the rows
     * @return ObjectStorage<DataRow>
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * Sets the rows
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<CPSIT\DenaCharts\Domain\Model\DataRow> $rows
     */
    public function setRows(ObjectStorage $rows)
    {
        $this->rows = $rows;
    }

    /**
     * Adds a row
     * @param DataRow $row
     */
    public function addRow(DataRow $row)
    {
        $this->rows->attach($row);
    }

    /**
     * Gets the columns
     *
     * @return ObjectStorage<\CPSIT\DenaCharts\Domain\Model\DataColumn>
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<CPSIT\DenaCharts\Domain\Model\DataColumn> $columns
     */
    public function setColumns(ObjectStorage $columns)
    {
        $this->columns = $columns;
    }

    /**
     * Adds a data column
     *
     * @param DataColumn $column
     */
    public function addColumn(DataColumn $column)
    {
        $this->columns->attach($column);
    }

    public function getCellById(string $cellId): ?DataCell
    {
        $match = preg_match('/([A-Z]+)([0-9]+)/', $cellId, $matches);
        if (!$match) {
            throw new \InvalidArgumentException('Invalid cell id ' . $cellId);
        }

        $columnLetters = $matches[1];
        $columnIndex = DataColumn::getColumnIndexForLetters($columnLetters);

        $rowIndex = ((int)$matches[2]) - 1;

        /** @var DataRow $row */
        if (!isset($this->getRows()[$rowIndex - 1])) {
            return null;
        }
        $row = $this->getRows()[$rowIndex - 1];

        if (!isset($row->getCells()[$columnIndex - 1])) {
            return null;
        }
        $cell = $row->getCells()[$columnIndex - 1];

        return $cell;
    }
}
