/*!
* Start Bootstrap - Scrolling Nav v4.3.0 (https://startbootstrap.com/template/scrolling-nav)
* Copyright 2013-2021 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-scrolling-nav/blob/master/LICENSE)
*/

$(document).ready(function () {
    console.log("dsqdddddddddd");
    let async = $('#async').length > 0;
    const nav = $("#pagination_nav");
    const page = nav.attr('data-page') * 1;
    const pagination = createPagination(page);
    if (pagination.pages.length > 1) {
        if (async) {
            let querying = false;
            $(document).on("click", ".page-link", function () {
                if (!$(this).parent().hasClass("active") && !querying) {
                    console.log("clicked");
                    const url = $(this).attr("data-href");
                    const page = $(this).attr("data-page");
                    const self = $(this).parent();
                    var settings = {
                        "url": url,
                        "method": "GET",
                        "timeout": 0,
                    };
                    $.ajax(settings).done(function (response) {
                        if (response.success) {
                            handleTable(response.data);
                            $('.page-item').each(function () {
                                $(this).removeClass("active");
                            });
                            $(self).addClass("active");
                            createPagination(page);
                        }
                    });
                }
            });
        }
    }
    let sync = $('#sync').length > 0;
    if (sync && !async) {
        let data = $('#stock_tickers').attr('data-tickers');
        data = JSON.parse(data);
        console.log(data);
        data.sort((a, b) => (moment(a.date).isAfter(b.date)) ? 1 : -1);
        let paginated = paginateList(data);
        handlePagination(paginated);
        if (data[data.length - 1] && data[data.length - 2]) {
            const priceChange = getTodaysChange(data[data.length - 1].close , data[data.length - 2].close).toFixed(3);
            const priceChangeperc = getTodaysChangePerc(data[data.length - 1].close , data[data.length - 2].close).toFixed(3);

            let v='';
            if(priceChange>=0){
                 v = `        <h4 class="text-white">$${data[data.length - 1].close}</h4>
                    <h5 class="has-text-success">  ${priceChange} (+${priceChangeperc}%)</h5>
                    <h6 class="small text-white">DATA AS OF ${moment(data[data.length - 1].date, 'YYYY-MM-DD').format('MMM DD, YYYY')}</h6>
                    <span class="symbol-page-header__bar_success"></span>`;
            }else{
                 v = `        <h4 class="text-white">$${data[data.length - 1].close}</h4>
                    <h5 class="has-text-danger">  ${priceChange} (${priceChangeperc}%)</h5>
                    <h6 class="small text-white">DATA AS OF ${moment(data[data.length - 1].date, 'YYYY-MM-DD').format('MMM DD, YYYY')}</h6>
                    <span class="symbol-page-header__bar_danger"></span>`;
            }
            $('#stock_val').html(v);
        }

    }
});

function getTodaysChange(d1, d0) {
    return d1 - d0;
}
function getTodaysChangePerc(d1, d0) {
    return ((d1 - d0) / d1) * 100;
}

function createPagination(page) {
    let async = $('#async').length > 0;
    let sync = $('#sync').length > 0;
    const nav = $("#pagination_nav");
    let maxPages = (async||sync) ? 10 : 5;
    const pages = nav.attr('data-pages') * 1;
    const total = nav.attr('data-total') * 1;
    const pagination = paginate(total, page, 10, maxPages);
    let pagesList = [];
    let path = nav.attr('data-path');
    let ulHtml = '';
    if (pagination.pages.length > 1) {
        if (async) {
            path = nav.attr('data-url');
            if (pagination.currentPage > 1) {
                ulHtml += `<li class="page-item"><span class="page-link" data-href="${path + '?p=' + (+pagination.currentPage - 1)}" data-page="${pagination.currentPage - 1}">Previous</span></li>`;
            }
            pagination.pages.forEach(function (item) {
                ulHtml += `<li class="page-item ${item === +pagination.currentPage ? ' active ' : ''} ${item !== '...' ? '' : 'disabled'}"><span class="page-link" data-page="${item}" data-href="${item !== '...' ? (path + '?p=' + item) : ''}">${item}</span></li>`;
            });
            if (+pagination.currentPage !== +pagination.totalPages) {
                ulHtml += `<li class="page-item"><span class="page-link"  data-href="${path + '?p=' + (+pagination.currentPage + 1)}" data-page="${pagination.currentPage + 1}">Next</span></li>`;
            }
        } else if (sync) {
            ulHtml += `<li class="page-item ${pagination.currentPage > 1 ? '' : 'disabled'}"><span class="page-link"  data-page="${pagination.currentPage - 1}">Previous</span></li>`;
            pagination.pages.forEach(function (item) {
                ulHtml += `<li class="page-item ${item === +pagination.currentPage ? ' active ' : ''} ${item !== '...' ? '' : 'disabled'}"><span class="page-link" data-page="${item}">${item}</span></li>`;
            });
            ulHtml += `<li class="page-item ${+pagination.currentPage !== +pagination.totalPages ? '' : 'disabled'}""><span class="page-link"  data-page="${pagination.currentPage + 1}">Next</span></li>`;
        } else {
            if (pagination.currentPage > 1) {
                ulHtml += `<li class="page-item"><a class="page-link" href="${path + '?p=' + (+pagination.currentPage - 1)}" >Previous</a></li>`;
            }
            pagination.pages.forEach(function (item) {
                ulHtml += `<li class="page-item ${item === +pagination.currentPage ? ' active ' : ''} ${item !== '...' ? '' : 'disabled'}"><a class="page-link" href="${item !== '...' ? (path + '?p=' + item) : ''}">${item}</a></li>`;
            });
            if (+pagination.currentPage !== +pagination.totalPages) {
                ulHtml += `            <li class="page-item"><a class="page-link"  href="${path + '?p=' + (+pagination.currentPage + 1)}">Next</a></li>`;
            }
        }
        $('#pagination').html(ulHtml);

    }

    return pagination;
}

