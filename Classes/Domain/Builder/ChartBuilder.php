<?php

namespace CPSIT\DenaCharts\Domain\Builder;

use CPSIT\DenaCharts\Domain\Builder\Aspect\ColorsAspect;
use CPSIT\DenaCharts\Domain\Model\ChartConfiguration;
use CPSIT\DenaCharts\Domain\Model\Chart;
use CPSIT\DenaCharts\Domain\Model\DataCell;
use CPSIT\DenaCharts\Domain\Model\DataRow;
use CPSIT\DenaCharts\Domain\Model\DataTable;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;

class ChartBuilder
{
    protected string $type;
    protected array $options;
    protected array $data;

    protected ColorsAspect $colorsProcessor;

    public function injectColorsProcessor(ColorsAspect $colorsProcessor)
    {
        $this->colorsProcessor = $colorsProcessor;
    }

    public function buildForConfiguration(
        ChartConfiguration $chartConfiguration,
        DataTable $dataTable,
        SiteLanguage $language,
        $builderConfiguration
    ): Chart {
        $options = $builderConfiguration['options'];
        $locale = $language->getLocale();
        if (!empty($locale)) {
            $options['locale'] = implode('-', \Locale::parseLocale($locale));
        }

        $chart = new Chart(
            $builderConfiguration['type'],
            $options,
            $this->getChartData($dataTable),
        );

        return $this->process($chartConfiguration, $chart);
    }

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
     * @return array
     */
    protected function createDataSets(DataTable $dataTable): array
    {
        $dataColumns = $dataTable->getColumns();
        $dataSets = [];
        /**
         * @var  $column DataRow
         */
        foreach ($dataColumns as $column) {
            $dataSets[] = [
                // label might not be appropriate for all chart types
                'label' => $column->getLabel(),
                'data' => array_map([$this, 'convertCell'], $column->getCells()),
            ];
        }

        return $dataSets;
    }

    /**
     * @param ChartConfiguration $chartConfiguration
     * @param SiteLanguage $language
     */
    protected function process(ChartConfiguration $chartConfiguration, Chart $chart): Chart
    {
        $chart = $this->colorsProcessor->process($chartConfiguration, $chart);
        return $chart;
    }

    protected function convertCell(DataCell $dataCell)
    {
        return $dataCell->getValue();
    }
}
