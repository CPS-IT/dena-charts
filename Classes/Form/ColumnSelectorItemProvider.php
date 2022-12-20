<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Form;

use CPSIT\DenaCharts\Domain\Model\DataColumn;
use CPSIT\DenaCharts\Service\DataTableService;

class ColumnSelectorItemProvider
{
    protected DataTableService $dataTableService;

    public function __construct(DataTableService $dataTableService)
    {
        $this->dataTableService = $dataTableService;
    }

    public function provideColumnSelectorItems(&$params): void
    {
        $row = $params['row'];
        $rowUid = (int)$row['uid'];
        if (! $rowUid > 0) {
            return;
        }

        $dataTable = $this->dataTableService->getDataTableForContentRowUid($rowUid);
        $columns = $dataTable->getColumns()->toArray();
        $items = array_map(fn (DataColumn $column) => [
            sprintf('%s %s', $column->getLetters(), $column->getLabel()),
            $column->getDataSetNumber()
        ], $columns);

        array_unshift($items, ['', 0]);

        $params['items'] = $items;
    }
}
