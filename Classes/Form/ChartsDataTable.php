<?php

namespace CPSIT\DenaCharts\Form;

use CPSIT\DenaCharts\Domain\Model\DataCell;
use CPSIT\DenaCharts\Domain\Model\DataRow;
use CPSIT\DenaCharts\Domain\Model\DataTable;
use CPSIT\DenaCharts\Service\DataTableService;
use TYPO3\CMS\Backend\Form\AbstractNode;
use TYPO3\CMS\Backend\Form\NodeFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ChartsDataTable extends AbstractNode
{
    protected DataTableService $dataTableService;

    public function __construct(NodeFactory $nodeFactory, array $data)
    {
        parent::__construct($nodeFactory, $data);
        $this->dataTableService = GeneralUtility::makeInstance(DataTableService::class);
    }

    public function render()
    {
        try {
            $rowUid = (int)$this->data['databaseRow']['uid'];
            $dataTable = $this->dataTableService->getDataTableForContentRowUid($rowUid);
        } catch (\Throwable $e) {
            return ['html' => 'Table cannot be fetched'];
        }

        $result = array_merge($this->initializeResultArray(), [
            'stylesheetFiles' => ['EXT:dena_charts/Resources/Public/Backend/Styles/charts-table.css'],
            'html' => $this->renderHtmlForDataTable($dataTable),
        ]);
        return $result;
    }

    protected function renderHtmlForDataTable(DataTable $dataTable): string
    {
        $headerRows = [['', ''], ['', '']];
        foreach ($dataTable->getColumns() as $column) {
            $headerRows[0][] = $column->getLetters();
            $headerRows[1][] = $column->getLabel();
        }

        $headerRowsHtml = implode('', array_map(function (array $header) {
            $wrapped = array_map(fn (string $content) => '<th>' . htmlentities($content) . '</th>', $header);
            return '<tr>' . implode('', $wrapped) . '</tr>';
        }, $headerRows));
        $headerHtml = '<thead>' . $headerRowsHtml . '</thead>';

        $bodyRowsHtml = implode('', array_map(function (DataRow $dataRow) {
            $header = '<th>' . $dataRow->getNumber() . '</th>' . '<th>' . $dataRow->getLabel() . '</th>';
            $wrapped = array_map(
                fn (DataCell $cell) => sprintf(
                    '<td title="%s %s %s">%s</td>',
                    $cell->getId(),
                    htmlentities($cell->getRow()->getLabel()),
                    htmlentities($cell->getColumn()->getLabel()),
                    htmlentities($cell->getValue())
                ),
                $dataRow->getCells()
            );
            return '<tr>' . $header . implode('', $wrapped) . '</tr>';
        }, $dataTable->getRows()->toArray()));
        $bodyHtml = '<tbody>' . $bodyRowsHtml . '</tbody>';

        return '<table class="data-table-chart">' . $headerHtml . $bodyHtml . '</table>';
    }
}
