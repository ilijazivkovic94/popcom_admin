var stackBars = [
    // {
    //     label: 'analyticsLookTimeChart',
    //     key: 'lookTimeByTypeAndDate',
    //     yLabel: 'Seconds',
    //     dataSet: [
    //         {
    //             label: 'Female',
    //             key: 'gender',
    //             data: [],
    //         },
    //         {
    //             label: 'Male',
    //             key: 'gender',
    //             data: [],
    //         },
    //     ]
    // },
    {
        label: 'analyticsLookersChart',
        key: 'lookersByTypeAndDate',
        dataSet: [
            {
                label: 'Female',
                key: 'gender',
                data: [],
            },
            {
                label: 'Male',
                key: 'gender',
                data: [],
            },
        ]
    },
    // {
    //     label: 'analyticsAgeByGenderChart',
    //     key: 'lookTimeByTypeAndDate',
    //     yLabel: 'Seconds',
    //     dataSet: [
    //         {
    //             label: 'Child',
    //             key: 'age',
    //             data: [],
    //         },
    //         {
    //             label: 'Young Adult',
    //             key: 'age',
    //             data: [],
    //         },
    //         {
    //             label: 'Adult',
    //             key: 'age',
    //             data: [],
    //         },
    //         {
    //             label: 'Senior',
    //             key: 'age',
    //             data: [],
    //         },
    //     ]
    // },
    {
        label: 'analyticsLookersByAgeChart',
        key: 'lookersByTypeAndDate',
        dataSet: [
            {
                label: 'Child',
                key: 'age',
                data: [],
            },
            {
                label: 'Young Adult',
                key: 'age',
                data: [],
            },
            {
                label: 'Adult',
                key: 'age',
                data: [],
            },
            {
                label: 'Senior',
                key: 'age',
                data: [],
            },
        ]
    },
    {
        label: 'analyticsEmotionsChart',
        key: 'lookTimeByTypeAndDate',
        yLabel: 'Seconds',
        dataSet: [
            {
                label: 'Male',
                key: 'ageGroupByGender',
                data: [],
            },
            {
                label: 'Female',
                key: 'ageGroupByGender',
                data: [],
            }
        ]
    },
    {
        label: 'analyticsLookersByEmotionChart',
        key: 'lookersByTypeAndDate',
        dataSet: [
            {
                label: 'Neutral',
                key: 'emotion',
                data: [],
            },
            {
                label: 'Disgust',
                key: 'emotion',
                data: [],
            },
            {
                label: 'Surprise',
                key: 'emotion',
                data: [],
            },
            {
                label: 'Happy',
                key: 'emotion',
                data: [],
            },
            {
                label: 'Angry',
                key: 'emotion',
                data: [],
            },
            {
                label: 'Fear',
                key: 'emotion',
                data: [],
            },
            {
                label: 'Sad',
                key: 'emotion',
                data: [],
            },
        ]
    }];
var barChart = [
    {
        label: 'analyticsLookTimeChart',
        key: 'lookTimeByTypeAndDate',
        sub: 'gender',
        labels: ['Male', 'Female'],
    },
    // {
    //     label: 'analyticsLookersChart',
    //     key: 'lookersByTypeAndDate',
    //     sub: 'gender',
    //     labels: ['Male', 'Female'],
    // },
    {
        label: 'analyticsAgeByGenderChart',
        key: 'lookTimeByTypeAndDate',
        sub: 'age',
        labels: ['Child', 'Young Adult', 'Adult', 'Senior'],
    },
    // {
    //     label: 'analyticsLookersByAgeChart',
    //     key: 'lookersByTypeAndDate',
    //     sub: 'age',
    //     labels: ['Child', 'Young Adult', 'Adult', 'Senior'],
    // },
    // {
    //     label: 'analyticsEmotionsChart',
    //     key: 'lookTimeByTypeAndDate',
    //     sub: 'emotion',
    //     labels: ['Neutral', 'Disgust', 'Surprise', 'Happy', 'Angry', 'Fear', 'Sad'],
    // },
    // {
    //     label: 'analyticsLookersByEmotionChart',
    //     key: 'lookersByTypeAndDate',
    //     sub: 'emotion',
    //     labels: ['Neutral', 'Disgust', 'Surprise', 'Happy', 'Angry', 'Fear', 'Sad'],
    // },
];
var colors = ['#995ABF', '#3FC4CF', '#FCE813', '#FF4506', '#FC9117', '#FD237D'];
var pbar_chart1, pbar_chart2, pbar_chart3 = {};
var gender_pbar_chart, age_pbar_chart, emotion_pbar_chart;

