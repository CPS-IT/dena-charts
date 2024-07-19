<?php

namespace CPSIT\DenaCharts\Domain\Factory;

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

use CPSIT\DenaCharts\Domain\Model\DataCell;
use CPSIT\DenaCharts\Domain\Model\DataColumn;
use CPSIT\DenaCharts\Domain\Model\DataRow;
use CPSIT\DenaCharts\Domain\Model\DataTable;
use NumberFormatter;

/**
 * Class DataTableFactory
 */
class DataTableFactory
{
    /**
     * Builds a DataTable from array
     *
     * @param array $data Two dimensional array to build data table from
     * @param array $highlightFieldIds List of fields ids of fields to highlight
     * @return DataTable
     */
    public function fromArray(array $data, array $highlightFieldIds = []): DataTable
    {
        $dataTable = new DataTable();
        if (empty($data)) {
            return $dataTable;
        }

        $columnHeaders = array_shift($data);
        array_shift($columnHeaders);
        $rowHeaders = array_column($data, 0);

        $numberFormatter = NumberFormatter::create('de_DE', NumberFormatter::DECIMAL);
        $cells = array_map(function (array $values) use ($numberFormatter): array {
            array_shift($values);
            return array_map(function (string $formattedValue) use ($numberFormatter) {
                return new DataCell($numberFormatter->parse($formattedValue));
            }, $values);
        }, $data);

        foreach ($columnHeaders as $columnIndex => $header) {
            $columnData = array_column($cells, $columnIndex);
            $dataTable->addColumn(new DataColumn(
                $columnIndex + 2,
                $header,
                $columnData,
            ));
        }

        foreach ($cells as $rowIndex => $cellRow) {
            $label = $rowHeaders[$rowIndex];
            $row = new DataRow(
                $rowIndex + 2,
                $label,
                $cellRow
            );
            $dataTable->addRow($row);
        }

        foreach ($highlightFieldIds as $fieldId) {
            $cell = $dataTable->getCellById($fieldId);
            if (isset($cell)) {
                // @extensionScannerIgnoreLine
                $cell->setHighlight(true);
            }
        }

        return $dataTable;
    }
}
