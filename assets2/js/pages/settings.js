$(document).ready(function(){
    var site_url = 'http://localhost/trackasins-master/';
    //var site_url = 'https://dev.trackasins.com/';
    var work = false;
    var i = 1;
    
    var checked = $("#email_notifications").data('checked');

    /*
     * This will open up the "delete account" modal
     */
    $(document).on('click', '.closeOpenModal', function(){

    });

    function showButton(){
        $(".mainSub").fadeIn('fast');
    }

    $(document).on('click', '.cc', function(){
        if($(".mainSub").css('display') == "none")
        {
            showButton();
        }
    });

    $(document).on('click', '.cc2', function(){
        if($(".mainSub").css('display') == "none")
        {
            showButton();
        }
    });
    
    $(document).on('click', '#removePP', function(e){
    	e.preventDefault();
    	
    	var confirm2 = confirm('Are you sure you want to remove your profile picture?');
    	
    	if(confirm2 == true){
			$.post('settings/remove_profile_picture',{run: 1}, function(data){
				if(data != ""){
					var obj = jQuery.parseJSON(data);
				
					if(obj.code == 1)
					{
						$("#profilePicHold").attr('src', obj.link);
                        $("#removePP").hide();
					}else{
						alert(obj.status);
					}
				}
			});
    	}else{
    		return false;
    	}
    	return false;
    });

    /*
     * This will work for both select all buttons
     */
    $(document).on('click', '.checkboxall1', function(){
        var t = $(this);
        var check = $("#" + t.data('for')).data('c');

        if(check == "no")
        {
            $(".cc").each(function(){
                var cc = $(this).data('for');

                $("#" + cc).prop( "checked", true );
            });
            $("#" + t.data('for')).data('c', 'yes')
        }else{
            $(".cc").each(function(){
                var cc = $(this).data('for');

                $("#" + cc).prop( "checked", false );
            });
            $("#" + t.data('for')).data('c', 'no')
        }
    });

    $(document).on('click', '.checkboxall2', function(){
        var t2 = $(this);
        var check = $("#" + t2.data('for')).data('c');

        if(check == "no")
        {
            $(".cc2").each(function(){
                var cc = $(this).data('for');

                $("#" + cc).prop( "checked", true );
            });
            $("#" + t2.data('for')).data('c', 'yes')
        }else{
            $(".cc2").each(function(){
                var cc = $(this).data('for');

                $("#" + cc).prop( "checked", false );
            });
            $("#" + t2.data('for')).data('c', 'no')
        }
    });

    $('.mainTable').DataTable({
        stateSave: true,
        stateSaveCallback: function(settings,data) {
            localStorage.setItem( 'DataTables_' + settings.sInstance, JSON.stringify(data) )
        },
        stateLoadCallback: function(settings) {
            return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
        },
        "language": {
            "lengthMenu": "Show _MENU_ products"
        }
    });

    /*
     * For changing the profile pictures
     */
    $("#profilePicSelect").change(function(){
        var btnLoading = $("#profileLoading");

        var fileSelect = document.getElementById("profilePicSelect");
        if(fileSelect.value !=""){
            var formData = new FormData();
            var file = fileSelect.files[0];
            formData.append('profile_picture_file', file);
            btnLoading.val("Loading...");
            // Now for the main stuff
            $.ajax({
                type:'POST',
                url: site_url + "/settings/change_profile_picture",
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                success:function(data){
			alert(data);
                    var obj = jQuery.parseJSON(data);

                    if(obj.code == 1)
                    {
                        btnLoading.val("Change Profile Picture");

                        // Load the new image into the image container
                        $("#profilePicHold").attr('src', obj.link);

                        // Response
                        $(".profilePictureHolder").prepend("<div class='clearfix alert alert-success'>" + obj.string + "</div>");
                        fileSelect.value = "";

                        work = false
                    }else{
                        btnLoading.val("Change Profile Picture");

                        $(".profilePictureHolder").prepend("<div class='clearfix alert alert-success'>" + obj.string + "</div>");
                        fileSelect.value = "";

                        work = false
                    }
                    $("#removePP").show();
                    removeAlert("profile_image");
                },
                error: function(data){
			//alert(data);
                    $(".profilePictureHolder").prepend("<div class='clearfix alert alert-danger'>OOPS! An error has happened. Error: "+data+"</div>");
                    fileSelect.value = "";

                    work = false;
                    removeAlert("profile_image");
                }
            });
        } else {
            return;
        }
    });
    // $(document).on('submit','#settings_profile_pic_form', function() {
    //     if(work == false)
    //     {
    //         work = true;
    //
    //         // Variables
    //         var form = $(this);
    //         var fileSelect = document.getElementById("profilePicSelect");
    //         var btnLoading = $("#profileLoading");
    //
    //         var salt = $("#x");
    //
    //         // Check to make sure we have a file
    //         if(fileSelect.value != "")
    //         {
    //             // Make the button have a loading thing
    //             btnLoading.val("Loading...");
    //
    //             // Now instantiate the form data vars
    //             var formData = new FormData();
    //             var file = fileSelect.files[0];
    //
    //             // Append form data
    //             formData.append('trackasins_csrf', $("input[name='trackasins_csrf']").val());
    //             formData.append('profile_picture_file', file);
    //
    //             // Now for the main stuff
    //             $.ajax({
    //                 type:'POST',
    //                 url: site_url + "/settings/change_profile_picture",
    //                 data:formData,
    //                 cache:false,
    //                 contentType: false,
    //                 processData: false,
    //                 success:function(data){
    //                     var obj = jQuery.parseJSON(data);
    //
    //                     if(obj.code == 1)
    //                     {
    //                         btnLoading.val("Change Profile Picture");
    //
    //                         // Load the new image into the image container
    //                         $("#profilePicHold").attr('src', obj.link);
    //
    //                         // Response
    //                         $(".profilePictureHolder").prepend("<div class='clearfix alert alert-success'>" + obj.string + "</div>");
    //                         fileSelect.value = "";
    //
    //                         work = false
    //                     }else{
    //                         btnLoading.val("Change Profile Picture");
    //
    //                         $(".profilePictureHolder").prepend("<div class='clearfix alert alert-success'>" + obj.string + "</div>");
    //                         fileSelect.value = "";
    //
    //                         work = false
    //                     }
    //                     $("#removePP").show();
    //                     removeAlert("profile_image");
    //                 },
    //                 error: function(data){
    //                     $(".profilePictureHolder").prepend("<div class='clearfix alert alert-danger'>OOPS! An error has happened</div>");
    //                     fileSelect.value = "";
    //
    //                     work = false;
    //                     removeAlert("profile_image");
    //                 }
    //             });
    //         }else{
    //             work = false;
    //         }
    //     }
    //     return false;
    // });

    function removeAlert(tab){
        var counter = 0;
        var interval = setInterval(function() {
            counter++;
            // Display 'counter' wherever you want to display it.
            if (counter == 5) {
                // Display a login box
                if(tab =="profile_image"){
                    $(".profilePictureHolder").find('.alert-success').remove();
                    $(".profilePictureHolder").find('.alert-danger').remove();
                } else if(tab == "basic_info"){
                    $(".basicInfoCont").find('.alert-success').remove();
                    $(".basicInfoCont").find('.alert-danger').remove();
                } else if(tab == "security_setting"){
                    $('.changeLoginEmaileHolder').find('.alert-success').remove();
                    $('.changeLoginEmaileHolder').find('.alert-danger').remove();
                } else if(tab == "password_holder" || tab == "amazon_api"){
                    $('.changePasswordHolder').find('.alert-success').remove();
                    $('.changePasswordHolder').find('.alert-danger').remove();
                }

                clearInterval(interval);
            }
        }, 1000);
    }

    /*
     * For changing basic info
     */
    $(document).on('submit', '#settings_change_basic_information', function(){
        
       
           
           
           // Variables
           var firstname = $("#sl_settings_firstname");
           var email = $("#sl_settings_email");
           var company_name = $("#sl_settings_company_name");
           var seller_id = $("#sl_settings_seller_id");
           var phone_number = $("#sl_settings_phone_number");

           // Now make sure these fields arent empty
           if(firstname.val() == "")
           {
               firstname.addClass('input-danger');
               return false;
           }else{
               firstname.removeClass('input-danger');
           }

           

           if(email.val() == "")
           {
               email.addClass('input-danger');
               return false;
           }else{
               email.removeClass('input-danger');
           }

           if(company_name.val() == "")
           {
               company_name.addClass('input-danger');
               return false;
           }else{
               company_name.removeClass('input-danger');
           }

           if(seller_id.val() == "")
           {
               seller_id.addClass('input-danger');
               return false;
           }else{
               seller_id.removeClass('input-danger');
           }

           if(phone_number.val() == "")
           {
               phone_number.addClass('input-danger');
               return false;
           }else{
               phone_number.removeClass('input-danger');
           }

           if(phone_number.val().length < 10){
                phone_number.addClass('input-danger');
                //alert(phone_number.val().length);
                return false;
            }else{
                phone_number.removeClass('input-danger');
            }   


           // Now do stuffy stuff
           if(firstname.val() != ""  && email.val() != "" && company_name.val() != "" && seller_id.val() != "" && phone_number.val() != "")
           {
               $.post(site_url + '/settings/change_basic_information', {firstname: firstname.val(), email: email.val(), company_name: company_name.val(), seller_id: seller_id.val(), phone_number: phone_number.val()}, function(data){
                   var obj = jQuery.parseJSON(data);

                   if(obj.code == 1)
                   {
                    var body = $("html, body");
                    body.stop().animate({scrollTop:0}, 500, 'swing', function() { 
                    });
                       $(".basicInfoCont").prepend("<div class='clearfix alert alert-success'>" + obj.string + "</div>");
                       
                       removeAlert("basic_info");
                       return false;
                   }else{
                    var body = $("html, body");
                    body.stop().animate({scrollTop:0}, 500, 'swing', function() { 
                    });
                       $(".basicInfoCont").prepend("<div class='clearfix alert alert-danger'>" + obj.string + "</div>");
                       
                       removeAlert("basic_info");
                       return false;
                   }
               });
           }else{
            return false;
           }
       
       $('#loadingSpinner').hide();
        return false;
    });

    /*
     * For changing amazon api settings
     */
    $(document).on('submit', '#settings_amazon_api', function(){
        if(work == false)
        {
            work = true;

            // Variables
            var api_connection = $("#amazon_connection");
            var api_connection_value = "off";
            var seller_id = $("#sl_settings_seller_id");
            var marketplace_id = $("#sl_settings_marketplace_id");
            var associate_tag = $("#sl_settings_associate_tag");
            var dev_account_number = $("#sl_settings_developer_account_number");
            var access_key_id = $("#sl_settings_access_key_id");
            var secret_key = $("#sl_settings_secret_key");

            // Validate
            // if(api_connection.data('checked') == "")
            if(api_connection.prop("checked") == false)
            {
                api_connection_value ="off";
                api_connection.addClass('input-danger');
                work = false
            }else{
                api_connection_value ="on";
                api_connection.removeClass('input-danger');
            }


            if(seller_id.val() == "")
            {
                seller_id.addClass('input-danger');
                work = false
            }else{
                seller_id.removeClass('input-danger');
            }

            if(marketplace_id.val() == "")
            {
                marketplace_id.addClass('input-danger');
                work = false
            }else{
                marketplace_id.removeClass('input-danger');
            }

            if(associate_tag.val() == "")
            {
                associate_tag.addClass('input-danger');
                work = false
            }else{
                associate_tag.removeClass('input-danger');
            }

            if(dev_account_number.val() == "")
            {
                dev_account_number.addClass('input-danger');
                work = false
            }else{
                dev_account_number.removeClass('input-danger');
            }

            if(access_key_id.val() == "")
            {
                access_key_id.addClass('input-danger');
                work = false
            }else{
                access_key_id.removeClass('input-danger');
            }

            if(secret_key.val() == "")
            {
                secret_key.addClass('input-danger');
                work = false
            }else{
                secret_key.removeClass('input-danger');
            }


            if(api_connection_value != "" && seller_id.val() != "" && marketplace_id.val() != "" && associate_tag.val() != "" && dev_account_number.val() != "" && access_key_id.val() != "" && secret_key.val() != "")
            {
                $.post(site_url + 'settings/amazon_api_update', {api_connection: api_connection_value, seller_id: seller_id.val(), marketplace_id: marketplace_id.val(), associate_tag: associate_tag.val(), dev_account_number: dev_account_number.val(), access_key_id: access_key_id.val(), secret_key: secret_key.val()}, function(data){
                    var obj = jQuery.parseJSON(data);

                    if(obj.code == 1)
                    {
                        $(".changePasswordHolder").prepend("<div class='clearfix alert alert-success'>" + obj.string + "</div>");
                        work = false;
                        removeAlert("amazon_api");
                    }else{
                        $(".changePasswordHolder").prepend("<div class='clearfix alert alert-danger'>" + obj.string + "</div>");
                        work = false;
                        removeAlert("amazon_api");
                    }

                });
            }else{
                work = false;
            }
        }
        return false;
    });

    /*
     * Changing Passwords
     */
    $(document).on('submit' , '#settings_change_password_form', function(){
         
             

             // Variables
             var current_password = $("#sl_settings_current_password");
             var new_password = $("#sl_settings_new_password");
             var confirm_new_password = $("#sl_settings_confirm_new_password");

             // Now make sure every field has a value
             if(current_password.val() == "")
             {
                 current_password.addClass('input-danger');
                 return false;
             }else{
                 current_password.removeClass('input-danger');
             }

             if(new_password.val() == "")
             {
                 new_password.addClass('input-danger');
                 return false;
             }else{
                 new_password.removeClass('input-danger');
             }

             if(confirm_new_password.val() == "")
             {
                 confirm_new_password.addClass('input-danger');
                 return false;
             }else{
                 confirm_new_password.removeClass('input-danger');
             }

             if(current_password.val() != "" && new_password.val() != "" && confirm_new_password.val() != "")
             {
                 // Now make the ajax call
                 $.post(site_url + 'settings/changePasswordProcess', {current_password: current_password.val(), new_password: new_password.val(), confirm_new_password: confirm_new_password.val()}, function(data){
                     var obj = jQuery.parseJSON(data);

                     if(obj.code == 1)
                     {
                         $(".changePasswordHolder").prepend("<div class='clearfix alert alert-success'>" + obj.string + "</div>");
                         
                         removeAlert("password_holder");
                         return false;
                     }else{
                         $(".changePasswordHolder").prepend("<div class='clearfix alert alert-danger'>" + obj.string + "</div>");
                         
                         removeAlert("password_holder");
                         return false;
                     }
                     $("#sl_settings_current_password").val("");
                     $("#sl_settings_new_password").val("");
                     $("#sl_settings_confirm_new_password").val("");
                 });
             }else{
                return false;
             }
         
        return false;
    });

    /*
     * Changing security settings
     */
    $(document).on('submit', '#settings_security_settings_form', function(){
       if(work == false)
       {
           var current_email = $("#sl_current_login_email");
           var new_email = $("#sl_new_login_email");
           var confirm_new_email = $("#sl_confirm_new_login_email");
            // current email address check
           if(current_email.val() == "")
           {
               current_email.addClass('input-danger');
               work = false
           }else{
               current_email.removeClass('input-danger');
           }
            // new email address check

           if(new_email.val() == "")
           {
               new_email.addClass('input-danger');
               work = false
           }else{
               new_email.removeClass('input-danger');
           }
        // confirm new email
           if(confirm_new_email.val() == "")
           {
               confirm_new_email.addClass('input-danger');
               work = false
           }else{
               confirm_new_email.removeClass('input-danger');
           }

           if(current_email.val() != "" && new_email.val() != "" && confirm_new_email.val() != "")
           {
               $.post(site_url + "/settings/change_security_settings", {current_email: current_email.val(), new_email: new_email.val(), confirm_new_email: confirm_new_email.val()}, function(data){
                   var obj = jQuery.parseJSON(data);

                   if(obj.code == 1)
                   {
                       $(".changeLoginEmaileHolder").prepend("<div class='clearfix alert alert-success'>" + obj.string + "</div>");
                       $("#sl_current_login_email").val("");
                       $("#sl_new_login_email").val("");
                       $("#sl_confirm_new_login_email").val("");
                       work = false;
                       removeAlert("security_setting");

                   }else{
                       $(".changeLoginEmaileHolder").prepend("<div class='clearfix alert alert-danger'>" + obj.string + "</div>");
                       work = false;
                       removeAlert("security_setting");
                   }

               });
           }else{
               work = false;
           }
       }
        return false;
    });
    
    /*
     * Change notification settings  
     */
    $(document).on('submit', '#settings_notifications_form', function(){
       
          
           // Variables
           var enable_notifications = $(".email_notifications");
           var phone_number = $("#sl_settings_phone_number");
           var email = $("#sl_settings_email");
           var timezone = $("#sl_settings_timezone");

           if(phone_number.val() == "")
           {
               phone_number.addClass('input-danger');
               return false;
           }else{
               phone_number.removeClass('input-danger');
           }

           if(email.val() == "")
           {
               email.addClass('input-danger');
               return false;
           }else{
               email.removeClass('input-danger');
           }

           var validEmail = validateEmail(email.val());

           var c = enable_notifications.is(':checked');
           
           
           if(email.val() != "" && phone_number.val() != ""  && timezone.val() != "" && validEmail)
           {
               $.post(site_url + "settings/change_notification_settings", {enable_notifications: c, email: email.val(), phone_number: phone_number.val(), timezone: timezone.val()}, function(data){
                   var obj = jQuery.parseJSON(data);
                   $(".success").each(function(){
                       $(this).remove();
                   });

                   $(".error").each(function(){
                       $(this).remove();
                   });

                   if(obj.code == 1)
                   {
                       // $(".changePasswordHolder").prepend("<div class='clearfix alert alert-success'>" + obj.string + "</div>");
                       work = false;
                       swal({
                           title: "Success",
                           text: obj.string,
                           type: "success",
                           showCancelButton: false,
                           confirmButtonClass: "confirm-button-color",
                           confirmButtonText: "OK",
                           closeOnConfirm: false,
                       },
                       function(isConfirm) {
                           if (isConfirm) {
                               window.location.reload();
                           }
                       });
                       // swal({
                       //     title: 'Success',
                       //     text: obj.string,
                       //     type: 'success'
                       // });
                       // window.location.reload();
                   }else{
                       swal({
                           //                        title: 'Warning',
                           title: '',
                           text: obj.string,
                           type: 'warning',
                           confirmButtonClass: "confirm-button-color",
                           confirmButtonText: "Ok"
                       });
                       // $(".changePasswordHolder").prepend("<div class='clearfix alert alert-danger'>" + obj.string + "</div>");
                       return false;
                   }

                   setTimeout(function(){
                       $(".success").each(function(){
                           $(this).remove();
                       });

                       $(".error").each(function(){
                           $(this).remove();
                       });
                   }, 5000);
               });
           }
       
        return false;
    });

    /*
     * Upgrade plan
     */
    $(document).on('submit', '#settings_upgrade_plan', function(){
        if(work == false)
        {
            work = true;

            // Variables
            var plan_select = $('input:radio[name=planUpdate]:checked');

            if(plan_select.val() == "")
            {
                plan_select.addClass('input-danger');
                work = false
            }else{
                plan_select.removeClass('input-danger');
            }

            if(plan_select.val() != "")
            {
                $.post(site_url + "settings/upgrade_plan_process", {plan_select: plan_select.val()}, function(data){
                    var obj = jQuery.parseJSON(data);

                    if(obj.code == 1)
                    {
                        $(".changePasswordHolder").prepend("<div class='clearfix alert alert-success'>" + obj.string + "</div>");
                        work = false;
                    }else{
                        $(".changePasswordHolder").prepend("<div class='clearfix alert alert-danger'>" + obj.string + "</div>");
                        work = false;
                    }
                });
            }
        }
        return false;
    });

    /*
     *Check bixes
    */
    $(document).on('click', '.checkb2', function(){
        var checkbox2 = $("#amazon_connection");

        if(checkbox2.data('checked') == 'off')
        {
            checkbox2.data('checked', 'on');
        }else if(checkbox2.data('checked') == 'on') {
            checkbox2.data('checked', 'off');
        }
    });

    $(document).on('click', '.checkb', function(){
        var checkbox = $("#email_notifications");

        if(checkbox.data('checked') == 'no')
        {
            checkbox.data('checked', 'yes');
        }else {
            checkbox.data('checked', 'no');
        }
    });

    /*
     * When someone clicks the box
     */
    $(document).on('click', '.checkbox', function(){
        var name = $(this).data('box');

        if(name != ""){
            // Reset
            $(".innerBox").css('border', '1px solid rgba(0, 0, 0, 0.18)');
            $(".innerBox").css('box-shadow', 'none');

            // New stuff
            $("." + name).css('border', '1px solid rgba(182, 95, 43, 0.48)');
            $("." + name).css('box-shadow', '0 1px 4px rgba(182, 95, 43, 0.5), 0 1px 3px rgba(182, 95, 43, 0.58)');
        }
    });


});

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}
