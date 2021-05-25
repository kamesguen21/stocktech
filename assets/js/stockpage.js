function get7D(stock_tickers) {
    const lastDate = moment(stock_tickers[stock_tickers.length - 1].date, 'YYYY-MM-DD');
    const firstDate = moment(stock_tickers[0].date, 'YYYY-MM-DD');
    const diff = lastDate.diff(firstDate, 'days');
    let res = {labels: [], data: [],color:"#43494Eab"};
    if (diff > (7)) {
        stock_tickers.forEach(function (ticker) {
            const days = lastDate.diff(moment(ticker.date, 'YYYY-MM-DD'), 'days');
            if (days <= (7)) {
                res.labels.push(ticker.date);
                res.data.push(ticker.close);
            }
        });
    } else {
        res = getMax(stock_tickers);
    }
    return res;
}

function get1M(stock_tickers) {
    const lastDate = moment(stock_tickers[stock_tickers.length - 1].date, 'YYYY-MM-DD');
    const firstDate = moment(stock_tickers[0].date, 'YYYY-MM-DD');
    const diff = lastDate.diff(firstDate, 'days');
    let res = {labels: [], data: [],color:"#17829Cab"};
    if (diff > (30)) {
        stock_tickers.forEach(function (ticker) {
            const days = lastDate.diff(moment(ticker.date, 'YYYY-MM-DD'), 'days');
            if (days <= (30)) {
                res.labels.push(ticker.date);
                res.data.push(ticker.close);
            }
        });
    } else {
        res = getMax(stock_tickers);
    }
    return res;
}

function get6M(stock_tickers) {
    const lastDate = moment(stock_tickers[stock_tickers.length - 1].date, 'YYYY-MM-DD');
    const firstDate = moment(stock_tickers[0].date, 'YYYY-MM-DD');
    const diff = lastDate.diff(firstDate, 'days');
    let res = {labels: [], data: [],color:"#FD6E70ab"};
    if (diff > (180)) {
        stock_tickers.forEach(function (ticker) {
            const days = lastDate.diff(moment(ticker.date, 'YYYY-MM-DD'), 'days');
            if (days <= (180)) {
                res.labels.push(ticker.date);
                res.data.push(ticker.close);
            }
        });
    } else {
        res = getMax(stock_tickers);
    }
    return res;
}

function get1Y(stock_tickers) {
    const lastDate = moment(stock_tickers[stock_tickers.length - 1].date, 'YYYY-MM-DD');
    const firstDate = moment(stock_tickers[0].date, 'YYYY-MM-DD');
    const diff = lastDate.diff(firstDate, 'days');
    let res = {labels: [], data: [],color:"#007BFFab"};
    if (diff > (356)) {
        stock_tickers.forEach(function (ticker) {
            const days = lastDate.diff(moment(ticker.date, 'YYYY-MM-DD'), 'days');
            if (days <= (356)) {
                res.labels.push(ticker.date);
                res.data.push(ticker.close);
            }
        });
    } else {
        res = getMax(stock_tickers);
    }
    return res;
}

function get5Y(stock_tickers) {
    const lastDate = moment(stock_tickers[stock_tickers.length - 1].date, 'YYYY-MM-DD');
    const firstDate = moment(stock_tickers[0].date, 'YYYY-MM-DD');
    const diff = lastDate.diff(firstDate, 'days');
    let res = {labels: [], data: [],color:"#5367FCab"};
    if (diff > (1780)) {
        stock_tickers.forEach(function (ticker) {
            const days = lastDate.diff(moment(ticker.date, 'YYYY-MM-DD'), 'days');
            if (days <= (1780)) {
                res.labels.push(ticker.date);
                res.data.push(ticker.close);
            }
        });
    } else {
        res = getMax(stock_tickers);
    }
    return res;
}

function getMax(stock_tickers) {
    let res = {labels: [], data: [],color:"#33e190ab"};
    stock_tickers.forEach(function (ticker) {
        res.labels.push(ticker.date);
        res.data.push(ticker.close);
    });
    return res;
}


