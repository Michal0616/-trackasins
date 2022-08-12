<div class='mainCont'>
    <div class="settingsHeadline headline headline-site-color">
        <div class="container-fluid inner">
            <div class="topHeadline container text-center">
                <h3>Help & Support</h3>
            </div>
        </div>
    </div>
    <div class="mainSettingsCont">
        <div class="container mainSettingCont">
            <?php include('left_grid.php');?>
            <div class="settingsMainCont col-lg-9 col-sm-12 pull-right">
                <div class="innerCont card card-default clearfix">
                    <div class="topHeader col-lg-12">
                        <h3>Contact Us</h3>
                    </div>
                    <div class="bottomContent col-lg-12" style='padding-left: 0px;'>
                        <!-- FOR PROFILE PICTURE -->
                        <div class="profilePictureHolder" style="border: none;">
                            <!-- <div class="topInfo clearfix" style='border-bottom: 1px solid #eee;padding-bottom: 15px;'>
                                <div class="col-lg-4 in pull-left">
                                    <h5><a href="mailto::Info@trackasins.com">Info@trackasins.com</a></h5>
                                    <h5>Location: Rockland County, NY</h5>
                                    <h5>Phone Number: 1-845-630-8226</h5>
                                </div>
                            </div> -->
                            <div class='form'><br />
                            <?php echo form_open('help/contact-form-process', array('id' => 'help_contact_us_form', 'action' => '')); ?>
                                <div class="inputType">
                                    <label>Your Name</label>
                                    <input type="text" class="" id="your_name" name="your_name" placeholder="Your Full Name" />
                                </div>
                                <div class="inputType">
                                    <label>Your Email</label>
                                    <input type="email" class="" id="your_email" name="your_email" placeholder="Your Email" />
                                </div>
                                <div class="inputType">
                                    <label>Subject</label>
                                    <input type="text" class="" id="subject" name="subject" placeholder="Subject" />
                                </div>
                                <div class="inputType">
                                    <label>Message</label>
                                    <textarea class="" id="message_body" name="message_body" placeholder="How can we help?"></textarea>
				</div>
				<!--div class="inputType">
				    <div class="g-recaptcha" data-sitekey="<?php //echo $this->config->item('google_recaptcha_key') ?>"></div>
                                </div-->
                                <div class="bottomProfilePicForm">
				    <!--input type="submit" class="btn btn-embossed btn-success primarycolorbtn" value="Send Message" /-->
				    <button class="g-recaptcha btn btn-embossed btn-success primarycolorbtn" 
        data-sitekey="<?php echo $this->config->item('google_recaptcha_key') ?>" 
        data-callback='onSubmit' 
        data-action='submit'>Send Message</button>
                                </div>
				<?php echo '</form>'; ?><br>
				<div id="contact_error"></div>
				<div id="contact_success"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function onSubmit(token) {
	// Variables
	// var site_url = 'https://dev.trackasins.com/';
    var site_url = 'http://localhost/trackasins/';
        var contact_name = $("#your_name");
        var contact_email = $("#your_email");
	var contact_subject  = $("#subject");
	var contact_msg = $("#message_body");

	if(contact_name.val() == "")
        {
            contact_name.addClass('input-danger');
            return false;
        }else{
            contact_name.removeClass('input-danger');
	}

	if(contact_email.val() == "")
        {
            contact_email.addClass('input-danger');
            return false;
        }else{
            contact_email.removeClass('input-danger');
	}

	var validEmail = validateEmail(contact_email.val());

	if(!validEmail)
        {
            contact_email.addClass('input-danger');
            return false;
        }else{
            contact_email.removeClass('input-danger');
	}

	if(contact_subject.val() == "")
        {
            contact_subject.addClass('input-danger');
            return false;
        }else{
            contact_subject.removeClass('input-danger');
	}

	if(contact_msg.val() == "")
        {
            contact_msg.addClass('input-danger');
            return false;
        }else{
            contact_msg.removeClass('input-danger');
        }

	if(contact_name.val() != "" && contact_email.val() != ""  && contact_subject.val() != "" && contact_msg.val() != "" && validEmail)
	{
		//alert("curl posting.."+site_url);
		//document.getElementById("help_contact_us_form").submit();
		$.post(site_url + "help/contact-form-process", {your_name:contact_name.val(), your_email:contact_email.val(), subject:contact_subject.val(), message_body:contact_msg.val()}, function(data){
			//alert(data);
                    var obj = jQuery.parseJSON(data);
			//alert(obj.code+"==="+obj.string+"***"+data);
                    if(obj.code == 1)
                    {
                        $("#contact_success").prepend("<div class='clearfix alert alert-success'>" + obj.string + "</div>");
                    }else{
                        $("#contact_error").prepend("<div class='clearfix alert alert-danger'>" + obj.string + "</div>");
                    }
                });
	}else{
		document.getElementById("contact_error").innerHTML = "Some error occured. Please try again!";
	}
}

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}
 </script>
