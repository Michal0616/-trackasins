$(function() {
	$('dd').filter(':nth-child(n+4)').addClass('hide2');
	$('dl').on('click', 'dt', function() {
		$(this)
			.next()
				.slideDown(300)
				.siblings('dd')
					.slideUp(300);
	});
});


$(document).ready(function() {
    $('.mainTable').DataTable({
        responsive: true,
        stateSave: true,

        stateSaveCallback: function(settings,data) {
            localStorage.setItem( 'DataTables_' + settings.sInstance, JSON.stringify(data) )
        },
        stateLoadCallback: function(settings) {
            return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
        },
        "paging": true,
        "lengthMenu": [
            [10, 25, 50, 100, 250, 500, 1000, 2000, -1],
            [10, 25, 50, 100, 250, 500, 1000, 2000, "All"]
        ],
        "language": {
            "lengthMenu": "Show _MENU_ products"
        }
    });

    $(document).on('click', '.car', function(){
        if($(this).hasClass('c'))
        {
            $(this).removeClass('c');
        }else{
            $(this).addClass('c');
        }
    });

    $(document).on('click', '.cb-label', function(){
        var t = $(this);
        var check = $("#" + t.data('for'));

        if(check.is(':checked'))
        {
            //$(".car").removeClass('c');
        }else{
            $(".car").addClass('c');
        }
    });
});
