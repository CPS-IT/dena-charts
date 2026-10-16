<?php

namespace CPSIT\DenaCharts\Domain\Builder;

use CPSIT\DenaCharts\Domain\Builder\Aspect\ColorsAspect;
use CPSIT\DenaCharts\Domain\Model\Chart;
use CPSIT\DenaCharts\Domain\Model\ChartConfiguration;
use CPSIT\DenaCharts\Domain\Model\DataCell;
use CPSIT\DenaCharts\Domain\Model\DataTable;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;

class ChartBuilder
{
    protected string $type;
    /** @var array<string, mixed> */
    protected array $options;

    /** @var array<string, mixed> */
    protected array $data;

    protected ColorsAspect $colorsProcessor;

    public function __construct(\CPSIT\DenaCharts\Domain\Builder\Aspect\ColorsAspect $colorsProcessor)
    {
        $this->colorsProcessor = $colorsProcessor;
    }

    /**
     * @param array{type: string, options: array<string, mixed>, builder?: class-string} $builderConfiguration
     */
    public function buildForConfiguration(
        ChartConfiguration $chartConfiguration,
        DataTable $dataTable,
        SiteLanguage $language,
        array $builderConfiguration
    ): Chart {
        $options = $builderConfiguration['options'];
        $locale = $language->getLocale();
        $options['locale'] = implode('-', \Locale::parseLocale((string)$locale));

        $aspectRatio = $chartConfiguration->getAspectRatio();
        if (!empty($aspectRatio)) {
            $splits = explode(':', $aspectRatio);
            $options['aspectRatio'] = (float)$splits[0] / (float)$splits[1];
        }

        $chart = new Chart(
            $builderConfiguration['type'],
            $options,
            $this->getChartData($dataTable),
        );

        return $this->process($chartConfiguration, $chart);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getChartData(DataTable $dataTable): array
    {
        $dataSets = $this->createDataSets($dataTable);

        $headers = [];
        foreach ($dataTable->getRows() as $rows) {
            $headers[] = $rows->getLabel();
        }

        return [
            'labels' => $headers,
            'datasets' => $dataSets,
        ];
    }

    /**
     * Creates data sets from data rows
     * Data sets are array build from DataRow objects.
     * There structure is specific for Chartjs
     *
     * @param DataTable $dataTable
     * @return array<int, array{label: string, data: array<int, mixed>}>
     */
    protected function createDataSets(DataTable $dataTable): array
    {
        $dataColumns = $dataTable->getColumns();
        $dataSets = [];
        foreach ($dataColumns as $column) {
            $dataSets[] = [
                // label might not be appropriate for all chart types
                'label' => $column->getLabel(),
                'data' => array_map([$this, 'convertCell'], $column->getCells()),
            ];
        }

        return $dataSets;
    }

    protected function process(ChartConfiguration $chartConfiguration, Chart $chart): Chart
    {
        $chart = $this->colorsProcessor->process($chartConfiguration, $chart);
        return $chart;
    }

    protected function convertCell(DataCell $dataCell): mixed
    {
        return $dataCell->getValue();
    }
}
