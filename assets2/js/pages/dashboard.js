// var site_url = 'https://dev.trackasins.com/';
 var site_url = 'http://localhost/trackasins-master/';
// var site_url ='http://www.trackasins.loc/';
$(function() {
    var checkedArray;
    $(document).ready(function () {
        $('[data-fancybox="images"]').fancybox({});
        $("#profilePicture").change(function() {
            var fileSelect = document.getElementById("profilePicture");
            if (fileSelect.value != "") {
                var formData = new FormData();
                var file = fileSelect.files[0];
                formData.append('profile_picture_file', file);
                $.ajax({
                    type: 'POST',
                    url: site_url + "/settings/change_profile_picture",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        var obj = jQuery.parseJSON(data);
                        if (obj.code == 1) {
                            $("#profilePicHold").attr('src', obj.link);
                        } else {

                        }
                    }
                });
            }
        });
        $("input[name='checkbulk1[]']").on("click", function(){
            var i =0;
            checkedArray = new Array();
            $("input[name='checkbulk1[]']").each(function(){
                if((this).checked) {
                    checkedArray[i] = $(this).val();
                    i++;
                }
            });
            console.log(checkedArray.length);
            if(checkedArray.length >0){
                $("#bulkActionCar").addClass("car_select")
            } else {
                $("#bulkActionCar").removeClass("car_select")
            }
        });
        $("#asinsBulkActionButton").on("click", function () {

        });

        $("#asinsSearchButton").on("click", function(){
            var asin = $('#asinName').val();
            var url = asin;
            $('#confirmAsinDiv').hide();

            if(asin ==''){
                alert('Please enter ASIN or URL !');
                return false;
            }

            $('#loadingSpinner').show();
            //$("#asinsSubmitButton").click();
            
            var regex = RegExp("https://www.amazon.com/([\\w-]+/)?(dp|gp/product)/(\\w+/)?(\\w{10})");
            m = url.match(regex);
            //alert(m);
            if (m) { 
                //alert("ASIN=" + m[4]);
                asin = m[4];
            } else {
                asin = $('#asinName').val();
            }
            $.ajax({
                type: 'POST',
                url: site_url + "dashboard/dashboard_search",
                data: {asin: asin},
                success: function (response) {
                    
                    $('#confirmAsinDiv').html(response);
                    
                    $('#confirmAsinDiv').show();
                    $('#loadingSpinner').hide();
                    $('#asinName').val('');
                }
            });


            //$("#asinsSubmitButton").click();
            // $.ajax({
            //     type: 'POST',
            //     url: site_url + "/dashboard/check_expiration_date",
            //     data: {},
            //     success: function (response) {
            //         var obj = jQuery.parseJSON(response);
            //        if(obj.result == "success"){
            //            // $("#loadingSpinner").hide();
            //            // getAsinsResult(asin);
            //            $("#asinsSubmitButton").click();
            //        } else {
            //            // $("#loadingSpinner").hide();
            //            swal({
            //                    title: "",
            //                    text: obj.message,
            //                    type: "warning",
            //                    showCancelButton: true,
            //                    confirmButtonClass: "confirm-button-color",
            //                    confirmButtonText: "Upgrade",
            //                    cancelButtonText: "Not now",
            //                    closeOnConfirm: false,
            //                },
            //                function(isConfirm) {
            //                    if (isConfirm) {
            //                        window.location.href = site_url + "settings/membership_account";
            //                    }
            //                });
            //            // swal({
            //            //     title: 'Warning',
            //            //     text: obj.message,
            //            //     type: 'warning'
            //            // });
            //        }
            //     }
            // });
        });


	$('.bottomContent .asinInput input').keypress(function (e) {
            if(e.keyCode || e.which == 13) {
		e.preventDefault();
                var asin = $('#asinName').val();
                var url = asin;
                $('#confirmAsinDiv').hide();

                if(asin ==''){
                    alert('Please enter ASIN or URL !');
                    return false;
                }

                $('#loadingSpinner').show();
                //$("#asinsSubmitButton").click();
                
                var regex = RegExp("https://www.amazon.com/([\\w-]+/)?(dp|gp/product)/(\\w+/)?(\\w{10})");
                m = url.match(regex);
                //alert(m);
                if (m) { 
                    //alert("ASIN=" + m[4]);
                    asin = m[4];
                } else {
                    asin = $('#asinName').val();
                }
                $.ajax({
                    type: 'POST',
                    url: site_url + "dashboard/dashboard_search",
                    data: {asin: asin},
                    success: function (response) {
                        
                        $('#confirmAsinDiv').html(response);
                        
                        $('#confirmAsinDiv').show();
                        $('#loadingSpinner').hide();
                        $('#asinName').val('');
                    }
                });
            }
        });

        $("#deleteAsinsConfirmButton").click(function(){
            deleteAsinsConfirmButton();
        });
        dataTableShow();

        $(document).on("change", "input[name=ans]", function () {
            let val = $("input[name=ans]:checked").val();
            let yesDiv = $("#yes-submission");
            let noDiv = $("#no-submission");
            if (val === 'yes') {
                yesDiv.show();
                noDiv.hide();
            } else {
                yesDiv.hide();
                noDiv.show();
            }
        });

        let bulkMessage = $('input[name=bulk_upload_message]').val();
        let bulkMessageType = $('input[name=bulk_upload_message_type]').val();
        console.log(bulkMessageType);
        if (bulkMessage !== '') {
            swal({
                title: "",
                text: bulkMessage,
                type: bulkMessageType ? bulkMessageType : 'success',
                showCancelButton: false,
                confirmButtonClass: "confirm-button-color",
                confirmButtonText: "Ok",
                closeOnConfirm: false,
            },
            function(isConfirm) {
                if (isConfirm) {
                    window.location.href =site_url +'Dashboard';
                }
            });
        }
        $(document).on('change', '#bulk_upload_file', function (e) {
            var fileSelect = document.getElementById("bulk_upload_file");
            
            if (fileSelect.value != "") {
                var file = fileSelect.value.match(/\\([^\\]+)$/)[1];
                $('#asinName').val(file);
		$('#bulk_upload_button').removeAttr("disabled");
            }
        });
        $(document).on('click', '#bulk_upload_button', function (e) {
            if($('#asinName').val() ==''){
                alert('Please choose file!');
                return false;
            }
            $('#loadingSpinner').show();
            //if (e.target.files.length > 0) {

                var fileSelect = document.getElementById("bulk_upload_file");
            
            if (fileSelect.value != "") {
                var formData = new FormData();
                var file = fileSelect.files[0];
                formData.append('bulk_upload_file', file);
                $.ajax({
                    type: 'POST',
                    url: site_url + "dashboard/index",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        var obj = jQuery.parseJSON(data);
                if(obj.message_type == "success"){
                    // $(".mainTable").dataTable().fnDestroy()
                    // $("#dashboardTbody").empty();
                    // $("#dashboardTbody").append(obj.show_result);
                     //dataTableShow();
                    $.ajax({
                        type: 'POST',
                        url: site_url + "/dashboard/dashboard_dt",
                        data: {re: fileSelect.value},
                        success: function (response) {
                            $('#datata').empty();
                            $('#datata').html(response);
                            $('#asinName').val('');
                            //$('#confirmAsinDiv').show();
                            //$('#loadingSpinner').hide();
                        }
                    });
                    swal({
                        title: "",
                        text: obj.message,
                        type: "success",
                        showCancelButton: false,
                        confirmButtonClass: "confirm-button-color",
                        confirmButtonText: "Ok",
                        closeOnConfirm: true,
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            //window.location.href =site_url +'Dashboard';
                            
                        }
                    });
                    // swal({
                    //     title: 'Success',
                    //     text: "All updated successfully.",
                    //     type: 'success'
                    // });
                    // window.location.reload();
                    $('#loadingSpinner').hide();
                } else {
                    swal({
                        //                        title: 'Warning',
                        title: '',
                        text: obj.message,
                        type: 'warning',
                        confirmButtonClass: "confirm-button-color",
                        confirmButtonText: "Ok"
                    });
                    $('#loadingSpinner').hide();
                }
                    }
                });
            }
            
        //}
        });
        
    });


    // var table = $('.mainTable').DataTable();
    // $('#bookSearch').keyup(function () {
    //     table.search($(this).val()).draw();
    // })

    $(document).on('click', '.car', function () {
        if ($(this).hasClass('c')) {
            $(this).removeClass('c');
        } else {
            $(this).addClass('c');
        }
    });

    $(document).on('click', '.cb-label', function () {
        var t = $(this);
        var check = $("#" + t.data('for'));

        if (check.is(':checked')) {
            //$(".car").removeClass('c');
        } else {
            $(".car").addClass('c');
        }
    });

});

