/*
* This is the main javascript class for the index ( Home ) page
*/
$(function(){
    var work = false;
    //var site_url = 'https://dev.trackasins.com/';
    var site_url = 'http://localhost/trackasins-master/';
    var r = 0;

    function removeAlerts(){
        $(".error").css('display', 'none').remove();
        $(".success").css('display', 'none').remove();
        $(".warning").css('display', 'none').remove();
    }
    
    // For the login system
    $(document).on('submit', '#loginMainForm', function(){
        if(work == false)
        {
            work = true;

            removeAlerts();

            var email = $("#sl_email_login");
            var password = $("#sl_password_login");

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

            if ($('#RememberMe').is(':checked')) {
                r = 1;
            }

            if(email.val() != "" && password.val() != "")
            {
                $.post(site_url + 'home/loginProcess', {email: email.val(), password: password.val(), remember: r}, function(data){
                    var obj = jQuery.parseJSON(data);

                    if(obj.code == 1)
                    {
                        window.location.assign(site_url + 'dashboard');
                    }else
                    {
                        $(".LoginSec").prepend("<div class='clearfix error'>" + obj.string + "</div>");
                        work = false;
                    }
                });
            }else{
                work = false;
            }
        }
        return false;
    });
    
    // For the signup system
    $(document).on('submit','#signupMainForm', function() {
        if(work == false)
        {
            work = true;

            removeAlerts();

            var firstname = $("#sl_firstname_signup");
            var lastname = $("#sl_lastname_signup");
            var company_name = $("#sl_company_name_signup");
            var seller_id = $("#sl_seller_id_signup");
            var email = $("#ta_email_signup");
            var confirm_email = $("#ta_email_confirm_signup");
            var password = $("#ta_password_signup");
            var confirm_pass = $("#ta_password_confirm_signup");
            var phone_number = $("#sl_phone_number_signup");

            if (firstname.val() == "")
            {
                firstname.addClass('input-danger');
                work = false;
            }

            if (lastname.val() == "")
            {
                lastname.addClass('input-danger');
                work = false;
            }

            if (company_name.val() == "")
            {
                company_name.addClass('input-danger');
                work = false;
            }

            if (seller_id.val() == "")
            {
                seller_id.addClass('input-danger');
                work = false;
            }

            if (email.val() == "")
            {
                email.addClass('input-danger');
                work = false;
            }

            if (confirm_email.val() == "")
            {
                confirm_email.addClass('input-danger');
                work = false;
            }

            if (password.val() == "")
            {
                password.addClass('input-danger');
                work = false;
            }

            if (confirm_pass.val() == "")
            {
                confirm_pass.addClass('input-danger');
                work = false;
            }

            if (phone_number.val() == "")
            {
                phone_number.addClass('input-danger');
                work = false;
            }

            if(firstname.val() != "" && lastname.val() != "" && company_name.val() != "" && seller_id.val() != "" && email.val() != "" && confirm_email.val() != "" && password.val() != "" && confirm_pass.val() != "" && phone_number.val() != "")
            {
                $.post(site_url + 'home/signupProcess', {firstname: firstname.val(), lastname: lastname.val(), company_name: company_name.val(), seller_id: seller_id.val(), email: email.val(), confirm_email: confirm_email.val(), password: password.val(), confirm_password: confirm_pass.val(), phone_number: phone_number.val()}, function(data){
                    var obj = jQuery.parseJSON(data);

                    if(obj.code == 1)
                    {
                        $(".RegisterSec").prepend("<div class='clearfix success'>" + obj.string + "</div>");
                        work = false;
                    }else
                    {
                        $(".RegisterSec").prepend("<div class='clearfix error'>" + obj.string + "</div>");
                        work = false;
                    }
                });
            }else{
                $(".RegisterSec").prepend("<div class='clearfix error'>Please fill in all of the fields!</div>");
                work = false;
            }
        }
        return false;
    });
    
    // This will validate the emails that they match
    $(document).on('keyup blur onchange', '#ta_email_confirm_signup', function(){
        var compare = $('#ta_email_signup').val();
        var string = $('#ta_email_confirm_signup').val();

        if(compare != "")
        {
            if(compare == string)
            {
                $(".toge").addClass('hiddenT');
                $(".forEmailS").removeClass('hiddenT');
            }else{
                $(".toge").addClass('hiddenT');
                $(".forEmailE").removeClass('hiddenT');
            }
        }else if(compare == "" && string == "")
        {
            $(".toge").addClass('hiddenT');
        }
    });

    $(document).on('keyup blur onchange', '#ta_password_confirm_signup', function(){
        var compare = $('#ta_password_signup').val();
        var string = $('#ta_password_confirm_signup').val();

        if(compare != "")
        {
            if(compare == string)
            {
                $(".togp").addClass('hiddenT');
                $(".forPasswordS").removeClass('hiddenT');
            }else{
                $(".togp").addClass('hiddenT');
                $(".forPasswordE").removeClass('hiddenT');
            }
        }else if(compare == "" && string == "")
        {
            $(".togp").addClass('hiddenT');
        }
    });
});