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
   * Set a deeply nested property of an object, creating empty objects
   * along the path, if the properties do not already exist.
   *
   * @param object object object to modify
   * @param path array path of object properties
   * @param value
   */
  this.setObjectPath = function(object, path, value)
  {
    nextKey = path.shift();
    if (path.length === 0) {
      object[nextKey] = value;
      return;
    }

    if (object[nextKey] === undefined) {
      object[nextKey] = {};
    }

    this.setObjectPath(object[nextKey], path, value);
  }

  this.capturePng = function(captureArea, filename) {
    html2canvas(captureArea, {
      ignoreElements: function(element) {
        return element.classList.contains('tx-dena-charts-no-capture');
      }
    })
      .then(canvas => {
        canvas.style.display = 'none'
        document.body.appendChild(canvas)
        return canvas
      })
      .then(canvas => {
        const image = canvas.toDataURL('image/png').replace('image/png', 'image/octet-stream')
        const a = document.createElement('a')
        a.setAttribute('download', filename)
        a.setAttribute('href', image)
        a.click()
        canvas.remove()
      });
  }

  this.onCaptureButtonClick = function(event, captureButton) {
    const captureAreaId = captureButton.getAttribute('data-capture-area');
    const captureFilename = captureButton.getAttribute('data-capture-filename')
    const captureArea = document.getElementById(captureAreaId);
    this.capturePng(captureArea, captureFilename);
  }

  this.initializePngCapture = function () {
    var captureButtons = document.getElementsByClassName('tx-dena-charts-capture-button');
    for (const captureButton of captureButtons) {
      const self = this;
      captureButton.addEventListener('click', function(event) {
        self.onCaptureButtonClick(event, captureButton);
      })
    }
  }

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

      // Match the sorting of tooltip items to the legend
      if (options.plugins.legend.reverse) {
        options.plugins.tooltip.itemSort = function(a, b) { return b.datasetIndex - a.datasetIndex }
      }

      // Add customized tooltip to display y axis unit
      this.setObjectPath(options, ['plugins', 'tooltip', 'callbacks', 'label'],
        function(context) {
          let isPieChart = context.chart.config.type === "pie";
          let label = isPieChart ? context.label : (context.dataset.label || '');

          if (label) {
            label += ': ';
          }
          if (context.formattedValue !== null) {
            label += context.formattedValue;
            let yAxisID = context.dataset.yAxisID ?? 'y';
            let axisOptions = context.chart?.options?.scales[yAxisID];
            if (axisOptions !== undefined) {
              let unit = axisOptions.unit;
              if (unit !== undefined) {
                label += ' [' + unit + ']';
              }
            }
          }
          return label;
        }
      );

      this.setObjectPath(options, ['elements', 'point', 'radius'],
        function(context) {
          const defaultRadius = context.chart?.options?.defaultPointRadius ?? 5;
          const highlightRadius = defaultRadius > 0 ? defaultRadius * 2 : 5;
          return context.raw?.highlight === true ? highlightRadius : defaultRadius;
        }
      );

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
  denaCharts.initializePngCapture();
})();
