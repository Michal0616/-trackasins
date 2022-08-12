<style>
    .hr-sect {
        display: flex;
        flex-basis: 100%;
        align-items: center;
        color: rgba(0, 0, 0, 0.35);
        margin: 8px 0px;
    }
    .hr-sect::before,
    .hr-sect::after {
        content: "";
        flex-grow: 1;
        background: rgba(0, 0, 0, 0.35);
        height: 1px;
        font-size: 0px;
        line-height: 0px;
        margin: 0px 8px;
    }
</style>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Delete Account</h4>
            </div>
            <div class="modal-body">
                <p style="font-weight: 500;">Are you sure you want to delete your account?</p>
            </div>
            <div class="modal-footer">
                <?php /*echo form_open('settings/upgrade_plan_process', array('id' => 'settings_upgrade_plan', 'action' => ''));*/ ?>
                    <from>
                    <button type="button" class="btn btn-embossed btn-success primarycolorbtn" id="confirmDeleteAccountButton">Confirm! Delete Account</button>
                    <button type="button" class="btn btn-default btn-embossed" data-dismiss="modal">Close</button>
                    </from>
                <?php /*echo '</form>';*/ ?>
            </div>
        </div>

    </div>
</div>
<!-- upgrade subscription modal start -->
<div class="modal face" id="updateSubscriptionModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="confirmUpdateSubscriptionModelTextTitle">Confirm Upgrade or Downgrade Subscription</h4>
            </div>
            <div class="modal-body">
                <p style="padding-bottom:10px;" id="confirmUpdateSubscriptionModelTextContent">Are you sure you want to upgrade or downgrade your subscription?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-embossed btn-success primarycolorbtn" id="confirmUpdateSubscriptionButton">Yes I am sure</button>
                <button type="button" class="btn btn-default btn-embossed" data-dismiss="modal">No go back</button>
            </div>
        </div>
    </div>
</div>
<!-- upgrade subscription modal end -->

<!-- cancel subscription modal start -->
<div class="modal face" id="cancelSubscriptionModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Confirm Cancel Subscription</h4>
            </div>
            <div class="modal-body">
                <p style="padding-bottom:10px;">Are you sure you want to cancel your subscription?</p>
                <p style="padding-bottom:10px;">All your ASINS will stop tracking</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-embossed btn-success primarycolorbtn" id="confirmCancelSubscriptionButton">Yes I am sure</button>
                <button type="button" class="btn btn-default btn-embossed" data-dismiss="modal">No go back</button>
            </div>
        </div>
    </div>
</div>
<!-- cancel subscription modal end -->

<!-- resume subscription modal start -->
<div class="modal face" id="resumeSubscriptionModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Confirm Resume Subscription</h4>
            </div>
            <div class="modal-body">
                <p style="padding-bottom:10px;">Are you sure you want to resume your subscription?</p>
                <p style="padding-bottom:10px;">All your ASINS will start tracking</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-embossed btn-success primarycolorbtn" id="confirmResumeSubscriptionButton">Yes I am sure</button>
                <button type="button" class="btn btn-default btn-embossed" data-dismiss="modal">No go back</button>
            </div>
        </div>
    </div>
</div>
<!-- resume subscription modal end -->

<div class="settingsHeadline headline headline-site-color">
    <div class="container-fluid inner">
        <div class="topHeadline container text-center">
            <h3>Settings</h3>
        </div>
    </div>
