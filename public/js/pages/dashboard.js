ChartFirst();
ChartSecond();

function ChartFirst(){
    var historicalBarChart1 = {};
    $.ajax({
        url : "/home/consolidated-chart",
        type : "get",
        dataType : "json",
        success : function(response){
            if(response.status && parseFloat(response.total_present_value_lease_asset) > 0 && parseFloat(response.total_undiscounted_value) > 0){
                historicalBarChart1 = [
                    {
                        key: "Cumulative Return",
                        values: [
                            {
                                "label" : "Undiscounted value",
                                "color": "#d67777",
                                "value" : parseInt(response.total_undiscounted_value)
                            } ,
                            {
                                "label" : "Present value" ,
                                "color": "#4f99b4",
                                "value" : parseInt(response.total_present_value_lease_asset)
                            }
                        ]
                    }
                ];
            }

            nv.addGraph(function() {
                var chart = nv.models.discreteBarChart()
                    .x(function(d) { return d.label; })
                    .y(function(d) { return d.value; })
                    .staggerLabels(true)
                    .showValues(true)
                    .margin({left: 120})
                    .duration(250)
                ;

                d3.select('#chart1 svg')
                    .datum(historicalBarChart1)
                    .call(chart);

                nv.utils.windowResize(chart.update);
                return chart;
            });

        }
    });
}

function ChartSecond(){
    var long_short_data = [
        {
            key: "Undiscounted Value",
            "color": "#d67777",
            values: []
        },
        {
            key: "Present Value",
            "color": "#4f99b4",
            values: []
        }
    ];


    $.ajax({
        url : "/home/categorised-charts",
        dataType : "json",
        type : "get",
        success : function (response) {
            if(response.status){
                var undicounted_values = [];
                var present_value = [];
                $.each(response.data, function (index,value) {
                    $.each(value, function(i,e){
                        if(index == "undiscounted_value"){
                            undicounted_values.push(e);
                        }
                        if(index == "present_value"){
                            present_value.push(e);
                        }
                    });
                });

                long_short_data = [
                    {
                        key: "Undiscounted Value",
                        "color": "#d67777",
                        values: undicounted_values
                    },
                    {
                        key: "Present Value",
                        "color": "#4f99b4",
                        values: present_value
                    }
                ];

            }
            var chart;
            nv.addGraph(function() {
                chart = nv.models.multiBarHorizontalChart()
                    .x(function(d) { return d.label })
                    .y(function(d) { return parseFloat(d.value) })
                    .duration(250)
                    .showControls(true)
                    .showLegend(true)
                    .legendPosition("bottom")
                    .controlsPosition("bottom")
                    .margin({top: 30, right: 50, bottom: 50, left: 200})
                    .stacked(false);

                chart.yAxis.tickFormat(d3.format(',.2f'));
                d3.select("#chart2 svg")
                    .datum(long_short_data)
                    .call(chart);

                nv.utils.windowResize(chart.update);

                chart.dispatch.on("stateChange", function(e) { nv.log("New State:", JSON.stringify(e)); });

                chart.state.dispatch.on("change", function(state){
                    nv.log("state", JSON.stringify(state));
                });

                return chart;
            });
        }
    });
}