$(document).ready(function () {
    if($('#stock_tickers').length>0){
        refreshMarket();
        setInterval(refreshMarket, 3000); //TODO: change to 3000
        let ctx = $('#myChart');
        let stock_tickers = $('#stock_tickers').attr('data-tickers');
        stock_tickers = JSON.parse(stock_tickers);
        console.log(stock_tickers);
        stock_tickers.sort((a, b) => (moment(a.date).isAfter(b.date)) ? 1 : -1);


        let data = {
            "max": getMax(stock_tickers),
            "5Y": get5Y(stock_tickers),
            "1Y": get1Y(stock_tickers),
            "6M": get6M(stock_tickers),
            "1M": get1M(stock_tickers),
            "7D": get7D(stock_tickers),
        };
        let labels = [];
        let myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: JSON.parse(JSON.stringify(data["max"].labels)),
                datasets: [
                    {
                        label: 'max',
                        data:JSON.parse(JSON.stringify(data["max"].data)),
                        backgroundColor: data["max"].color,
                        fill: true,
                        borderColor: 'rgba(255,255,255,0)',
                    }]
            }, options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                elements: {
                    point: {
                        radius: 0
                    }
                }
            }
        });
        $("#max").addClass('active');
        let charts = [
            'max',
            '5Y',
            '1Y',
            '6M',
            '1M',
            '7D',
        ];
        for (const chart of charts) {
            $('#' + chart).click(function () {
                if (!$(this).hasClass('active')) {
                    removeData(myChart);
                    addData(myChart, data[chart].labels, data[chart].data,data[chart].color);
                }
                for (const cht of charts) {
                    if (cht !== chart) {
                        $('#' + cht).removeClass('active');
                    }
                }
                $(this).addClass('active')
            });
        }
    }

});

function addData(chart, labels, data,color) {
    labels.forEach((label) => {
        chart.data.labels.push(label);
    });
    chart.data.datasets.forEach((dataset) => {
        dataset.backgroundColor=color;
        data.forEach((d) => {
            dataset.data.push(d);
        })
    });
    chart.update();
}

function removeData(chart) {
    while (chart.data.labels.length > 0) {
        chart.data.labels.pop();
    }
    chart.data.datasets.forEach((dataset) => {
        while (dataset.data.length  > 0) {
            dataset.data.pop();
        }
    });
    chart.update();
}

function refreshMarket() {
    const url = $('#homepage_urls').attr("data-now");
    const downSvg = '<i class="" data-fa-i2svg=""><svg class="svg-inline--fa fa-caret-down fa-w-10" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="caret-down" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg=""><path fill="currentColor" d="M31.3 192h257.3c17.8 0 26.7 21.5 14.1 34.1L174.1 354.8c-7.8 7.8-20.5 7.8-28.3 0L17.2 226.1C4.6 213.5 13.5 192 31.3 192z"></path></svg></i>';
    const upSvg = '<i class="" data-fa-i2svg=""><svg class="svg-inline--fa fa-caret-up fa-w-10" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="caret-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg=""><path fill="currentColor" d="M288.662 352H31.338c-17.818 0-26.741-21.543-14.142-34.142l128.662-128.662c7.81-7.81 20.474-7.81 28.284 0l128.662 128.662c12.6 12.599 3.676 34.142-14.142 34.142z"></path></svg></i>';

    var settings = {
        "url": url,
        "method": "GET",
        "timeout": 0,
    };
    $.ajax(settings).done(function (response) {
        console.log(response);
        if (response.status === "OK") {
            let html = '';
            let data = [];
            if (response.stocks) {
                data = data.concat(Object.values(response.stocks));
            }
            if (response.crypto) {
                data = data.concat(Object.values(response.crypto));
            }
            if (response.currencies) {
                data = data.concat(Object.values(response.currencies));
            }
            console.log(data);
            for (const item of data) {
                let base = `<div class="dist_tick__3zKR_ column">
<div class="my-3 mx-5 has-text-light is-size-6">
<div class="dist_price__1olmD">
<span class="has-text-light">${item.ticker}</span>
<span class="has-text-light has-text-weight-bold">$ ${item.lastTrade.p}</span>
</div>`;
                if (item.todaysChange < 0) {
                    base += `<div class="mt-1 has-text-danger"><span class="icon is-left dist_icon__35B3C">${downSvg} </span>${item.todaysChangePerc}%($${item.todaysChange} )</div>`;
                } else {
                    base += `<div class="mt-1 has-text-success"><span class="icon is-left dist_icon__35B3C"> ${upSvg}</span>${item.todaysChangePerc}%($${item.todaysChange} )</div>`;
                }
                base += "</div></div>";
                html += base;
            }
            html = `      <div class="columns is-gapless is-mobile dist_container__1B5i6">${html}</div>`;
            $('#ticker').html(html);
            $('#ticker').show();
        } else {
            $('#ticker').hide();

        }
    });
}


