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
     * @return DataTable
     */
    public function fromArray(array $data): DataTable
    {
        $dataTable = new DataTable();

        if (!empty($data)) {
            $headers = array_shift($data);
            array_shift($headers);

            foreach ($headers as $columnIndex => $header) {
                $column = new DataColumn();
                $column->setLabel($header);
                $dataColumn = array_column($data, $columnIndex + 1);
                $column->setData($this->parseNumbers($dataColumn));

                $dataTable->addColumn($column);
            }

            foreach ($data as $dataRow) {
                $row = new DataRow();
                $label = (string)array_shift($dataRow);
                $row->setLabel($label);
                $row->setData($this->parseNumbers($dataRow));
                $dataTable->addRow($row);
            }
        }

        return $dataTable;
    }

    protected function parseNumbers(array $data)
    {
        $numberFormatter = NumberFormatter::create('de_DE', NumberFormatter::DECIMAL);
        return array_map(function ($formattedValue) use ($numberFormatter) {
            return $numberFormatter->parse($formattedValue);
        }, $data);
    }
}
