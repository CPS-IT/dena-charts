mod.wizards.newContentElement.wizardItems.common {
    elements {
            chart {
                iconIdentifier = tx_denacharts_chart
                title = LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:wizards.newContentElement.chartTitle
                description = LLL:EXT:dena_charts/Resources/Private/Language/locallang_db.xlf:wizards.newContentElement.chartDescription
                tt_content_defValues {
                    CType = denacharts_chart
                }
            }

    }
    show := addToList(chart)
}