function clickFileUpload(){
    document.getElementById('bulk_upload_file').click()
    document.getElementById("bulk_upload_button").disabled = true;
}

function getAsinsResult(asin){
    alert(asin);
    $.ajax({
        type: 'POST',
        url: site_url + "/dashboard/getAsinsResult",
        data: {asin: asin},
        success: function (response) {

        }
    });

}

function dataTableShow(){
    $('.mainTable').DataTable({
	dom:"<'myfilter'f><'mylength'l>t",
	fixedHeader: {
            headerOffset: 90
        },
        responsive: true,
        stateSave: true,
        stateSaveCallback: function (settings, data) {
            localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data))
        },
        stateLoadCallback: function (settings) {
            return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance))
        },
        "paging": true,
        "pageLength": 50,
        "lengthMenu": [
            [10, 25, 50, 100, 250, 500, 1000, 2000, -1],
            [10, 25, 50, 100, 250, 500, 1000, 2000, "All"]
        ],
        "language": {
            "lengthMenu": "Show _MENU_ products"
        },
        /*"aoColumns": [
            null,
            null,
            null,
            null,
	    null,
            { "sSortDataType": "dom-checkbox" },
            { "sSortDataType": "dom-checkbox" },
            { "sSortDataType": "dom-checkbox" },
            { "sSortDataType": "dom-checkbox" },
            { "sSortDataType": "dom-checkbox" },
            //{ "sSortDataType": "dom-checkbox" },
            //{ "bSortable": false }
            //null
        ],*/
        //order: [[ 5, 'desc' ], [ 6, 'asc' ], [ 9, 'asc' ]],
	"order": [],
        /*'columnDefs': [{
              "targets": ['_all'],
              "orderable": false
          }],
	aoColumnDefs: [
		{ bSortable: false, aTargets: [ '_all' ] }
	],*/
	"aaSorting": [],
	"bSort": false,
	
    });
    // $('.menuListOpen').on("click", function(){
    
    //     alert('tttt');
    //     $('.menuListOpen').on('click');
    // });
    // $('.fa-caret-down').on("click", function(){
    
    //     alert('tttt')
    //     $('.menuListOpen').off('click');
    // });
    $.fn.dataTable.ext.order['dom-checkbox'] = function (settings, col) {
        return this.api().column(col, {order: 'index'}).nodes().map(function (td, i) {
            return $('input', td).prop('checked') ? '1' : '0';
        });
    }
    //$('table').removeClass('dataTable');
}

