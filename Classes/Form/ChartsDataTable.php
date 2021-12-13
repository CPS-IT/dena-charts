<?php

namespace CPSIT\DenaCharts\Form;

use CPSIT\DenaCharts\Domain\Factory\DataTableFactory;
use CPSIT\DenaCharts\Domain\Model\DataCell;
use CPSIT\DenaCharts\Domain\Model\DataRow;
use CPSIT\DenaCharts\Service\FileReaderCSV;
use TYPO3\CMS\Backend\Form\AbstractNode;
use TYPO3\CMS\Backend\Form\NodeFactory;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ChartsDataTable extends AbstractNode
{
    protected FileRepository $fileRepository;

    protected FileReaderCSV $fileReaderCSV;

    protected DataTableFactory $dataTableFactory;

    public function __construct(NodeFactory $nodeFactory, array $data)
    {
        parent::__construct($nodeFactory, $data);
        $this->fileRepository = GeneralUtility::makeInstance(FileRepository::class);
        $this->fileReaderCSV = GeneralUtility::makeInstance(FileReaderCSV::class);
        $this->dataTableFactory = GeneralUtility::makeInstance(DataTableFactory::class);
    }

    public function render()
    {
        try {
            $dataTable = $this->getDataTable();
        } catch (\Throwable $e) {
            return ['html' => 'Table cannot be fetched'];
        }

        $result = $this->initializeResultArray();
        $result['html'] = $this->renderHtmlForDataTable($dataTable);
        return $result;
    }

    protected function getFile(int $contentElementUid): ?FileReference
    {
        $files = $this->fileRepository->findByRelation('tt_content', 'denacharts_data_file', $contentElementUid);
        if (empty($files)) {
            return null;
        }
        return $files[0];
    }

    protected function getDataTable(): \CPSIT\DenaCharts\Domain\Model\DataTable
    {
        $contentElementUid = (int)$this->data['databaseRow']['uid'];
        $file = $this->getFile($contentElementUid);
        $data = $this->fileReaderCSV->getData($file);
        return $this->dataTableFactory->fromArray($data);
    }

    protected function renderHtmlForDataTable(\CPSIT\DenaCharts\Domain\Model\DataTable $dataTable): string
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