moment.tz.setDefault('Etc/UTC');
function drawAnalyticsTotalDonut(draw_data) {
    var bar_ctx = document.getElementById('analyticsChart');
    if (pbar_chart1) {
        pbar_chart1.destroy();
    }
    const data = {
        labels: [
            'Detections Only',
            'Converted',
            // 'Visitors',
            // 'Views',
            // 'Passers',
        ],
        labelOffset: "top",
        datasets: [{
            label: 'My First Dataset',
            // data: [draw_data.visitorCount, draw_data.lookers.length, draw_data.passers.length + draw_data.lookers.length],
            data: [draw_data.passers.length - draw_data.lookers.length, draw_data.lookers.length],
            backgroundColor: [
                'rgb(63, 196, 207)',
                'rgb(153, 90, 191)',
                '#2F8CD0',
            ],
            hoverOffset: 4
        }]
    };
    pbar_chart1 = new Chart(bar_ctx, {
        type: 'pie',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            elements: {
                center: {
                    text: 'Total Passers',
                    value: draw_data.visitorCount + draw_data.lookers.length + draw_data.passers.length,
                    color: 'grey', //Default black
                    fontStyle: 'Helvetica', //Default Arial
                    sidePadding: 15 //Default 20 (as a percentage)
                }
            },
            legend: {
                position: 'bottom',
                labels: {
                    position: 'bottom',
                    usePointStyle: true,
                    fontSize: 15,
                    fontStyle: 'bold'
                }
            },
            plugins: {
                labels: {
                    render: 'percentage',
                    fontColor: '#fff',
                    fontSize: 18,
                }
            }
        }
    });

    return pbar_chart1;
}