function handleTable(data) {
//stocks_table
    if (data && data.length > 0) {
        let tableBody = '';
        data.forEach(function (stock) {
            tableBody += `       <tr class="tr-clk" data-id="${stock.id}">
                                <td>${stock.symbol}</td>
                                <td>${stock.description.name}</td>
                                <td>${stock.description.industry}</td>
                                <td>${stock.description.marketcap}</td>
                            </tr>`;
        });
        $('#stocks_table').html(tableBody);

    } else {
        $('#stocks_table').html(` <tr class="text-center"><td colspan="4">no records found</td> </tr>`);
    }
}

function paginate(totalItems, currentPage = 1, pageSize = 10, maxPages = 10) {
    totalItems = parseInt(totalItems);
    currentPage = parseInt(currentPage);
    pageSize = parseInt(pageSize);
    maxPages = parseInt(maxPages);
    // calculate total pages
    let totalPages = Math.ceil(totalItems / pageSize);

    // ensure current page isn't out of range
    if (currentPage < 1) {
        currentPage = 1;
    } else if (currentPage > totalPages) {
        currentPage = totalPages;
    }

    let startPage, endPage;
    if (totalPages <= maxPages) {
        // total pages less than max so show all pages
        startPage = 1;
        endPage = totalPages;
    } else {
        // total pages more than max so calculate start and end pages
        let maxPagesBeforeCurrentPage = Math.floor(maxPages / 2);
        let maxPagesAfterCurrentPage = Math.ceil(maxPages / 2) - 1;
        if (currentPage <= maxPagesBeforeCurrentPage) {
            // current page near the start
            startPage = 1;
            endPage = maxPages;
        } else if (currentPage + maxPagesAfterCurrentPage >= totalPages) {
            // current page near the end
            startPage = totalPages - maxPages + 1;
            endPage = totalPages;
        } else {
            // current page somewhere in the middle
            startPage = currentPage - maxPagesBeforeCurrentPage;
            endPage = currentPage + maxPagesAfterCurrentPage;
        }
    }

    // calculate start and end item indexes
    let startIndex = (currentPage - 1) * pageSize;
    let endIndex = Math.min(startIndex + pageSize - 1, totalItems - 1);

    // create an array of pages to ng-repeat in the pager control
    let pages = Array.from(Array((endPage + 1) - startPage).keys()).map(i => startPage + i);
    if (!pages.includes(totalPages)) {
        if (!pages.includes(totalPages - 2)) {
            pages.push('...');
        }
        if (!pages.includes(totalPages - 1)) {
            pages.push(totalPages - 1);
        }
        pages.push(totalPages);
    }
    if (!pages.includes(1)) {
        if (!pages.includes(3)) {
            pages.unshift('...');
        }
        if (!pages.includes(2)) {
            pages.unshift(2);
        }
        pages.unshift(1);

    }
    // return object with all pager properties required by the view
    return {
        totalItems: totalItems,
        currentPage: currentPage,
        pageSize: pageSize,
        totalPages: totalPages,
        startPage: startPage,
        endPage: endPage,
        startIndex: startIndex,
        endIndex: endIndex,
        pages: pages
    };
}

function paginateList(data) {
    const perChunk = 10;// items per chunk
    let resultArray = [];
    let result = data.reduce((data, item, index) => {
        const chunkIndex = Math.floor(index / perChunk);
        if (!resultArray[chunkIndex]) {
            resultArray[chunkIndex] = [] // start a new chunk
        }
        resultArray[chunkIndex].push(item);
        return resultArray
    }, []);
    console.log(result);
    return result;
}

function createRows(items) {
    let html = '';
    items.forEach(function (ticker) {
        html += `    <tr>
                                <td>${moment(ticker.date, 'YYYY-MM-DD').format('L')}</td>
                                <td>${ticker.open}</td>
                                <td>${ticker.hight}</td>
                                <td>${ticker.low}</td>
                                <td>${ticker.close}</td>
                                <td>${ticker.adjClose}</td>
                                <td>${Number(ticker.volume).toLocaleString('en-US', {maximumFractionDigits: 0})}</td>
                            </tr>`;
    });
    $('#stocks_table').html(html);
}

function handlePagination(paginated) {
    if (paginated && paginated[0] && paginated[0].length > 0) {
        createRows(paginated[0]);
    }
    $(document).on("click", ".page-link", function () {
        if (!$(this).parent().hasClass("active") && !$(this).parent().hasClass("disabled")) {
            console.log("clicked");
            const page = $(this).attr("data-page");
            createRows(paginated[page - 1]);
            $('.page-item').each(function () {
                $(this).removeClass("active");
            });
            $(this).addClass("active");
            createPagination(page);
        }
    });
}
