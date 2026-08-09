document.addEventListener('DOMContentLoaded', function () {
    initBookingsWeeklyChart();
    initRevenueWeeklyChart();
});

function initBookingsWeeklyChart() {
    const chartEl = document.getElementById('bookingsWeeklyChart');
    if (!chartEl) return;

    const bookingsData = window.dashboardCharts?.bookingsWeekly;
    if (!bookingsData) return;

    const options = {
        chart: {
            type: 'bar',
            height: 300,
            fontFamily: 'inherit',
            toolbar: { show: false },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        series: bookingsData.series,
        xaxis: {
            categories: bookingsData.labels,
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        colors: ['#696cff', '#8592a3'],
        plotOptions: {
            bar: {
                borderRadius: 6,
                columnWidth: '45%',
                distributed: false
            }
        },
        dataLabels: { enabled: false },
        legend: {
            position: 'top',
            horizontalAlign: 'left',
            markers: { radius: 12 }
        },
        grid: {
            borderColor: '#f1f1f1',
            padding: { top: -10, bottom: -10 }
        },
        tooltip: {
            shared: true,
            intersect: false
        }
    };

    new ApexCharts(chartEl, options).render();
}

function initRevenueWeeklyChart() {
    const chartEl = document.getElementById('revenueWeeklyChart');
    if (!chartEl) return;

    const revenueData = window.dashboardCharts?.revenueWeekly;
    if (!revenueData) return;

    const options = {
        chart: {
            type: 'area',
            height: 300,
            fontFamily: 'inherit',
            toolbar: { show: false },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        series: revenueData.series,
        xaxis: {
            categories: revenueData.labels,
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        colors: ['#71dd37', '#ffab00'],
        stroke: {
            curve: 'smooth',
            width: 2.5
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1,
                stops: [0, 90, 100]
            }
        },
        dataLabels: { enabled: false },
        legend: {
            position: 'top',
            horizontalAlign: 'left',
            markers: { radius: 12 }
        },
        grid: {
            borderColor: '#f1f1f1',
            padding: { top: -10, bottom: -10 }
        },
        yaxis: {
            labels: {
                formatter: function (val) {
                    return new Intl.NumberFormat('vi-VN').format(val);
                }
            }
        },
        tooltip: {
            shared: true,
            intersect: false,
            y: {
                formatter: function (val) {
                    return new Intl.NumberFormat('vi-VN').format(val) + ' VND';
                }
            }
        }
    };

    new ApexCharts(chartEl, options).render();
}