function drawAnalyticsTotalArea(labels, draw_data, date_ary) {
    var bar_ctx = document.getElementById('analyticsLineChart');
    if (pbar_chart2) {
        pbar_chart2.destroy();
    }

    var colors = {
        lightBlue: {
            fill: '#3FC4CF',
            stroke: '#6fccdd',
        },
        darkBlue: {
            fill: '#2F8CD0',
            stroke: '#3282bf',
        },
        purple: {
            fill: '#995ABF',
            stroke: '#75539e',
        },
    };

    var visitors = [];
    var lookers = [];
    var passers = [];

    for (var j = 0; j < date_ary.length; j++) {
        var v_data = draw_data.visitors.filter(v => v.timestamp.indexOf(date_ary[j]) > -1);
        var l_data = draw_data.lookers.filter(v => v.timestamp.indexOf(date_ary[j]) > -1);
        var p_data = draw_data.passers.filter(v => v.timestamp.indexOf(date_ary[j]) > -1);

        visitors.push(v_data.length);
        lookers.push(l_data.length);
        passers.push(p_data.length);
    }

    const xData = labels;

    pbar_chart2 = new Chart(bar_ctx, {
        type: 'line',
        data: {
            labels: xData,
            datasets: [
                {
                    label: "Visitors",
                    fill: true,
                    backgroundColor: colors.lightBlue.fill,
                    pointBackgroundColor: colors.lightBlue.stroke,
                    borderColor: colors.lightBlue.stroke,
                    pointHighlightStroke: colors.lightBlue.stroke,
                    borderCapStyle: 'butt',
                    data: visitors,
                },
                {
                    label: "Views",
                    fill: true,
                    backgroundColor: colors.purple.fill,
                    pointBackgroundColor: colors.purple.stroke,
                    borderColor: colors.purple.stroke,
                    pointHighlightStroke: colors.purple.stroke,
                    borderCapStyle: 'butt',
                    data: lookers,

                },
                {
                    label: "Passers",
                    fill: true,
                    backgroundColor: colors.darkBlue.fill,
                    pointBackgroundColor: colors.darkBlue.stroke,
                    borderColor: colors.darkBlue.stroke,
                    pointHighlightStroke: colors.darkBlue.stroke,
                    borderCapStyle: 'butt',
                    data: passers,
                }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            // Can't just just `stacked: true` like the docs say
            scales: {
                yAxes: [{
                    stacked: true,
                }]
            },
            animation: {
                duration: 750,
            },
            elements: {
                // line: {
                //     tension: 0
                // },
                // point: {
                //     radius: 0
                // }
            },
            legend: {
                position: 'top',
                align: "start",
                labels: {
                    fontSize: 15,
                    fontStyle: 'bold'
                }
            }
        }
    });

    return pbar_chart2;
}

function getSumData(array_data) {
    var value = 0;
    if(array_data) {
        array_data.map(item => {
            value += item * 1;
        });
    }
    return value;
}

function getPercentData(array_data, index) {
    var sume_data = getSumData(array_data);
    if(!array_data[index]) {
        return 0;
    }
    return (array_data[index] * 100 / sume_data).toFixed(1);
}

function drawAnalyticsByDonut(draw_data) {
    var gender_bar_ctx = document.getElementById('analyticsGenderChart');
    var age_bar_ctx = document.getElementById('analyticsAgeChart');
    var emotion_bar_ctx = document.getElementById('analyticsEmotionChart');
    if (gender_pbar_chart) {
        gender_pbar_chart.destroy();
    }
    if (age_pbar_chart) {
        age_pbar_chart.destroy();
    }
    if (emotion_pbar_chart) {
        emotion_pbar_chart.destroy();
    }
    const gender_data = {
        labels: [
            'Male ' + getPercentData(draw_data.totalsByGender, 0) + '%',
            'Female ' + getPercentData(draw_data.totalsByGender, 1) + '%',
        ],
        labelOffset: "top",
        datasets: [{
            label: 'My First Dataset',
            data: draw_data.totalsByGender,
            backgroundColor: [
                'rgb(63, 196, 207)',
                'rgb(153, 90, 191)',
            ],
            hoverOffset: 4
        }]
    };
    gender_pbar_chart = new Chart(gender_bar_ctx, {
        type: 'doughnut',
        data: gender_data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutoutPercentage: 65,
            elements: {
                center: {
                    text: 'Gender',
                    color: '#000', //Default black
                    fontStyle: 'Helvetica', //Default Arial
                    fontSize: 36,
                    sidePadding: 15 //Default 20 (as a percentage),
                }
            },
            legend: {
                position: 'right',
                labels: {
                    fontSize: 15,
                    fontStyle: 'bold'
                }
            },
            plugins: {
                labels: {
                    render: false,
                    fontColor: '#fff',
                    fontSize: 0,
                }
            }
        }
    });

    const age_data = {
        labels: [
            'Child ' + getPercentData(draw_data.totalByAgeGroup, 0) + '%',
            'Young Adult ' + getPercentData(draw_data.totalByAgeGroup, 1) + '%',
            'Adult ' + getPercentData(draw_data.totalByAgeGroup, 2) + '%',
            'Senior ' + getPercentData(draw_data.totalByAgeGroup, 3) + '%',
        ],
        labelOffset: "top",
        datasets: [{
            label: 'My First Dataset',
            data: draw_data.totalByAgeGroup,
            backgroundColor: [
                'rgb(63, 196, 207)',
                'rgb(55, 153, 255)',
                'rgb(153, 90, 191)',
                'rgb(252, 232, 19)',
            ],
            hoverOffset: 4
        }]
    };
    age_pbar_chart = new Chart(age_bar_ctx, {
        type: 'doughnut',
        data: age_data,
        options: {
            responsive: true,
            cutoutPercentage: 65,
            elements: {
                center: {
                    text: 'Age',
                    color: '#000', //Default black
                    fontStyle: 'Helvetica', //Default Arial
                    fontSize: 36,
                    sidePadding: 15 //Default 20 (as a percentage)
                }
            },
            legend: {
                position: 'right',
                labels: {
                    fontSize: 15,
                    fontStyle: 'bold'
                }
            },
            plugins: {
                labels: {
                    render: false,
                    fontColor: '#fff',
                    fontSize: 0,
                }
            }
        }
    });

    const emotion_data = {
        labels: [
            'Neutral ' + getPercentData(draw_data.totalByEmotions, 0) + '%',
            'Disgust ' + getPercentData(draw_data.totalByEmotions, 1) + '%',
            'Surprise ' + getPercentData(draw_data.totalByEmotions, 2) + '%',
            'Happy ' + getPercentData(draw_data.totalByEmotions, 3) + '%',
            'Angry ' + getPercentData(draw_data.totalByEmotions, 4) + '%',
            'Fear ' + getPercentData(draw_data.totalByEmotions, 5) + '%',
            'Sad ' + getPercentData(draw_data.totalByEmotions, 6) + '%',
        ],
        labelOffset: "top",
        datasets: [{
            label: 'My First Dataset',
            data: draw_data.totalByEmotions,
            backgroundColor: [
                'rgb(153, 90, 191)',
                'rgb(63, 196, 207)',
                'rgb(55, 153, 255)',
                'rgb(252, 232, 19)',
                'rgb(246, 96, 45)',
                'rgb(248, 152, 42)',
                'rgb(222, 96, 149)',
            ],
            hoverOffset: 4
        }]
    };
    emotion_pbar_chart = new Chart(emotion_bar_ctx, {
        type: 'doughnut',
        data: emotion_data,
        options: {
            responsive: true,
            cutoutPercentage: 65,
            elements: {
                center: {
                    text: 'Emotion',
                    color: '#000', //Default black
                    fontStyle: 'Helvetica', //Default Arial
                    fontSize: 36,
                    sidePadding: 15 //Default 20 (as a percentage)
                }
            },
            legend: {
                position: 'right',
                labels: {
                    fontSize: 15,
                    fontStyle: 'bold'
                }
            },
            plugins: {
                labels: {
                    render: false,
                    fontColor: '#fff',
                    fontSize: 0,
                }
            }
        }
    });
    return {gender_pbar_chart, age_pbar_chart, emotion_pbar_chart};
}