function onSelectAll(){
    var i=0;
    var totalCount = 0;
    $("input[name='checkbulk1[]']").each(function(){
        totalCount++
        if((this).checked) {
            i++;
        }
    });

    $("input[name='checkbulk1[]']").each(function(){
        if(totalCount == i){
            $(this).prop('checked', false);
        } else {
            $(this).prop('checked', true);
        }
    });

}
function onChangeTurnOnOff(type){
    var i =0;
    checkedArray = new Array();
    $("input[name='checkbulk1[]']").each(function(){
        if((this).checked) {
            checkedArray[i] = $(this).val();
            i++;
        }
    });
    
    if(checkedArray.length >0){
        $.ajax({
            type: 'POST',
            url: site_url + "/dashboard/change_bulk_notifications",
            data: { "list" : checkedArray, "type" : type},
            success: function (response) {
                var obj = jQuery.parseJSON(response);
                if(obj.result == "success"){
                    $(".mainTable").dataTable().fnDestroy()
                    $("#dashboardTbody").empty();
                    $("#dashboardTbody").append(obj.show_result);
                    dataTableShow();
                    swal({
                        title: "",
                        text: "All updated successfully.",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonClass: "confirm-button-color",
                        confirmButtonText: "Ok",
                        closeOnConfirm: true,
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            //window.location.href =site_url +'Dashboard';
                        }
                    });
                    // swal({
                    //     title: 'Success',
                    //     text: "All updated successfully.",
                    //     type: 'success'
                    // });
                    // window.location.reload();
                } else {
                    swal({
                        //                        title: 'Warning',
                        title: '',
                        text: obj.message,
                        type: 'warning',
                        confirmButtonClass: "confirm-button-color",
                        confirmButtonText: "Ok"
                    });
                }
            }
        });
    } else {
        swal({
            //                        title: 'Warning',
            title: '',
            text: "Please select any one item.",
            type: 'warning',
            confirmButtonClass: "confirm-button-color",
            confirmButtonText: "Ok"

        });
    }
}

