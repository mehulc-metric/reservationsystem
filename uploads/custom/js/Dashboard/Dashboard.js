function save_data()
{
    $('div .error').html('');
    
}

// Reports of Spectators Per Day
$('#container').highcharts({
    chart: {
        type: 'column'
    },
    credits: {
        enabled: false
    },
    title: {
        text: spectators_title
    },    
    xAxis: {
        categories: hours,
        crosshair: true
    },
    yAxis: {       
        title: {
            text: 'Visitors'
        }
    },
    tooltip: {
        headerFormat:   '<span style="font-size:10px">{series.name}: {point.key}</span><table>',
        pointFormat:    '<tr><td style="color:{series.color};padding:0"></td>' +
                        '<td style="padding:0"><b>Users: {point.y:1f}</b></td></tr>',
        footerFormat:   '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [{
        name: spectators_X_lebal,
        data: spectators_data

    }]
});
// Reports of Expected Afluence Per Day
$('#container1').highcharts({
  chart: {
        type: 'column'
    },
    credits: {
        enabled: false
    },
    title: {
        text: afluence_title
    },   
    xAxis: {
        categories: hours,
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Visitors'
        }
    },
    tooltip: {
        headerFormat:   '<span style="font-size:10px">{series.name}: {point.key}</span><table>',
        pointFormat:    '<tr><td style="color:{series.color};padding:0"></td>' +
                        '<td style="padding:0"><b>Users: {point.y:1f}</b></td></tr>',
        footerFormat:   '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [{
        name: afluence_X_lebal,
        data: reserved_data

    }]
}); 

// filterSpectators
function filterSpectators(){
    var spectactors_filter = $("#spectators").val();
    var filterURL = base_url_filter + "/Dashboard/filterSpectators";

    $.ajax({
        url: filterURL,
        type: "POST",
        dataType: "json",
        data: {filterType: spectactors_filter},
        success: function (response)
        {
            // Reports of Spectators Per Day
            $('#container').highcharts({
                chart: {
                    type: 'column'
                },
                credits: {
                    enabled: false
                },
                title: {
                    text: response[0].spectators_title
                },
                xAxis: {
                    categories: response[0].hours_data,
                    crosshair: true
                },
                yAxis: {
                    title: {
                        text: 'Visitors'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{series.name}: {point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0"></td>' +
                            '<td style="padding:0"><b>Users: {point.y:1f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                        name: response[0].spectators_X_lebal,
                        data: response[0].spectators_data

                    }]
            });

        }
    });
}

// filterExpectedAfluence
function filterExpectedAfluence(){
    var expected_Afluence = $("#expected_Afluence").val();
    var filterURL = base_url_filter + "/Dashboard/filterExpectedAfluence";

    $.ajax({
        url: filterURL,
        type: "POST",
        dataType: "json",
        data: {filterType: expected_Afluence},
        success: function (response)
        {
            // Reports of Spectators Per Day
            $('#container1').highcharts({
                chart: {
                    type: 'column'
                },
                credits: {
                    enabled: false
                },
                title: {
                    text: response[0].afluence_title
                },
                xAxis: {
                    categories: response[0].hours_data,
                    crosshair: true
                },
                yAxis: {
                    title: {
                        text: 'Visitors'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{series.name}: {point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0"></td>' +
                            '<td style="padding:0"><b>Users: {point.y:1f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                        name: response[0].afluence_X_lebal,
                        data: response[0].spectators_data

                    }]
            });
        }
    });
}