function drawAnalyticsStackBar(elementId, dataSet, labels, draw_data, date_ary, yLabel) {
    var bar_ctx = document.getElementById(elementId);
    const data = {
        labels: labels,
        datasets: []
    };
    var legendOption = {
        position: 'bottom',
        align: "start",
        labels: {
            pointStyle: 'circle',
            fontSize: 15,
            fontStyle: 'bold'
        }
    };
    let yAxesOption = [{
        stacked: true,
        scaleLabel: {
            display: yLabel ? true : false,
            labelString: yLabel
        }
    }];

    if (elementId == 'analyticsEmotionsChart') {
        legendOption = {
            position: 'top',
            align: "center",
            labels: {
                pointStyle: 'circle',
                fontSize: 15,
                fontStyle: 'bold'
            }
        };

        yAxesOption = [{
            stacked: true,
            scaleLabel: {
                display: yLabel ? true : false,
                labelString: yLabel
            },
            ticks: {
                min: 0,
                max: 100,
                stepSize: 50,
                callback: function(value){return value+ "%"}
            }
        }];
    }
    for (var j in dataSet) {
        var result_data = draw_data[dataSet[j].key][j];
        var result = [];
        for(var i = 0; i < date_ary.length; i++) {
            if(elementId == 'analyticsEmotionsChart') {
                result_data = draw_data[dataSet[j].key][i];
                result.push(result_data[j] && result_data[j].total ? result_data[j].total : 0);
            }  else {
                var row = result_data.find(r => r.date_val == date_ary[i]);
                if(row) {
                    result.push(row.total);
                } else {
                    result.push(0);
                }
            }
        }
        data.datasets.push({
            label: dataSet[j].label,
            data: result,
            backgroundColor: colors[j],
        });
    };
    const config = {
        type: 'bar',
        data: data,
        options: {
            stacked: true,
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: [{
                    stacked: true
                }],
                yAxes: yAxesOption
            },
            legend: legendOption,
            plugins: {
                labels: {
                    render: false,
                    fontColor: '#fff',
                    fontSize: 0,
                }
            }
        }
    };

    if (pbar_chart3[elementId]) {
        pbar_chart3[elementId].destroy();
    }

    pbar_chart3[elementId] = new Chart(bar_ctx, config);
    return pbar_chart3[elementId];
}

