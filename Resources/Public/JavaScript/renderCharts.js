/**
 * renderCharts.js
 * Renders charts using a chart library. Currently supports only ChartJs (@see http://chartjs.org).
 * Support for other libraries as Google Charts may be implemented later.
 */

/**
 * Dena charts object.
 * @type {{}}
 */
var denaCharts = denaCharts || {};

(function () {
  var charts = []; // keeps all charts

  /**********
   * PUBLIC METHODS
   **********/

  /**
   * Reads data and configuration from all canvases and builds charts accordingly.
   * Existing chart objects are replaced.
   */
  this.initializeCharts = function () {
    var canvases = document.getElementsByClassName('tx-dena-charts-canvas');
    for (var c = 0; c < canvases.length; c++) {
      var canvas = canvases[c];

      var ctx = canvas.getContext('2d');

      let options = JSON.parse(canvas.dataset.options);

      // Add customized tooltip to display y axis unit
      options = {...options, ...{
          'plugins': {
            'tooltip': {
              'callbacks': {
                label: function(context) {
                  let label = context.dataset.label || '';

                  if (label) {
                    label += ': ';
                  }
                  if (context.formattedValue !== null) {
                    label += context.formattedValue;
                    let unit = context.chart?.options?.scales?.y?.unit
                    if (unit !== undefined) {
                      label += ' [' + unit + ']';
                    }
                  }
                  return label;
                }
              }
            }
          }
      }};

      charts[c] = new Chart(
        ctx,
        {
          type: canvas.dataset.type,
          data: JSON.parse(canvas.dataset.data),
          options: options
        }
      );
    }
  };
}).apply(denaCharts);

(function () {
  denaCharts.initializeCharts();
})();
