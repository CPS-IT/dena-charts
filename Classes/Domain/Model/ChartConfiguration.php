<?php

namespace CPSIT\DenaCharts\Domain\Model;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class ChartConfiguration extends AbstractEntity
{
    const CHART_TYPE_AREA = 'area';

    const CHART_TYPE_BAR = 'bar';

    const CHART_TYPE_COLUMN = 'column';

    const CHART_TYPE_DOUGHNUT = 'doughnut';

    const CHART_TYPE_LINE = 'line';

    const CHART_TYPE_PIE = 'pie';

    const CHART_TYPES = [
        self::CHART_TYPE_AREA,
        self::CHART_TYPE_BAR,
        self::CHART_TYPE_COLUMN,
        self::CHART_TYPE_DOUGHNUT,
        self::CHART_TYPE_LINE,
        self::CHART_TYPE_PIE,
    ];

    protected string $type = '';

    /**
     * @var ObjectStorage<FileReference>|null
     */
    protected ?ObjectStorage $dataFile = null;

    protected string $axisXTitle = '';

    protected string $axisXUnit = '';

    protected int $axisY2FirstColumn = 0;

    protected string $axisY2Title = '';

    protected string $axisY2Unit = '';

    protected string $axisYTitle = '';

    protected string $axisYUnit = '';

    protected string $aspectRatio = '';

    protected bool $stack = false;

    protected string $colorScheme = '';

    protected string $colors = '';

    protected bool $enableZoom = false;

    protected bool $showGridLines = false;

    protected bool $showPoints = false;

    public function getType(): string
    {
        return substr($this->type, strlen('denacharts_chart_'));
    }

    public function getDataFile(): \TYPO3\CMS\Core\Resource\FileReference
    {
        return $this->dataFile->offsetGet(0)->getOriginalResource();
    }

    public function getAxisXTitle(): string
    {
        return $this->axisXTitle;
    }

    public function getAxisXUnit(): string
    {
        return $this->axisXUnit;
    }

    public function getAxisY2FirstColumn(): ?int
    {
        if ($this->axisY2FirstColumn === 0) {
            return null;
        }
        return $this->axisY2FirstColumn;
    }

    public function getAxisY2Title(): string
    {
        return $this->axisY2Title;
    }

    public function getAxisY2Unit(): string
    {
        return $this->axisY2Unit;
    }

    public function getAxisYTitle(): string
    {
        return $this->axisYTitle;
    }

    public function getAxisYUnit(): string
    {
        return $this->axisYUnit;
    }

    public function getAspectRatio(): string
    {
        return $this->aspectRatio;
    }

    public function isZoomEnabled(): bool
    {
        return $this->enableZoom;
    }

    public function isStack(): bool
    {
        return $this->stack;
    }

    public function getColorScheme(): string
    {
        return $this->colorScheme;
    }

    public function getColors(): string
    {
        return $this->colors;
    }

    public function isShowGridLines(): bool
    {
        return $this->showGridLines;
    }

    public function isShowPoints(): bool
    {
        return $this->showPoints;
    }
}