function drawAnalyticsBar(elementId, labels, draw_data) {
    var bar_ctx = document.getElementById(elementId);
    let graph_data = draw_data.map(d => d.total);
    const data = {
        labels: labels,
        datasets: [{
            label: '',
            data: graph_data,
            backgroundColor: colors
        }]
    };
    const config = {
        type: 'bar',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Seconds'
                    }
                }]
            },
            legend: {
                display: false,
                position: 'bottom',
                align: "start",
                labels: {
                    pointStyle: 'circle',
                    fontSize: 15,
                    fontStyle: 'bold'
                }
            },
            plugins: {
                labels: {
                    render: false,
                    fontColor: '#fff',
                    fontSize: 0,
                }
            }
        }
    };

    if (pbar_chart3[elementId]) {
        pbar_chart3[elementId].destroy();
    }
    pbar_chart3[elementId] = new Chart(bar_ctx, config);
    return pbar_chart3[elementId];
}

function loadVisitorChart(time, kiosk_id = null, startdt = null, enddt = null, accountId = null) {
    var token = $("#_t").val();
    var data = {
        time: time,
        startdt: startdt,
        enddt: enddt,
        _token: token,
        kiosk_id: kiosk_id,
        accountId: accountId
    };
    var days = 0;
    var current = moment().startOf('day');
    var formatString = 'YYYY-MM-DD';
    if(time == 'picker') {
        current = moment(startdt, "YYYY-MM-DD");
        var given = moment(enddt, "YYYY-MM-DD");
        days = moment.duration(given.diff(current)).asDays() + 1;
        formatString = 'MM/DD/YYYY';
    } else if (time == 'today') {
        days = 1;
        formatString = 'MM/DD/YYYY';
    } else if (time == 'week') {
        current = current.endOf('week').isoWeekday(1);
        days = 7;
        formatString = 'DD';
    } else if (time == 'month') {
        current = current.startOf('month');
        days = moment().daysInMonth();
        formatString = 'DD';
    } else if (time == 'year') {
        current = current.startOf('year');
        var startDate = current.format('YYYY-MM-DD');
        var endofYear = current.endOf('year');

        days = moment.duration(endofYear.diff(startDate)).asDays();
        current = moment(startDate);
        formatString = 'MM/DD/YYYY';
    } else if (time == 'lastyear') {
        var lastYear = new Date().getFullYear() * 1 - 1;
        current = moment(lastYear + '-01-01');
        current = current.startOf('year');
        var endofLastYear = current.endOf('year');
        days = moment.duration(endofLastYear.diff(lastYear + '-01-01')).asDays();
        current = moment(lastYear + '-01-01');
        formatString = 'MM/DD/YYYY';
    }
    days = Math.round(days);
    var labels = [];
    var date_ary = [];
    for (var i = 0; i < days; i++) {
        date_ary.push(moment(current).add(i, 'days').format('YYYY-MM-DD'));
        labels.push(moment(current).add(i, 'days').format(formatString));
    }
    data.period_days = days;
    data.start_date = current.format('YYYY-MM-DD');
    $('.card-body').addClass('loading');
    $('.chart-body').addClass('loading');
    $.get(base_url + '/app/traffic-analytics/draw', data, function (res) {
        $('.card-body').removeClass('loading');
        $('.chart-body').removeClass('loading');
        $('#visitors_number').html(res.visitorCount);
        $('#lookers_number').html(res.lookers.length);
        $('#passers_number').html(res.passers.length);
        var attraction_rate = (res.lookers.length * 100 / res.passers.length).toFixed(1);
        $('#attraction_number').html( (attraction_rate && !isNaN(attraction_rate) ? attraction_rate : 0) + '%');
        var engagement_rate = (res.visitorCount * 100 / res.lookers.length).toFixed(1);
        $('#engagement_number').html( (engagement_rate && !isNaN(engagement_rate) ? engagement_rate : 0)+ '%');

        $('#avg_time').html(((res.maleLookTime + res.femaleLookTime) / 2).toFixed(1) + ' seconds');
        $('#avg_female_time').html(res.femaleLookTime.toFixed(1) + ' seconds');
        $('#avg_male_time').html(res.maleLookTime.toFixed(1) + ' seconds');

        drawAnalyticsTotalDonut(res);
        drawAnalyticsByDonut(res);

        drawAnalyticsTotalArea(labels, res, date_ary);

        for (var i in stackBars) {
            if (stackBars[i].label == 'analyticsEmotionsChart') {
                drawAnalyticsStackBar(stackBars[i].label, stackBars[i].dataSet, ['Child', 'Young Adult', 'Adult', 'Senior'], res[stackBars[i].key], ['Child', 'Young Adult', 'Adult', 'Senior'], stackBars[i].yLabel);
            } else {
                drawAnalyticsStackBar(stackBars[i].label, stackBars[i].dataSet, labels, res[stackBars[i].key], date_ary, stackBars[i].yLabel);
            }
        }

        for(var j in barChart) {
            drawAnalyticsBar(barChart[j].label, barChart[j].labels, res[barChart[j].key][barChart[j].sub]);
        }
    });
}

