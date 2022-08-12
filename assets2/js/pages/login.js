$(function(){
    var work = false;
    var site_url = 'http://localhost/sitelyftstudios/';

    function removeAlerts(){
        $(".error").css('display', 'none').remove();
        $(".success").css('display', 'none').remove();
        $(".warning").css('display', 'none').remove();
    }

    // For the main stuff
    $(document).on('submit', '#loginMainForm', function(){
        if(work == false)
        {
            work = true;

            removeAlerts();

            var email = $("#sl_email");
            var password = $("#sl_password");

            if (email.val() == "")
            {
                email.addClass('input-danger');
                work = false;
            }

            if (password.val() == "")
            {
                password.addClass('input-danger');
                work = false;
            }

            if(email != "" && password != "")
            {
                $.post(site_url + 'login/loginProcess', {email: email.val(), password: password.val()}, function(data){
                    var obj = jQuery.parseJSON(data);

                    if(obj.code == 1)
                    {
                        window.location.assign(site_url + 'dashboard');
                    }else
                    {
                        $(".middleLoginCont").prepend("<div class='clearfix error'>" + obj.string + "</div>");
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