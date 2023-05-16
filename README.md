DENA Charts
===========

Charts allows two display data sets as charts. This is a TYPO3 extension for the DENA project.

This extension has been developed at [CPS IT GmbH](https://cps-it.de).

## Integration



## Editor

Steps to crate a chart:

1. Upload the data in CSV format for the chart to your system.
2. Add the desired chart like any other content element. Select from the chart Tab the desire chart typ.
3. Enter an optional `Header` text for the content element
4. Switch to the tab `Chart` and select the CSV data source file you want to display.
5. Add the required `Axis` and `units` information
6. Select the `Color scheme` and `Colors` to be used by the chart
7. Optional you  can specify a `source`  and a link to it
8. Enter an optional description for the chart
9. If needed change the aspect ratio of the chart


## Integration

You can specify different color schemes for every site in your system. You just need to add the following
configuration to your site config file:

```
...
settings:
  denaCharts:
    colorSchemeFile: EXT:your_extension/Resources/Private/colorschemes.json
...
```