jQuery(document).ready(function () {
    Chart.defaults.global.legend.labels.usePointStyle = true;
    Chart.pluginService.register({
        beforeDraw: function (chart) {
            if (chart.config.options.elements.center) {
                //Get ctx from string
                var ctx = chart.chart.ctx;

                //Get options from the center object in options
                var centerConfig = chart.config.options.elements.center;
                var fontStyle = centerConfig.fontStyle || 'Arial';
                var txt = centerConfig.text;
                var value = centerConfig.value;
                var color = centerConfig.color || '#000';
                var sidePadding = centerConfig.sidePadding || 20;
                var sidePaddingCalculated = (sidePadding / 100) * (chart.innerRadius * 2)
                //Start with a base font of 30px
                ctx.font = "30px " + fontStyle;

                //Get the width of the string and also the width of the element minus 10 to give it 5px side padding
                var stringWidth = ctx.measureText(txt).width;
                var elementWidth = (chart.innerRadius * 2) - sidePaddingCalculated;

                // Find out how much the font can grow in width.
                var widthRatio = elementWidth / stringWidth;
                var newFontSize = Math.floor(30 * widthRatio);
                var elementHeight = (chart.innerRadius * 2);

                // Pick a new font size so it will not be larger than the height of label.
                var fontSizeToUse = centerConfig.fontSize || Math.min(newFontSize, elementHeight);

                //Set font settings to draw it correctly.
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                var centerX = ((chart.chartArea.left + chart.chartArea.right) / 2);
                var centerY = ((chart.chartArea.top + chart.chartArea.bottom - (!isNaN(value) ? fontSizeToUse : 0)) / 2);
                var cenerValueY = ((chart.chartArea.top + chart.chartArea.bottom + fontSizeToUse) / 2);
                ctx.font = fontSizeToUse + "px " + fontStyle;
                ctx.fillStyle = color;

                //Draw text in center
                ctx.fillText(txt, centerX, centerY);
                if (!isNaN(value)) {
                    ctx.fillStyle = '#000';
                    ctx.fillText(String(value).replace(/(.)(?=(\d{3})+$)/g, '$1,'), centerX, cenerValueY);
                }
            }
        }
    });
    jQuery("#subaccount").on('change', function () {
        var accountId = null;
        if ($(this).val() != '') {
            accountId = $(this).val();
            $.ajax(
                {
                    url: baseurl + '/app/ajax/getAccountMachine',
                    type: "post",
                    data: {account_id: accountId, "_token": jQuery("#_t").val()},
                    beforeSend: function () {
                        $('#loader').show();
                    }
                })
                .done(function (data) {
                    if (data.machines.length > 0) {
                        var optHtml = '<option value="">Select machine</option>' +
                            '<option value="">All machines</option>';
                        for (var m of data.machines) {
                            optHtml += '<option value="' + m.kiosk_id + '">' + m.kiosk_identifier + '</option>';
                        }
                        $("#kiosk_id").html(optHtml);
                    }
                    $('#loader').hide();

                })
                .fail(function (jqXHR, ajaxOptions, thrownError) {
                    //$("#product-data").append('<p class="text-center">No more products found</p>');
                });
        } else {
            var optHtml = '<option value="">Select machines</option><option value="">All machines</option>';
            $("#kiosk_id").html(optHtml);
        }
        //visitorTable.draw();
        var daterange = $('input[name="daterange"]').val();
        var time = $("#datePeriod").val();
        var strtdt = null;
        var enddt = null;
        if (daterange != '') {
            var dt = daterange.split(' to ');
            strtdt = dt[0];
            enddt = dt[1];
            time = 'picker';
        }

        loadVisitorChart(time, null, strtdt, enddt, accountId);
    });

    $('input[name="daterange"]').daterangepicker({
        opens: 'center',
        autoUpdateInput: false,
        maxDate: moment(new Date()),
    }, function (start, end) {
        var kiosk_ids = ($("#kiosk_id").val() != "") ? $("#kiosk_id").val() : null;
        var accountId = (jQuery("#subaccount").length > 0) ? jQuery("#subaccount").val() : null;
        loadVisitorChart('picker', kiosk_ids, start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'), accountId);
        $('input[name="daterange"]').val(start.format('MM/DD/YYYY') + ' to ' + end.format('MM/DD/YYYY'));
        console.log("A new date selection was made: " + start.format('MM/DD/YYYY') + ' to ' + end.format('MM/DD/YYYY'));
    });

    $('input[name="daterange"]').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
        var kiosk_ids = ($("#kiosk_id").val() != "") ? $("#kiosk_id").val() : null;
        var accountId = (jQuery("#subaccount").length > 0) ? jQuery("#subaccount").val() : null;
        loadVisitorChart('today', kiosk_ids, null, null, accountId);
    });

    $("#datePeriod").change(function () {
        var time = $(this).val();
        var kiosk_id = ($("#kiosk_id").val() != '') ? $("#kiosk_id").val() : null;
        var accountId = (jQuery("#subaccount").length > 0) ? jQuery("#subaccount").val() : null;
        loadVisitorChart(time, kiosk_id, null, null, accountId);

        var ct = '';
        var start_date = '';
        var end_date = '';

        var current = moment().startOf('day');
        if (time == 'today') {
            ct = "Today";
            start_date = current.format('MM/DD/YYYY');
            end_date = current.format('MM/DD/YYYY');
        } else if (time == 'week') {
            ct = 'Current Week';
            start_date = current.endOf('week').isoWeekday(1).format('MM/DD/YYYY');
            end_date = moment(start_date).add(6, 'days').format('MM/DD/YYYY');
        } else if (time == 'month') {
            ct = 'Current Month';
            start_date = current.startOf('month').format('MM/DD/YYYY');
            end_date = current.endOf('month').format('MM/DD/YYYY');
        } else if (time == 'year') {
            ct = 'Current Year';
            start_date = current.startOf('year').format('MM/DD/YYYY');
            end_date = current.endOf('year').format('MM/DD/YYYY');
        } else if (time == 'lastyear') {
            var lastYear = new Date().getFullYear() * 1 - 1;
            current = moment(lastYear + '-01-01');
            start_date = current.startOf('year').format('MM/DD/YYYY');
            end_date = current.endOf('year').format('MM/DD/YYYY');
            ct = 'Last Year';
        }
        if(start_date && end_date) {
            $('input[name="daterange"]').val(start_date + ' to ' + end_date);
        } else {
            $('input[name="daterange"]').val('');
        }

        $(".chartLable").text(ct);
    });

    $("#kiosk_id").change(function () {
        var kiosk_id = $(this).val();
        var daterange = $('input[name="daterange"]').val();
        if (daterange != '') {
            var dt = daterange.split(' to ');
            var strtdt = dt[0];
            var enddt = dt[1];
            loadVisitorChart('picker', kiosk_ids, strtdt, enddt);
        } else {
            var time = $("#datePeriod").val();
            loadVisitorChart(time, kiosk_id);
        }
        //var kiosk_id = ($("#kiosk_id").val() != '') ? $("#kiosk_id").val() : null;

    });

    loadVisitorChart('today');
});
