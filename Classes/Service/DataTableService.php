<?php

namespace CPSIT\DenaCharts\Service;

use CPSIT\DenaCharts\Domain\Factory\DataTableFactory;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\FileRepository;

class DataTableService
{
    protected FileRepository $fileRepository;

    protected FileReaderCSV $fileReaderCSV;

    protected DataTableFactory $dataTableFactory;

    public function __construct(
        FileRepository $fileRepository,
        FileReaderCSV $fileReaderCSV,
        DataTableFactory $dataTableFactory
    ) {
        $this->fileRepository = $fileRepository;
        $this->fileReaderCSV = $fileReaderCSV;
        $this->dataTableFactory = $dataTableFactory;
    }

    public function getDataTableForContentRowUid(int $ttContentRowUid): \CPSIT\DenaCharts\Domain\Model\DataTable
    {
        $contentElementUid = $ttContentRowUid;
        $file = $this->getFile($contentElementUid);
        $data = [];
        if($file instanceof FileReference) {
            $data = $this->fileReaderCSV->getData($file);
        }
        return $this->dataTableFactory->fromArray($data);
    }

    protected function getFile(int $contentElementUid): ?FileReference
    {
        $files = $this->fileRepository->findByRelation('tt_content', 'denacharts_data_file', $contentElementUid);
        if (empty($files)) {
            return null;
        }
        return $files[0];
    }
}
