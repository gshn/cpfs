Vue.component('data-chart', {
    extends: VueChartJs.Line,
    mounted () {
        this.renderChart({
            labels: [12, 14, 3, 5, 2, 13, 5, 16, 10, 19],
            datasets: [{
                label: 'Daily Save Data(GB)',
                backgroundColor: "rgba(255,99,132,0)",
                borderColor: "rgba(255,255,255,1)",
                data: [12, 14, 3, 5, 2, 13, 5, 16, 10, 19],
                borderWidth: 2
            }]
        }, {
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    stacked: true,
                    gridLines: {
                        display: true,
                        color: "rgba(255,255,255,0.2)"
                    },
                    ticks: {
                        display: true,
                        fontColor: '#fff'
                    }
                }],
                xAxes: [{
                    gridLines: {
                        display: false,
                        color: "rgba(255,255,255,0.2)"
                    },
                    ticks: {
                        display: false,
                        fontColor: '#fff'
                    }
                }]
            },
            legend: {
                display: false,
                labels: {
                    fontColor: '#fff',
                    fontSize: '12'
                }
            }
        })
    }
});

Vue.component('invoice-chart', {
    extends: VueChartJs.Doughnut,
    mounted () {
        this.renderChart({
            datasets: [{
                data: [1424000, 4320000, 3453000],
                backgroundColor: [
                    '#00a7e9',
                    '#fcaf16',
                    '#e33a96'
                ]
            }],
            labels: [
                '업로드',
                '다운로드',
                '가입비'
            ]
        }, {
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    stacked: true,
                    gridLines: {
                        display: true,
                        color: "rgba(255,255,255,0.2)"
                    },
                    ticks: {
                        display: true,
                        fontColor: '#fff'
                    }
                }],
                xAxes: [{
                    gridLines: {
                        display: false,
                        color: "rgba(255,255,255,0.2)"
                    },
                    ticks: {
                        display: false,
                        fontColor: '#fff'
                    }
                }]
            },
            legend: {
                display: false,
                labels: {
                    fontColor: '#fff',
                    fontSize: '12'
                }
            }
        })
    }
});
Vue.component('up-down-chart', {
    extends: VueChartJs.Line,
    mounted () {
        this.renderChart({
            labels: [12, 14, 3, 5, 2, 13, 5, 16, 10, 19],
            datasets: [{
                label: '일일 업로드 데이터 (GB)',
                backgroundColor: "rgba(113,191,68,.5)",
                borderColor: "rgba(255,255,255,1)",
                data: [2, 5, 10, 4, 19, 14, 17, 13, 6, 7],
                borderWidth: 2
            },
            {
                label: '일일 다운로드 데이터 (GB)',
                backgroundColor: "rgba(0,167,233,.9)",
                borderColor: "rgba(255,255,255,1)",
                data: [12, 14, 3, 5, 2, 13, 5, 16, 10, 19],
                borderWidth: 2,
                borderDash: [2, 4],
                borderDashOffset: 2
            }]
        }, {
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    stacked: false,
                    gridLines: {
                        display: false,
                        color: "rgba(255,255,255,0.2)"
                    },
                    ticks: {
                        display: false,
                        fontColor: '#fff'
                    }
                }],
                xAxes: [{
                    gridLines: {
                        display: false,
                        color: "rgba(255,255,255,0.2)"
                    },
                    ticks: {
                        display: false,
                        fontColor: '#fff'
                    }
                }]
            },
            legend: {
                display: false,
                labels: {
                    fontColor: '#fff',
                    fontSize: '12'
                }
            }
        })
    }
});

Vue.component('member-chart', {
    extends: VueChartJs.Bar,
    mounted () {
        this.renderChart({
            labels: ['일', '월', '화', '수', '목', '금', '토'],
            datasets: [{
                label: '주간 회원 가입자 (명)',
                backgroundColor: "rgba(252,175,22,1)",
                borderColor: "rgba(255,255,255,1)",
                data: [12, 14, 3, 5, 2, 13, 5],
                borderWidth: 2
            }]
        }, {
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    stacked: false,
                    gridLines: {
                        display: false,
                        color: "rgba(255,255,255,0.2)"
                    },
                    ticks: {
                        display: false,
                        fontColor: '#fff'
                    }
                }],
                xAxes: [{
                    barThickness: 15,
                    gridLines: {
                        display: false,
                        color: "rgba(255,255,255,0.2)"
                    },
                    ticks: {
                        display: true,
                        fontColor: '#000'
                    }
                }]
            },
            legend: {
                display: false,
                labels: {
                    fontColor: '#fff',
                    fontSize: '12'
                }
            }
        })
    }
});

Vue.component('visit-chart', {
    extends: VueChartJs.Line,
    mounted () {
        this.renderChart({
            labels: ['일', '월', '화', '수', '목', '금', '토'],
            datasets: [{
                label: '주간 방문자 (명)',
                backgroundColor: "rgba(227,58,150,.3)",
                borderColor: "rgba(227,58,150,1)",
                data: [12, 14, 3, 5, 2, 13, 5],
                borderWidth: 2,
                borderDash: [2, 4],
                borderDashOffset: 2
            }]
        }, {
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    stacked: false,
                    gridLines: {
                        display: false,
                        color: "rgba(255,255,255,0.2)"
                    },
                    ticks: {
                        display: false,
                        fontColor: '#fff'
                    }
                }],
                xAxes: [{
                    barThickness: 15,
                    gridLines: {
                        display: false,
                        color: "rgba(255,255,255,0.2)"
                    },
                    ticks: {
                        display: false,
                        fontColor: '#000'
                    }
                }]
            },
            legend: {
                display: false,
                labels: {
                    fontColor: '#fff',
                    fontSize: '12'
                }
            }
        })
    }
});
var DASHBOARD = new Vue({
    el: '#dashboard'
});
