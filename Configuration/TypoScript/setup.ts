lib.fluidContent {
    templateRootPaths {
        200 = EXT:dena_charts/Resources/Private/Templates/
    }
}
tt_content.denacharts_chart < lib.fluidContent
tt_content.denacharts_chart{

    templateName = Chart

    dataProcessing {
        1 = CPSIT\DenaCharts\DataProcessing\ChartProcessor
        1 {
            default {
                bar {
                    type = bar
                    datasets {
                        0 {
                            backgroundColor (
                            rgba(255, 99, 132, 0.2)|rgba(54, 162, 235, 0.2)|rgba(255, 206, 86, 0.2)|
                            rgba(75, 192, 192, 0.2)|rgba(153, 102, 255, 0.2)|rgba(255, 159, 64, 0.2)
                            )
                            borderColor (
                            rgb(255,99,132)|rgb(54,162,235)|rgb(255,206,86)|
                            rgb(75,192,192)|rgb(153,102,255)|rgb(199,159,64)
                            )
                            borderWidth = 3
                        }
                    }
                    options {
                        #barPercentage =
                        #categoryPercentage =
                        #barThickness =
                        #maxBarThickness =
                        gridLines.offsetGridLines = false
                        legend {
                            # default: true, allowed: true, false
                            # display = false

                            # default: top, allowed: top, bottom, left, right
                            position = bottom

                            # box takes full canvas width, pushing other boxes down
                            # default: true, allowed: true, false
                            # fullWidth = true

                            # data sets in reverse order
                            # default: false, allowed: true,false
                            # reverse = true
                            #labels {
                            #
                            #}
                        }
                    }
                }
                line {
                    type = line
                    datasets {
                        0 {
                            fill = false
                            lineTension = 0.2
                            #backgroundColor = rgb(75, 192, 192)
                            borderColor = rgb(75, 192, 192)
                            borderWidth = 3
                        }
                    }
                    options {
                        #barPercentage =
                        #categoryPercentage =
                        #barThickness =
                        #maxBarThickness =
                        gridLines.offsetGridLines = false
                    }
                }

                radar {
                    type = radar
                    datasets {
                        0 {
                            fill = false
                            lineTension = 0.2
                            #backgroundColor = rgb(75, 192, 192)
                            borderColor = rgb(75, 192, 192)
                            borderWidth = 3
                        }
                    }
                    options {
                        #barPercentage =
                        #categoryPercentage =
                        #barThickness =
                        #maxBarThickness =
                        gridLines.offsetGridLines = false
                    }
                }
                pie {
                    type = pie
                    datasets {
                        0 {
                            # percentage of cutout in the middle of chart
                            # default: 0
                            #cutoutPercentage = 20

                            # starting angle to draw arcs from
                            # default: -0.5 + Math.Pi
                            #rotation = 0

                            #backgroundColor = rgb(75, 192, 192)
                            backgroundColor (
                            rgba(255, 99, 132, 0.2)|rgba(54, 162, 235, 0.2)|rgba(255, 206, 86, 0.2)|
                            rgba(75, 192, 192, 0.2)|rgba(153, 102, 255, 0.2)|rgba(255, 159, 64, 0.2)
                            )

                            borderColor = rgb(20, 20, 20,0.5)
                            borderWidth = 1
                        }
                    }
                    options {
                        #barPercentage =
                        #categoryPercentage =
                        #barThickness =
                        #maxBarThickness =
                        gridLines.offsetGridLines = false
                    }
                }


                doughnut {
                    type = doughnut
                    datasets {
                        0 {
                            # percentage of cutout in the middle of chart
                            # default: 50
                            #cutoutPercentage = 20

                            # starting angle to draw arcs from
                            # default: -0.5 + Math.Pi
                            #rotation = 0

                            #backgroundColor = rgb(75, 192, 192)
                            backgroundColor (
                            rgba(255, 99, 132, 0.2)|rgba(54, 162, 235, 0.2)|rgba(255, 206, 86, 0.2)|
                            rgba(75, 192, 192, 0.2)|rgba(153, 102, 255, 0.2)|rgba(255, 159, 64, 0.2)
                            )

                            borderColor = rgb(20, 20, 20,0.5)
                            borderWidth = 1
                        }
                    }
                    options {
                        #barPercentage =
                        #categoryPercentage =
                        #barThickness =
                        #maxBarThickness =
                        gridLines.offsetGridLines = false
                    }
                }
            }
        }
    }
}



