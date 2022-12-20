<?php

declare(strict_types=1);

namespace CPSIT\DenaCharts\Domain\Model;

class ColorScheme
{
    protected string $id;

    /**
     * @var Color[]
     */
    protected array $colors;

    /**
     * @param string $id
     * @param Color[] $colors
     */
    public function __construct(string $id, array $colors)
    {
        $this->id = $id;
        $this->colors = $colors;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string[]|null $colorIds
     * @return Color[]
     */
    public function getColors(?array $colorIds = null): array
    {
        if (isset($colorIds)) {
            $colorsById = $this->getColorsById();
            return array_map(
                function (string $colorId) use ($colorsById): Color {
                    if (!isset($colorsById[$colorId])) {
                        throw new \InvalidArgumentException(
                            sprintf('Color "%s" does not exist in color scheme "%s"', $colorId, $this->getId()),
                            1635929399539
                        );
                    }
                    return $colorsById[$colorId];
                },
                $colorIds
            );
        }

        return $this->colors;
    }

    /**
     * @return array
     */
    protected function getColorsById(): array
    {
        $colorsById = [];
        foreach ($this->getColors() as $color) {
            $colorsById[(string) $color->getId()] = $color;
        }
        return $colorsById;
    }
}
