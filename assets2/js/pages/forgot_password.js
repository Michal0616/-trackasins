$(function(){
    var work = false;
    //var site_url = 'https://dev.trackasins.com/';
    var site_url = 'http://localhost/trackasins/';
    function removeAlerts(){
        $(".error").css('display', 'none').remove();
        $(".success").css('display', 'none').remove();
        $(".warning").css('display', 'none').remove();
    }

    // For the main stuff
    $(document).on('submit', '#forgot_password_form', function(){
        $("#loadingSpinner").show();
        if(work == false)
        {
            work = true;

            removeAlerts();

            var username_or_email = $("#sl_user_or_email");

            if (username_or_email.val() == "")
            {
                username_or_email.addClass('input-danger');
                work = false;
            }

            if(username_or_email != "")
            {
                $.post(site_url + 'forgot_password/requestPasswordReset', {username_or_email: username_or_email.val()}, function(data){
                    $("#sl_user_or_email").val('');
                    $("#loadingSpinner").hide();
                    var obj = jQuery.parseJSON(data);

                    if(obj.code == 1)
                    {
                        $(".ForgotPassSec").prepend("<div class='clearfix success'>" + obj.string + "</div>");
                        setTimeout(function(){
                            window.location.assign(site_url);
                        }, 3000);
                        work = false;
                    }else
                    {
                        $(".ForgotPassSec").prepend("<div class='clearfix error'>" + obj.string + "</div>");
                        work = false;
                    }
                });
            }else{
                work = false;
            }
        }
        return false;
    });

    // Changing passwords
    $(document).on('submit', '#change_password_form', function(){
        $("#loadingSpinner").show();
        if(work == false)
        {
            work = true;

            removeAlerts();

            var password = $("#sl_password");
            var confirm_password = $("#sl_password_confirm");
            var request_id = $("#request_id");
            var unique_id = $("#unique_id");

            if (password.val() == "")
            {
                password.addClass('input-danger');
                work = false;
            }

            if (confirm_password.val() == "")
            {
                confirm_password.addClass('input-danger');
                work = false;
            }

            if(password != "" && confirm_password != "")
            {
                $.post(site_url + 'forgot_password/changePassProcess', {request_id: request_id.val(), unique_id: unique_id.val(), password: password.val(), confirm_password: confirm_password.val()}, function(data){
                    $("#loadingSpinner").hide();
                    var obj = jQuery.parseJSON(data);

                    if(obj.code == 1)
                    {
                        setTimeout(function(){
                            window.location.assign(site_url);
                        }, 3000);
                        $(".ForgotPassSec").prepend("<div class='clearfix success'>" + obj.string + "</div>");
                        work = false;
                    }else
                    {
                        $(".ForgotPassSec").prepend("<div class='clearfix error'>" + obj.string + "</div>");
                        work = false;
                    }
                });
            }else{
                work = false;
            }
        }
        return false;
    });
});