</div>
<div class="mainSettingsCont">
    <div class="container mainSettingCont">
        <?php include('left_grid.php');?>
        <div class="settingsMainCont col-lg-9 col-md-8 col-xs-12 col-sm-12 pull-right">
            <div class="innerCont card card-default clearfix">
                <div class="topHeader col-lg-12">
                    <h3>Membership and Account</h3>
                    <h3 class="text-center"><br>
                        <span>Monthly Total: $</span><span id="monthly-total">0</span>
                    </h3>
                </div>
                <div class="bottomContent col-lg-12">
                    <!-- FOR CHANGING PASSWORDS -->
                    <div class="changePasswordHolder">
                        <?php /*echo form_open('settings/upgrade_plan_process', array('id' => 'settings_upgrade_plan', 'action' => ''));*/ ?>
                        <form id="paymentForm" >
                        <div class='boxesSelectHolder clearfix'>
                           <div class="box priceBox col-lg-6">
                               <div class="innerBox">
                                   <div class="topHead">
                                       <h3>TrackASINS</h3>
                                   </div>
                                   <div class="content">
                                       <div class="inputType">
                                           <select class="from-control" onchange="onGetTotal()" id="asins-monthly" name="track_support_id" open>
                                               <?php if(count($track_supports)>0) {
                                                        foreach($track_supports as $key => $track_support){?>
                                                            <?php if($track_support->price ==  99999) {?>
                                                                <option value="<?php echo $track_support->id;?>" <?php if(isset($support) && ($support->track_support == $track_support->id)){?> selected <?php }?>><?php echo $track_support->description;?></option>
                                                            <?php } else {?>
                                                                <option value="<?php echo $track_support->id;?>" <?php if(isset($support) && ($support->track_support == $track_support->id)){?> selected <?php }?>><?php echo $track_support->description." $".$track_support->price."/Month"; ?></option>
                                                            <?php } ?>
                                               <?php } }?>
                                           </select>
                                       </div>
                                   </div>
                               </div>
                           </div>
                            <div class="box priceBox col-lg-6">
                                <div class="innerBox">
                                    <div class="topHead">
                                        <h3>Email Support</h3>
                                    </div>
                                    <div class="content">
                                        <div class="inputType">
                                            <select class="from-control" onchange="onGetTotal()" id="email-monthly" name="email_support_id">
                                                <?php if(count($email_supports) >0) {
                                                    foreach($email_supports as $key => $email_support) {?>
                                                        <?php if($email_support->price != 0) {?>
                                                            <option value="<?php echo $email_support->id ?>" <?php if(isset($support) && ($support->email_support == $email_support->id)){?> selected <?php }?>><?php echo $email_support->description." : $". $email_support->price."/Month" ?></option>
                                                        <?php } else {?>
                                                            <option value="<?php echo $email_support->id ?>" <?php if(isset($support) && ($support->email_support == $email_support->id)){?> selected <?php }?>><?php echo $email_support->description ?> : Free</option>
                                                        <?php }?>
                                                <?php } }?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="token" value="" />
                        <input type="hidden" name="total_value"  id="total_value"/>
                        <?php if(!isset($subscription))  { ?>
                        <?php if(isset($card) && $card) { ?>
                        <br>
                        <div class="text-center">
                            <input type="button" class="btn btn-embossed btn-success primarycolorbtn" value="Pay With <?php echo $card['brand'] ?> **<?php echo $card['last4'] ?>" name="submit-button" id="updateWithExistingCard"/>
                            <input type="hidden" name="existing_card" value="0">
                        </div>
                        <div class="hr-sect">OR USE ANOTHER CARD</div>
                        <?php } ?>
                        <div class="basicInfoCont  basicInfoContDiv col-lg-12" style="">
                             <div class="inputType paymentDiv col-lg-12 col-md-12">
                                 <label>Name</label>
                                 <input type="text" class="" id="payment_name" name="payment_name" placeholder="Name" />
                             </div>

                             <div class="inputType paymentDiv col-lg-12 col-md-12">
                                 <label>CC#</label><span class="tx-danger">*</span>
                                 <input type="text" class="card-number stripe-sensitive required" id="cc" name="billing_card_number" placeholder="CC#" maxlength="22" autocomplete="off"  data-stripe="number"/>
                             </div>
                             <div class="inputType paymentDiv col-lg-9 col-md-9">
                                 <div class="row">
                                     <div class="inputType paymentDiv col-lg-6 col-md-6">
                                         <label>Exp Month <span class="tx-danger">*</span></label>
                                         <select name="billing_card_month" class="card-expiry-month stripe-sensitive stripe-expiration required" id="card-expiry-month" data-stripe="exp-month">
                                             <?php for($i=1; $i<=12; $i++){?>
                                                 <option value="<?php echo $i;?>" <?php if($i == date('m')) {?>  selected <?php }?>> <?php echo sprintf("%02d",$i);?></option>
                                             <?php }?>
                                         </select>
                                     </div>
                                     <div class="inputType paymentDiv col-lg-6 col-md-6">
                                         <label>Exp Year <span class="tx-danger">*</span></label>
                                         <select class="card-expiry-year stripe-sensitive required stripe-expiration valid" id="billing_card_year" data-stripe="exp-year" name="card-expiry-year">
                                             <?php for($i= date('Y'); $i<= date('Y')+10; $i++) {?>
                                                 <option value="<?php echo $i;?>" <?php if($i == date('Y')) {?>  selected <?php }?>> <?php echo $i;?></option>
                                             <?php }?>
                                         </select>
                                     </div>
                                 </div>
                             </div>
                             <div class="inputType paymentDiv col-lg-3 col-md-3">
                                 <label>CVV</label> <span class="tx-danger">*</span>
                                 <input type="text" class="card-cvc stripe-sensitive required" id="cvv" name="billing_card_cvc" placeholder="CVV" data-stripe="cvc"/>
                             </div>
                         </div>
                         <?php } else {?>
                            <div class="basicInfoCont  basicInfoContDiv col-lg-12" style="">
                                <?php if($subscription->ends_at == null) {?>
                                    <p style="padding:10px">You are currently subscribed to the "$<?php echo  $current_track_support." ASINS Plan and $".$current_email_support ?> Support Plan" and your Bill is set to Auto Renew ON: <?php $currentDate = substr($subscription->ends_date_subscription,0,10); $currentNewDate = date("m/d/Y", strtotime($currentDate)); echo $currentNewDate; ?></p>
                                    <p style="padding:10px">
                                        <?php
                                            if(isset($planItems)){
                                                echo "You are currently using " . $planItems['current_count'] ." ASINS out of your ".$planItems['plan_count'] . " Plan Limit";
                                            }
                                        ?>
                                    </p>
                                <?php } else {
                                    $currentDate =substr($subscription->ends_at,0,10);
                                    $currentNewDate = date("m/d/Y", strtotime($currentDate));?>
                                    <p style="padding:10px">Your subscription has been cancelled. <!--Subscription end date is <?php //echo $currentNewDate; ?>--></p>
                                    <p style="padding:10px">
                                        <?php
                                            if(isset($planItems)){
                                                echo "You are currently using " . $planItems['current_count'] ." ASINS out of your ".$planItems['plan_count'] . " plan limit";
                                            }
                                        ?>
                                    </p>
                                <?php }?>
                            </div>
                         <?php }?>

                        </form>
                        <div class="bottomProfilePicForm buttonsGroup col-lg-12">
                            <input type="button" class="btn btn-embossed btn-success primarycolorbtn" value="Update Plan" name="submit-button" id="submitPaymentFormButton"/>
                            <?php if(isset($subscription) && $subscription->ends_at == null)  {?>
                                <button class="btn btn-embossed btn-success primarycolorbtn" data-toggle="modal" data-target="#cancelSubscriptionModal" id="cancelSubscriptionButton">Cancel Subscription</button>
                            <?php } else if (isset($subscription) && $subscription->ends_at >= date('Y-m-d H:i:s')) {?>
                                <button class="btn btn-embossed btn-success primarycolorbtn" data-toggle="modal" data-target="#resumeSubscriptionModal" id="resumeSubscriptionButton">Resume Subscription</button>
                            <?php } ?>
                            <a href="#" class="btn btn-embossed btn-danger closeOpenModal" data-toggle="modal" data-target="#myModal">Close My Account and End Billing</a>
                            <?php if (isset($subscription)) { ?>
                                <input type="button" class="btn btn-embossed btn-success primarycolorbtn" value="Update Payment Method" name="submit-button" id="toggle-update-card-form"/>
                            <?php } ?>
                        </div>
                        <input type="hidden" name="current_total_value" id="current_total_value" value="<?php echo $current_total_value; ?>">
                        <input type="hidden" name="current_track_support" id="current_track_support" value="<?php echo $current_track_support; ?>">
                        <input type="hidden" name="current_email_support" id="current_email_support" value="<?php echo $current_email_support; ?>">
                        <input type="hidden" name="modified_track_support" id="modified_track_support" value="">
                        <input type="hidden" name="modified_email_support" id="modified_email_support" value="">

                        
                        <input type="hidden" id="current_subscription" value="<?php if(isset($subscription)) {echo "1";} else {echo "0";}?>">
                    </div>

                    <!-- Update Card Form -->
                    <?php if(isset($subscription))  {?>
                    <div class="changePasswordHolder">
                        <form id="updatePaymentMethodForm">
                            <input type="hidden" name="token" value="" />
                            <div id="card-details-div" class="basicInfoCont  basicInfoContDiv col-lg-12 hide" style="">
                                <div class="inputType paymentDiv col-lg-12 col-md-12">
                                    <label>Name</label>
                                    <input type="text" class="" id="payment_name" name="payment_name" placeholder="Name" />
                                </div>

                                <div class="inputType paymentDiv col-lg-12 col-md-12">
                                    <label>CC#</label><span class="tx-danger">*</span>
                                    <input type="text" class="card-number stripe-sensitive required" id="cc" name="billing_card_number" placeholder="CC#" maxlength="22" autocomplete="off"  data-stripe="number"/>
                                </div>
                                <div class="inputType paymentDiv col-lg-9 col-md-9">
                                    <div class="row">
                                        <div class="inputType paymentDiv col-lg-6 col-md-6">
                                            <label>Exp Month <span class="tx-danger">*</span></label>
                                            <select name="billing_card_month" class="card-expiry-month stripe-sensitive stripe-expiration required" id="card-expiry-month" data-stripe="exp-month">
                                                <?php for($i=1; $i<=12; $i++){?>
                                                    <option value="<?php echo $i;?>" <?php if($i == date('m')) {?>  selected <?php }?>> <?php echo sprintf("%02d",$i);?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                        <div class="inputType paymentDiv col-lg-6 col-md-6">
                                            <label>Exp Year <span class="tx-danger">*</span></label>
                                            <select class="card-expiry-year stripe-sensitive required stripe-expiration valid" id="billing_card_year" data-stripe="exp-year" name="card-expiry-year">
                                                <?php for($i= date('Y'); $i<= date('Y')+10; $i++) {?>
                                                    <option value="<?php echo $i;?>" <?php if($i == date('Y')) {?>  selected <?php }?>> <?php echo $i;?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="inputType paymentDiv col-lg-3 col-md-3">
                                    <label>CVV</label> <span class="tx-danger">*</span>
                                    <input type="text" class="card-cvc stripe-sensitive required" id="cvv" name="billing_card_cvc" placeholder="CVV" data-stripe="cvc"/>
                                </div>
                                <div class="bottomProfilePicForm buttonsGroup col-lg-12">
                                    <input type="button" id="submitPaymentMethod" class="btn btn-embossed btn-success primarycolorbtn pull-right" value="Save" name="submit-button"/>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php } ?>

                </div>

            </div>
        </div>
    </div>
</div>


<!--<script src="--><?php //base_url()?><!--/assets2/js/jquery.js" type="text/javascript"></script>-->

<link rel="stylesheet" href="<?php base_url()?>/assets2/global/plugins/bootstrap-select/css/bootstrap-select.min.css">
<link rel="stylesheet" href="<?php base_url()?>/assets2/global/plugins/bootstrap-sweetalert/sweetalert.css">
<script src="<?php echo site_url('assets2/global/plugins/jquery.min.js')?>" type="text/javascript"></script>
<script src="<?php echo site_url('assets2/global/plugins/jquery-validation/js/jquery.validate.js')?>" type="text/javascript"></script>

<script src="https://js.stripe.com/v2"></script>
<script src="<?php echo site_url('assets2/global/plugins/bootstrap-sweetalert/sweetalert.js')?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // Change the key to your one
        Stripe.setPublishableKey("<?php echo $this->config->item('STRIPE_KEY')?>");

        function addInputNames() {
            $(".card-number").attr("name", "card-number");
            $(".card-cvc").attr("name", "card-cvc");
            $(".card-expiry-month").attr("name", "card-expiry-month");
            $(".card-expiry-year").attr("name", "card-expiry-year");
        }
        function removeInputNames() {
            $(".card-number").removeAttr("name");
            $(".card-cvc").removeAttr("name");
            $(".card-expiry-month").removeAttr("name");
            $(".card-expiry-year").removeAttr("name");
        }
        // add custom rules for credit card validating
        jQuery.validator.addMethod("cardNumber", Stripe.validateCardNumber, "Please enter a valid card number");
        jQuery.validator.addMethod("cardCVC", Stripe.validateCVC, "Please enter a valid security code");
        jQuery.validator.addMethod("cardExpiry", function() {
            return Stripe.validateExpiry($(".card-expiry-month").val(),
                $(".card-expiry-year").val())
        }, "Please enter a valid expiration");
        $("#paymentForm").validate({
            rules: {
                "card-cvc" : {
                    cardCVC: true,
                    required: true
                },
                "card-number" : {
                    cardNumber: true,
                    required: true,
                    creditcard: true
                },
                "card-expiry-month" : "cardExpiry",
                "card-expiry-year" : "cardExpiry"
            },
            submitHandler: function(form) {
                $("#loadingSpinner").show();
                removeInputNames();
                var $form = $("#paymentForm");
                $("#submitPaymentFormButton").attr("disabled", "disabled");
                $form.find('[name="token"]').val('');
                var total_monthly = $("#monthly-total").text();
                if(total_monthly ==0){
                    swal({
//                        title: 'Warning',
                        title: '',
                        text: "Please upgrade your support plans ",
                        type: 'warning',
                        confirmButtonClass: "confirm-button-color",
                        confirmButtonText: "Ok"
                    });
                    $("#submitPaymentFormButton").removeAttr("disabled");
                }else{
                    $("#total_value").val(total_monthly);
                    Stripe.card.createToken($form, function(status, response) {
                        if (response.error) {
                            swal({
                                //                        title: 'Warning',
                                title: '',
                                text: response.error.message,
                                type: 'warning',
                                confirmButtonClass: "confirm-button-color",
                                confirmButtonText: "Ok"
                            });
                            $("#submitPaymentFormButton").removeAttr("disabled");
                            $("#loadingSpinner").hide();
                            return;
                        } else {
                            $form.find('[name="token"]').val(response.id);
                            // Or using Ajax
                            $.ajax({
                                // You need to change the url option to your back-end endpoint
                                url: '<?php echo site_url("/settings/upgrade_plan_process")?>',
                                data: $form.serialize(),
                                method: 'POST',
                                dataType: 'json'
                            }).success(function (response) {
                                if (response.result == 'success') {
                                    $("#loadingSpinner").hide();
                                    swal({
                                        title: "Success",
                                        text: response.message,
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

//                                    swal({
//                                        title: 'Success',
//                                        text: response.message,
//                                        type: 'success'
//                                    });
//
//                                    window.location.reload();
                                } else {
                                    $("#loadingSpinner").hide();
                                    swal({
                                        //                        title: 'Warning',
                                        title: '',
                                        text: response.message,
                                        type: 'warning',
                                        confirmButtonClass: "confirm-button-color",
                                        confirmButtonText: "Ok"
                                    });
                                    $("#submitPaymentFormButton").removeAttr("disabled")
                                }
                            });
                        }
                    });
                }
            }
        });
        // Update Payment Method Handler
        $("#updatePaymentMethodForm").validate({
            rules: {
                "card-cvc" : {
                    cardCVC: true,
                    required: true
                },
                "card-number" : {
                    cardNumber: true,
                    required: true,
                    creditcard: true
                },
                "card-expiry-month" : "cardExpiry",
                "card-expiry-year" : "cardExpiry"
            },
            submitHandler: function(form) {
                $("#loadingSpinner").show();
                removeInputNames();
                var $form = $("#updatePaymentMethodForm");
                $("#submitPaymentMethod").attr("disabled", "disabled");
                $form.find('[name="token"]').val('');
                Stripe.card.createToken($form, function(status, response) {
                    if (response.error) {
                        swal({
                            //                        title: 'Warning',
                            title: '',
                            text: response.error.message,
                            type: 'warning',
                            confirmButtonClass: "confirm-button-color",
                            confirmButtonText: "Ok"
                        });
                        $("#submitPaymentMethod").removeAttr("disabled");
                        $("#loadingSpinner").hide();
                        return;
                    } else {
                        $form.find('[name="token"]').val(response.id);
                        // Or using Ajax
                        $.ajax({
                            // You need to change the url option to your back-end endpoint
                            url: '<?php echo site_url("/settings/update_payment_method")?>',
                            data: $form.serialize(),
                            method: 'POST',
                            dataType: 'json'
                        }).success(function (response) {
                            if (response.result == 'success') {
                                $("#loadingSpinner").hide();
                                swal({
                                        title: "Success",
                                        text: response.message,
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
                            } else {
                                $("#loadingSpinner").hide();
                                swal({
                                    //                        title: 'Warning',
                                    title: '',
                                    text: response.message,
                                    type: 'warning',
                                    confirmButtonClass: "confirm-button-color",
                                    confirmButtonText: "Ok"
                                });
                                $("#submitPaymentMethod").removeAttr("disabled")
                            }
                        });
                    }
                });
            }
        });
        addInputNames();
        onGetTotal();
        $("#submitPaymentFormButton").click(function(){
            if($("#current_subscription").val() == 1) {
                $("#updateSubscriptionModal").modal("show");
            } else {
                $("#paymentForm").submit();
            }
        });
        $("#submitPaymentMethod").click(function () {
            $("#updatePaymentMethodForm").submit();
        });
        $("#confirmUpdateSubscriptionButton").click(function(){
            onUpdateSubscriptionPlan();
        });
        $("#confirmCancelSubscriptionButton").click(function(){
            onCancelSubscription();
        });

        $("#confirmResumeSubscriptionButton").click(function() {
           onResumeSubscription();
        });

        $("#confirmDeleteAccountButton").click(function(){
           onDeleteAccount();
        });

        $(document).on("click", "#toggle-update-card-form", function () {
            $("#card-details-div").toggleClass("hide");
        });

        $(document).on("click", "#updateWithExistingCard", function () {
            $("#loadingSpinner").show();
            var $this = $(this);
            var total_monthly = $("#monthly-total").text();
            $("input[name=existing_card]").val(1);
            $this.attr("disabled", "disabled");
            $("#total_value").val(total_monthly);
            $.ajax({
                // You need to change the url option to your back-end endpoint
                url: '<?php echo site_url("/settings/upgrade_plan_process")?>',
                data: $("#asins-monthly, #email-monthly, #total_value, input[name=existing_card]").serialize(),
                method: 'POST',
                dataType: 'json'
            }).success(function (response) {
                if (response.result == 'success') {
                    $("#loadingSpinner").hide();
                    swal({
                        title: "Success",
                        text: response.message,
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
                } else {
                    $("#loadingSpinner").hide();
                    swal({
                        title: '',
                        text: response.message,
                        type: 'warning',
                        confirmButtonClass: "confirm-button-color",
                        confirmButtonText: "Ok"
                    });
                    $this.removeAttr("disabled")
                }
            });
        });
    });

    function onGetTotal(){
        var total = 0;
        var asins_monthly ="";
        var email_monthly ="";
        if($("#asins-monthly").val() !='') {
            asins_monthly = $("#asins-monthly").val();
        }
        if($("#email-monthly").val() !='') {
            email_monthly = $("#email-monthly").val();
        }

        $.ajax({
            // You need to change the url option to your back-end endpoint
            url: '<?php echo site_url("/settings/get_total_value")?>',
            data: {'track_support_id': asins_monthly, 'email_support_id': email_monthly},
            method: 'POST',
            dataType: 'json'
        }).success(function (response) {
            if (response.status == 'success') {
                track_support = parseInt(response.track_support);
                email_support = parseInt(response.email_support);
                email_support_desc= response.email_support_desc;
                track_support_desc= response.track_support_desc;
                tot = track_support + email_support; 
                //alert(response.track_support);
                $("#monthly-total").text(tot);
                $("#modified_track_support").val(track_support)
                $("#modified_email_support").val(email_support)
                
                current_track_support = $("#current_track_support").val();
                current_email_support = $("#current_email_support").val();
              // alert($("#monthly-total").text());
               // alert($("#current_total_value").val());
               // alert(Math.abs(email_support));
               //alert(current_email_support);
                //alert(email_support);
                
                if(current_track_support != track_support && current_email_support != email_support) {
                $("#confirmUpdateSubscriptionModelTextTitle").text("Confirm subscription and email support ");
                $("#confirmUpdateSubscriptionModelTextContent").text('Are you sure you want to choose "$'+track_support+' ASINS plan and $'+email_support+' support plan" ?');
                }
                if(current_track_support != track_support && current_email_support == email_support) {
                $("#confirmUpdateSubscriptionModelTextTitle").text("Confirm subscription and email support ");
                $("#confirmUpdateSubscriptionModelTextContent").text('Are you sure you want to choose "$'+track_support+' ASINS plan" ?');
                }
                if(current_track_support == track_support && current_email_support != email_support) {
                $("#confirmUpdateSubscriptionModelTextTitle").text("Confirm subscription and email support ");
                $("#confirmUpdateSubscriptionModelTextContent").text('Are you sure you want to choose "$'+email_support+' support plan" ?');
                }
                // if(current_track_support <= track_support && current_email_support == email_support) {
                //     $("#confirmUpdateSubscriptionModelTextTitle").text("Confirm upgrade subscription ");
                //     $("#confirmUpdateSubscriptionModelTextContent").text("Are you sure you want to upgrade your subscription?");
                // } else {
                //     $("#confirmUpdateSubscriptionModelTextTitle").text("Confirm downgrade subscription ");
                //     $("#confirmUpdateSubscriptionModelTextContent").text("Are you sure you want to downgrade your subscription?");
                // }
                // if (current_email_support > email_support) {
                //     $("#confirmUpdateSubscriptionModelTextTitle").text("Confirm Email Support");
                //     $("#confirmUpdateSubscriptionModelTextContent").text("Are you sure you want to choose free Email Support?");
                // }
                // if (current_email_support < email_support) {
                //     $("#confirmUpdateSubscriptionModelTextTitle").text("Confirm Email Support ");
                //     $("#confirmUpdateSubscriptionModelTextContent").text("Are you sure you want to choose free Email Support?");
                // }

                compareTotalValue();
            }
        });
    }
    function compareTotalValue(){
        if($("#current_total_value").val() == $("#monthly-total").text()){
            $("#submitPaymentFormButton").attr("disabled", "disabled");
        } else {
            $("#submitPaymentFormButton").removeAttr("disabled");
        }
    }

    function onUpdateSubscriptionPlan(){
        $("#updateSubscriptionModal").modal('hide');
        $("#loadingSpinner").show();
        $.ajax({
            // You need to change the url option to your back-end endpoint
            url: '<?php echo site_url("/settings/upgrade_plan_process")?>',
            data: {'track_support_id': $("#asins-monthly").val(), 'email_support_id' : $("#email-monthly").val(), 'total_value': $("#monthly-total").text(), 'track_support': $("#modified_track_support").val(), 'email_support': $("#modified_email_support").val()},
            method: 'POST',
            dataType: 'json'
        }).success(function (response) {
            if (response.result == 'success') {
                $("#loadingSpinner").hide();
                swal({
                    title: "Success",
                    text: response.message,
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

//                swal({
//                    title: 'Success',
//                    text: response.message,
//                    type: 'success'
//                });

//                window.location.reload();
            } else {
                $("#loadingSpinner").hide();
                swal({
                    //                        title: 'Warning',
                    title: '',
                    text: response.message,
                    type: 'warning',
                    confirmButtonClass: "confirm-button-color",
                    confirmButtonText: "Ok"
                });
                $("#submitPaymentFormButton").removeAttr("disabled")
            }
        });
    }
    function onCancelSubscription(){
        $("#cancelSubscriptionModal").modal('hide');
        $("#loadingSpinner").show();
        $.ajax({
            // You need to change the url option to your back-end endpoint
            url: '<?php echo site_url("/settings/cancel_subscription")?>',
            data: {},
            method: 'POST',
            dataType: 'json'
        }).success(function (response) {
            if (response.result == 'success') {
                $("#loadingSpinner").hide();
                swal({
                    title: "Success",
                    text: response.message,
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

//                swal({
//                    title: 'Success',
//                    text: response.message,
//                    type: 'success'
//                });
//
//                window.location.reload();
            } else {
                $("#loadingSpinner").hide();
                swal({
                    //                        title: 'Warning',
                    title: '',
                    text: response.message,
                    type: 'warning',
                    confirmButtonClass: "confirm-button-color",
                    confirmButtonText: "Ok"
                });
            }
        });
    }

    function onResumeSubscription(){
        $("#resumeSubscriptionModal").modal('hide');
        $("#loadingSpinner").show();
        $.ajax({
            // You need to change the url option to your back-end endpoint
            url: '<?php echo site_url("/settings/resume_subscription")?>',
            data: {},
            method: 'POST',
            dataType: 'json'
        }).success(function (response) {
            if (response.result == 'success') {
                $("#loadingSpinner").hide();
                swal({
                    title: "Success",
                    text: response.message,
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

//                swal({
//                    title: 'Success',
//                    text: response.message,
//                    type: 'success'
//                });
//
//                window.location.reload();
            } else {
                $("#loadingSpinner").hide();
                swal({
                    //                        title: 'Warning',
                    title: '',
                    text: response.message,
                    type: 'warning',
                    confirmButtonClass: "confirm-button-color",
                    confirmButtonText: "Ok",
                });
            }
        });
    }

    function onDeleteAccount(){
        $("#myModal").hide();
        $("#loadingSpinner").show();

        $.ajax({
            // You need to change the url option to your back-end endpoint
            url: '<?php echo site_url("/settings/deleteAccount")?>',
            data: {},
            method: 'POST',
            dataType: 'json'
        }).success(function (response) {
            if (response.result == 'success') {
                $("#loadingSpinner").hide();
                swal({
                        title: "Success",
                        text: response.message,
                        type: "success",
                        showCancelButton: false,
                        confirmButtonClass: "confirm-button-color",
                        confirmButtonText: "OK",
                        closeOnConfirm: false,
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            window.location.href='<?php echo site_url(); ?>';
                        }
                    });
            }
        });

    }

</script>