function  deleteAsinsConfirmButton() {
    $("#deleteAsinsModal").modal('hide');
    var i =0;
    checkedArray = new Array();
    $("input[name='checkbulk1[]']").each(function(){
        if((this).checked) {
            checkedArray[i] = $(this).val();
            i++;
        }
    });

    if(checkedArray.length >0){
        $("#loadingSpinner").show();
        $.ajax({
            type: 'POST',
            url: site_url + "/dashboard/delete_bulk_asins",
            data: { "list" : checkedArray},
            success: function (response) {
                var obj = jQuery.parseJSON(response);
                if(obj.result == "success"){
                    $(".mainTable").dataTable().fnDestroy()
                    $("#dashboardTbody").empty();
                    $("#dashboardTbody").append(obj.show_result);
                    dataTableShow();
                    // swal({
                    //     title: 'Success',
                    //     text: "Deleted successfully.",
                    //     type: 'success'
                    // });
                    $("#loadingSpinner").hide();
                    swal({
                            title: "",
                            text: "Deleted successfully.",
                            type: "success",
                            showCancelButton: false,
                            confirmButtonClass: "confirm-button-color",
                            confirmButtonText: "Ok",
                            closeOnConfirm: false,
                        },
                        function(isConfirm) {
                            if (isConfirm) {
                                window.location.href =site_url +'Dashboard';
                            }
                        });

                } else {
                    $("#loadingSpinner").hide();
                    swal({
                        title: '',
                        text: obj.message,
                        type: 'warning',
                        confirmButtonClass: "confirm-button-color",
                        confirmButtonText: "Ok"
                    });
                }
            }
        });
    } else {
        swal({
            //                        title: 'Warning',
            title: '',
            text: "Please select any one item.",
            type: 'warning',
            confirmButtonClass: "confirm-button-color",
            confirmButtonText: "Ok"
        });
    }
}

