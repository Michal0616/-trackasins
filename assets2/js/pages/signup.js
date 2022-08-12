$(function(){
    var work = false;
    var site_url = 'http://localhost/sitelyftstudios/';
    
    function removeAlerts(){
        $(".error").css('display', 'none').remove();
        $(".success").css('display', 'none').remove();
        $(".warning").css('display', 'none').remove();
    }
    
    $(document).on('submit','#signupMainForm', function()
    {
       if(work == false)
       {
           work = true;
           
           removeAlerts();
           
           var firstname = $("#sl_firstname");
           var lastname = $("#sl_lastname");
           var company_name = $("#sl_company_name");
           var seller_id = $("#sl_seller_id");
           var email = $("#sl_email");
           var confirm_email = $("#ta_email_confirm");
           var password = $("#sl_password");
           var confirm_pass = $("#sl_password_confirm");
           var phone_number = $("#sl_phone_number");

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
               $.post(site_url + 'signup/signupProcess', {firstname: firstname.val(), lastname: lastname.val(), company_name: company_name.val(), seller_id: seller_id.val(), email: email.val(), confirm_email: confirm_email.val(), password: password.val(), confirm_password: confirm_pass.val(), phone_number: phone_number.val()}, function(data){
                   var obj = jQuery.parseJSON(data);

                   if(obj.code == 1)
                   {
                       $(".middleSignupCont").prepend("<div class='clearfix success'>" + obj.string + "</div>");
                       work = false;
                   }else
                   {
                       $(".middleSignupCont").prepend("<div class='clearfix error'>" + obj.string + "</div>");
                       work = false;
                   }
               });
           }else{
               $(".middleSignupCont").prepend("<div class='clearfix error'>Please fill in all of the fields!</div>");
               work = false;
           }
       }
       return false;
    });
});