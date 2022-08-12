var site_url = 'http://localhost/trackasins/';
// var site_url = 'https://dev.trackasins.com/';
// var site_url ='http://www.trackasins.loc/';
$(document).ready(function() {
    // localStorage.removeItem(DataTables_Table_1);
    dataTableShow();
});

function dataTableShow(){
    $('.mainTable').DataTable( {
	dom:"<'myfilter'f><'mylength'l>t",
	fixedHeader: {
            headerOffset: 90
        },
        responsive: true,
        "processing": true,
        "serverSide": true,
        "ajax": site_url+"notifications/datatable_ssp",
        "paging": true,
        "pageLength": 10,
        "lengthMenu": [
            [10, 20, 30],
            [10, 20, 30]
        ],
        'columnDefs': [
            {
                'targets': 0,
                'createdCell':  function (td, cellData, rowData, row, col) {
                    $(td).attr('class', 'text-center vartical-middle');
                }
            },
            {
                'targets': 1,
                'createdCell':  function (td, cellData, rowData, row, col) {
                    $(td).attr('class', 'text-center verticle-middle');
                    $(td).attr('title', extractContent(cellData));
                }
            },
            {
                'targets': 2,
                'createdCell':  function (td, cellData, rowData, row, col) {
                    $(td).attr('class', 'text-center verticle-middle');
                }
            },
            {
                'targets': 3,
                'createdCell':  function (td, cellData, rowData, row, col) {
                    $(td).attr('class', 'text-center b red verticle-middle');
                }
            },
            {
                'targets': 4,
                'createdCell':  function (td, cellData, rowData, row, col) {
                    $(td).attr('class', 'text-center b red verticle-middle');
                }
            }
        ],
        "drawCallback": function( settings ) {
            var table = this.api().table();
            table.rows().eq(0).each(function (index) {
                var row = table.row( index );
                row.child(format(row.data())).show();
            });
        }
    });
}

function format ( d ) {
    if (d[98] == 1) {
        var
            message = 'Amazon ran out of stock on ',
            emoji = 'fa fa-smile-o',
            backgroundColour = '#d27842',
            stockClass = 'out-of-stock',
            extraStyle = '';
    } else {
        var
            message = 'Amazon back in stock on ',
            emoji = 'fa fa-frown-o',
            backgroundColour = '#aaa',
            stockClass = 'in-stock-on',
            extraStyle = 'style="color: white;"';
    }
    return '<table></table><tr role="row" class="even">' +
        '<td style="width: 100%;padding: 20px;" colspan="5">' +
        '<div style="margin-top: 5px">' +
        '<ul class="activityHolder">' +
        '<li class="clearfix '+ stockClass +'">' +
        '<div class="col2 col-lg-1">' +
        '<div class="date" '+ extraStyle +'></div>' +
        '</div>' +
        '<div class="col1 col-lg-11">' +
        '<div class="contm">' +
        '<div class="cont-col1">' +
        '<div class="label label-sm label-info" style="font-size: 1.5em;background: '+ backgroundColour +'">' +
        '<i class="'+ emoji +'" aria-hidden="true"></i>' +
        '</div>' +
        '</div>' +
        '<div class="cont-col2">' +
        '<div class="desc">'+ message +' '+ d[99] +'</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</li>' +
        '</ul>' +
        '</div>' +
        '</td>' +
        '<td style="display: none;"></td>' +
        '<td style="display: none;"></td>' +
        '<td style="display: none;"></td>' +
        '<td style="display: none;"></td>' +
        '</tr></table>';
}

function extractContent(s) {
    var span = document.createElement('span');
    span.innerHTML = s;
    return span.textContent || span.innerText;
}
