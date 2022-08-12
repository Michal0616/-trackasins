var today = $("#today-date").val();

$(function(){
    $(document).on('click', '.toggle', function(){
        var open = $(this).data('open');

        if(open != "") {
            $(".innerContainer").addClass('hidden');
            $("." + open).removeClass('hidden');

            $(".toggle").removeClass('active');
            $(this).addClass('active');
        }
    });

    $(document).on("click", "input[name=date-range]", function () {
        var val = $(this).val(),
            startEl = $("#custom-start-date"),
            endEl = $("#custom-end-date");
        if (val && val !== "custom") {
            startEl.val(val);
            endEl.val(today);
        } else if (!isMultiple && !val) {
            startEl.val(startDate);
        } else if (isMultiple && !val) {
            startEl.attr('type', type);
            startEl.val(startDate);
        }
    });

    $(document).on("change", "#custom-start-date,#custom-end-date", function () {
        $("#checkbox9").trigger('click');
    });

    $.fn.dataTable.ext.order['dom-checkbox'] = function  ( settings, col )
    {
        return this.api().column( col, {order:'index'} ).nodes().map( function ( td, i ) {
            return $('input', td).prop('checked') ? '1' : '0';
        } );
    }

    $('.mainTable').DataTable({
	dom:"<'myfilter'f><'mylength'l>t",
	fixedHeader: {
            headerOffset: 90
        },
        responsive: true,
        //stateSave: true,
        stateSaveCallback: function(settings,data) {
            localStorage.setItem( 'DataTables_' + settings.sInstance, JSON.stringify(data) )
        },
        stateLoadCallback: function(settings) {
            return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
        },
        "pageLength": 50,
        "lengthMenu": [
            [10, 25, 50, 100, 250, 500, 1000, 2000, -1],
            [10, 25, 50, 100, 250, 500, 1000, 2000, "All"]
        ],
        "language": {
            "lengthMenu": "Show _MENU_ products"
        },
        "aoColumns": [
            null,
            null,
            null,
            { "sSortDataType": "dom-checkbox" }
        ]
    });
    $('.mainTable_multi').DataTable({
	dom:"<'myfilter'f><'mylength'l>t",
        fixedHeader: {
            headerOffset: 90
        },
        //stateSave: true,
        stateSaveCallback: function(settings,data) {
            localStorage.setItem( 'DataTables_' + settings.sInstance, JSON.stringify(data) )
        },
        stateLoadCallback: function(settings) {
            return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
        },
        "pageLength": 50,
        "lengthMenu": [
            [10, 25, 50, 100, 250, 500, 1000, 2000, -1],
            [10, 25, 50, 100, 250, 500, 1000, 2000, "All"]
        ],
        "language": {
            "lengthMenu": "Show _MENU_ products"
        },
        "aoColumns": [
            null,
            null,
            null,
            null
            //{ "sSortDataType": "dom-checkbox" }
        ]
    });
});

var asins = [],
    startDate = today,
    type = 'date',
    isMultiple = false;

$('[data-fancybox="images"]').fancybox({});
function clear() {
    asins = [];
    startDate = today;
    type = 'date';
    isMultiple = false;
    $("#checkbox1").trigger('click');
}

function runSingle(asin, date) {
    clear();
    asins.push(asin);
    startDate = date;
    openModal();
}

function runMultiple() {
    clear();
    var totalAsins = $('.product-select').length,
        timestamps = [],
        timestampMapping = {};

    $('.product-select:checked').each(function() {
        var $this = $(this);
        asins.push($this.val());
        timestamps.push($this.attr('data-timestamp'));
        timestampMapping[$this.attr('data-timestamp')] = $this.attr('data-date');
    });

    if(!asins.length) {
        return alert("Please select at least one product for report");
    }
    if (asins.length != totalAsins) {
        var minDate = Math.min(...timestamps);
        startDate = timestampMapping[minDate];
    } else {
        startDate = '';
        type = 'text';
    }
    isMultiple = true;

    openModal();
}

function openModal() {
    $('#technicalReport').modal('show');
}

function report(type) {
    type = !type ? 1 : type;
    var currentDate = $("#currentDate").val(),
        startDate = '',
        endDate = currentDate,
        timeSelect = $('input[name="date-range"]:checked').val();

    if(timeSelect) {
       if(timeSelect != 'custom') {
           startDate = timeSelect;
       } else {
           startDate = $('#custom-start-date').val();
           endDate = $('#custom-end-date').val();
       }
    }

    window.open(base_url + 'reports/export/' + type + '?startDate=' + startDate + '&endDate=' + endDate + '&asin=' + asins.join(','), '_blank');
}

function onSelectAll(){
    var i=0;
    var totalCount = 0;
    $("input[name='product-select[]']").each(function(){
        totalCount++
        if((this).checked) {
            i++;
        }
    });

    $("input[name='product-select[]']").each(function(){
        if(totalCount == i){
            $(this).prop('checked', false);
        } else {
            $(this).prop('checked', true);
        }
    });

}
// $('#custom-start-date').click(function(event){
    
//     //event.preventDefault();
//     alert(event);
//     //$('#custom-start-date').click();
// });
