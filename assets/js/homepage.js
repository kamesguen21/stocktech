$(document).ready(function () {
    refreshMarket();
    setInterval(refreshMarket, 3000);
    getNews();
    $(document).on("click", ".tr-clk", function () {
        let base = $("#homepage_urls").attr('data-stock-page');
        let id = $(this).attr('data-id');
        window.location.replace(base+'stock/view/' + id);
    });
});

function getNews() {
    const url = $('#homepage_urls').attr("data-news");
    var settings = {
        "url": url,
        "method": "GET",
        "timeout": 0,
    };
    $.ajax(settings).done(function (response) {
        if (response.success) {
            handleNews(response.news);
        }
    });
}

function handleNews(news) {
    let card = '';
    let cards = '';
    news.forEach(function (item, key) {
        let date = moment(item.publishedAt.date, 'YYYY-MM-DD HH:mm:ss.SSS');
        if (key < 3) {
            card += `     <a class="d-flex border-bottom-blue pt-3 pb-4 align-items-center justify-content-between text-white" style="text-decoration: none;" href="${item.url}">
                                <div class="pr-3">
                                    <h6>${item.title}</h6>
                                    <div class="fs-12">
                                        <span class="mr-2">Source </span>${item.source_name}
                                    </div>
                                </div>
                                <div class="rotate-img">
                                    <img src="${item.urlToImage}" alt="thumb" class="img-fluid img-lg">
                                </div>
                            </a>`;
        }
        cards += `<div class="row">
                            <div class="col-sm-4 grid-margin">
                                <div class="position-relative">
                                    <div class="rotate-img">
                                        <img src="${item.urlToImage}" alt="thumb" class="img-fluid">
                                    </div>
                                    <div class="badge-positioned">
                                        <span class="badge badge-danger font-weight-bold">${date.format('L')}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8  grid-margin">
                                <a class="mb-2 h2 font-weight-600" style="text-decoration: none;" href="${item.url}">
                                  ${item.title}
                                </a>
                                <div class="fs-13 mb-2">
                                    <span class="mr-2">Source </span>${item.source_name}
                                </div>
                                <p class="mb-0">
                                    ${item.content}
                                </p>
                            </div>
                        </div>`;
    });
    $('#news_container_1').append(card);
    $('#news_container_2').html(cards);

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