var st_counter = 0;
var st_switchStatus;
function chackUncheck(userIp, el) {
    var $el = $(el);
    var url_link;
    st_counter ++;
    if (st_counter <= 10){
        st_switchStatus = document.getElementById('switch' + userIp).checked;
        if (st_switchStatus) {
            url_link =site_url +  'Dashboard/checkAndUncheck/' + userIp + '/1';
            document.getElementById('amznotseller_label_'+userIp).innerText = "Yes";
        } else {
            url_link = site_url + 'Dashboard/checkAndUncheck/' + userIp + '/0';
            document.getElementById('amznotseller_label_'+userIp).innerText = "No";
        }
        $.ajax({
            url: url_link,
            success: function (res) {
                var obj = jQuery.parseJSON(res);
                if(obj.result == "success"){
                    $el.closest('tr.scrape-row').html(obj.show_result);
                    $('#stockNotificationDiv').html(obj.count);
                } else {
                    document.getElementById('switch' + userIp).checked = !st_switchStatus;
                    swal({
                        title: "",
                        text: obj.message,
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "confirm-button-color",
                        confirmButtonText: "Upgrade",
                        cancelButtonText: "Not now",
                        closeOnConfirm: false,
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            window.location.href = site_url + "settings/membership_account";
                        }
                    });
                }
                /*document.getElementById('shows').style.display='none'; */
            }
        })
    } else {
        $('#switch' + userIp).attr("checked", false);

        // $('#switchstock' + stockIp).attr("checked", switchStatus);
    }
    console.log(st_switchStatus)
}

var counter = 0;
var switchStatus;
function stockcheck(stockIp, el) {
    counter ++;
    var $el = $(el);
    var url_link;
    
    if (counter <= 10 ) {
        switchStatus = document.getElementById('switchstock' + stockIp).checked;
        if (switchStatus) {
            document.getElementById('stock_label_'+stockIp).innerText = "Yes";
        } else {
            document.getElementById('stock_label_'+stockIp).innerText = "No";
        }
        var chck = document.getElementById('switchstock' + stockIp).checked;
        url_link = site_url+'Dashboard/stockinsert/' + stockIp + '/' + chck;
        $.ajax({
            url: url_link,
            success: function (res3) {
                var obj = jQuery.parseJSON(res3);
                if(obj.result == "success"){
                    $el.closest('tr.scrape-row').html(obj.show_result);
                    $('#backStockNotificationsDiv').html(obj.count);
                }else {
                    document.getElementById('switchstock' + stockIp).checked = !switchStatus;
                    swal({
                        title: "",
                        text: obj.message,
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "confirm-button-color",
                        confirmButtonText: "Upgrade",
                        cancelButtonText: "Not now",
                        closeOnConfirm: false,
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            window.location.href = site_url + "settings/membership_account";
                        }
                    });
                }
    
            }
        })
    } else {
        $('#switchstock' + stockIp).attr("checked", switchStatus);
    }
}

var em_counter = 0;
var chck;
function emailcheck(emailget) {
    var url_link;
    em_counter ++;
    if (em_counter <= 10) {
        chck = document.getElementById('switchid' + emailget).checked;
        url_link = site_url+'Dashboard/emailinsert/' + emailget + '/' + chck;
        $.ajax({
            url: url_link,
            success: function (res1) {
                console.log(res1);
    
            }
        })
    } else {
        $('#switchid' + emailget).attr("checked", false);
    }
    
}

function phonecheck(phoneip) {
    console.log(phoneip);
    var url_link;
    var chck = document.getElementById('switchphone' + phoneip).checked;
    url_link = site_url+'Dashboard/phoneinsert/' + phoneip + '/' + chck;
    //console.log(url_link);
    $.ajax({
        url: url_link,
        success: function (res2) {
            console.log(res2);
            /*document.getElementById('shows').style.display='none'; */
        }
    })
}

function show() {
    if (document.getElementById('shows').style.display == 'none') {
        document.getElementById('shows').style.display = 'block';
    }
}
function dele_show() {
    document.getElementById('shows_delete').style.display = 'block';
}

function saveTodatabase(requiresRescrape = false) {

    $('#confirmAsinDiv').hide();
    var user_id = document.getElementById('user_id_1').value;
    //var id = document.getElementById('id_1').value;
    var img = document.getElementById('img_1').value;
    var title_name = document.getElementById('title_name_1').value;
    var asin = document.getElementById('asin_1').value;
    var amznotseller = document.getElementById('amznotseller_1').value;
    /*var stock_url     = document.getElementById('stock_url_1').value;*/
    var sellerstock = document.getElementById('sellerstock_1').value;
    var amazonstock = document.getElementById('amazonstock_1').value;
    var rating = document.getElementById('rating_1').value;
    var reviews = document.getElementById('reviews_1').value;
    var seller_name = document.getElementById('seller_name_1').value;
    var seller_url = document.getElementById('seller_url_1').value;
    var seller_ids = document.getElementById('seller_ids_1').value;
    var price = document.getElementById('price_1').value;
    var shipping = document.getElementById('shipping_1').value;

    /*console.log( img);*/

    var url_link = site_url+'Dashboard/SaveToDB/';
    requiresRescrape = requiresRescrape ? 1 : 0;
    $.ajax({
        type: "POST",
        url: url_link,
        data: "user_id=" + user_id + "&img=" + img + "&title_name=" + title_name + "&asin=" + asin + "&amznotseller=" + amznotseller + "&sellerstock=" + sellerstock + "&amazonstock=" + amazonstock + "&rating=" + rating + "&reviews=" + reviews + "&seller_name=" + seller_name + "&seller_url=" + seller_url + "&seller_ids=" + seller_ids + "&price=" + price + "&shipping=" + shipping + "&requires_rescrape=" + requiresRescrape,
        success: function (msg) {
            var f_data = jQuery.parseJSON(msg);
            if(f_data.result =='success' ){
                //$(".mainTable").dataTable().fnDestroy()
                //$("#dashboardTbody").empty();
                //$("#dashboardTbody").append(f_data.show_result);
                $.ajax({
                    type: 'POST',
                    url: site_url + "/dashboard/dashboard_dt",
                    data: {re: f_data.result},
                    success: function (response) {
                        $('#datata').empty();
                        $('#datata').html(response);
                        //$('#confirmAsinDiv').show();
                        //$('#loadingSpinner').hide();
                    }
                });
                swal({
                    title: "",
                    text: f_data.message,
                    type: "success",
                    showCancelButton: false,
                    confirmButtonClass: "confirm-button-color",
                    confirmButtonText: "Ok",
                    closeOnConfirm: true,
                },
                function(isConfirm) {
                    if (isConfirm) {
			    //alert("confirm==");
                        window.location.href =site_url +'Dashboard';
                    }
                });
                // swal({
                //     title: 'Success',
                //     text: f_data.message,
                //     type: 'success'
                // });

            } else {
                
                
                // swal({
                //     title: 'Warning',
                //     text: f_data.message,
                //     type: 'warning'
                // });
                // window.location.href =site_url +'Dashboard';
                swal({
                    title: "",
                    text: f_data.message,
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonClass: "confirm-button-color",
                    confirmButtonText: "Ok",
                    closeOnConfirm: true,
                },
                function(isConfirm) {
                    if (isConfirm) {
                        //window.location.href =site_url +'Dashboard';
                    }
                });
            }
            // var finalData = f_data.replace(/\\/g, "");
            // $('#DataTables_Table_0 > tbody:last-child').append(finalData);
        }
    });
    $("#asinName").val('');
    document.getElementById('shows').style.display = 'none';

}

function clearConfirmAsinDiv() {
    $("#confirmAsinDiv").hide();
}

$(document).ready(function() {
    var holder = document.getElementById('asinName');
    holder.ondragover = holder.ondragenter = function(evt) {
      evt.preventDefault();
    };

    holder.ondrop = function(evt) {
        
      // pretty simple -- but not for IE :(
      bulk_upload_file.files = evt.dataTransfer.files;
      evt.preventDefault();
      $("#bulk_upload_file").trigger("change");
      
    };
});
