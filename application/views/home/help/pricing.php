<div class='mainCont'>
    <div class="settingsHeadline headline headline-site-color">
        <div class="container-fluid inner">
            <div class="topHeadline container text-center">
                <h3>Help & Support</h3>
            </div>
        </div>
    </div>
    <div class="mainSettingsCont spage">
        <div class="container mainSettingCont">
            <?php include('left_grid.php');?>
            <div class="settingsMainCont col-lg-9 col-sm-12 pull-right">
                <div class="innerCont card card-default clearfix">
                    <div class="topHeader col-lg-12">
                        <h3>Pricing</h3>
                        <h3 class="text-center"><br>
                            <span>Monthly Total Calculator: $</span><span id="monthly-total">0</span>
                        </h3>
                    </div>
                    <div class="bottomContent col-lg-12">
                        <div class="changePasswordHolder">
                            <?php /*echo form_open('settings/upgrade_plan_process', array('id' => 'settings_upgrade_plan', 'action' => ''));*/ ?>
                            <form>
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
<!--                                    <div class="box priceBox col-lg-6 col-lg-offset-6">-->
<!--                                        <div class="innerBox">-->
<!--                                            <div class="topHead">-->
<!--                                                <h3>Live Chat Support</h3>-->
<!--                                            </div>-->
<!--                                            <div class="content">-->
<!--                                                <div class="inputType">-->
<!--                                                    <select class="from-control" onchange="onGetTotal()" id="live-monthly">-->
<!--                                                        <option value="0">Nothing selected</option>-->
<!--                                                        <option value="50">Live Chat:$50/Month (9AM to 5PM EST)</option>-->
<!--                                                        <option value="200">Live Chat:$200/Month (24/7)</option>-->
<!--                                                    </select>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!---->
<!--                                    <div class="box priceBox col-lg-6 col-lg-offset-6">-->
<!--                                        <div class="innerBox">-->
<!--                                            <div class="topHead">-->
<!--                                                <h3>Phone Support</h3>-->
<!--                                            </div>-->
<!--                                            <div class="content">-->
<!--                                                <div class="inputType">-->
<!--                                                    <select class="from-control" onchange="onGetTotal()" id="phone-monthly">-->
<!--                                                        <option value="0">Nothing selected</option>-->
<!--                                                        <option value="100">Phone Support: $100/Month (9AM to 5PM EST)</option>-->
<!--                                                        <option value="300">Phone Support: $300/Month (24/7)</option>-->
<!--                                                    </select>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
                                                                    
                                
                                </div>
                                <div class="boxesSelectHolder clearfix col-lg-12">
                                <br>
                                <h5>TrackASINS: Variable Pricing Structure</h5> 
                                <p>The market’s full of third-party platforms for Amazon sellers, but TrackASINS offers a variable pricing solution that sets it apart from the rest. With TrackASINS, you pay only for what you need. </p> 
                                <p>TrackASINS keeps your subscription cost low by charging only what you need. You don’t have to spend hundreds, or even thousands for a whole suite of services including many tools you will never use. </p> 
                                <p>You simply select how many ASINS you would like to track, from the TrackASINS tiered pricing structure. Whether you’re only tracking a few ASINS or thousands of them, our variable pricing ensures you a much lower price than a overloaded platforms.</p> 
                                <p>Here are a few of the TrackASINS advantages:</p> 
                                <p>TrackASINS: an Affordable Amazon Third-Party Seller Solution: A number of factors contribute to our low cost:<br>
                                Low Start-up Subscription Cost: Starting at 10 ASINS for $25 per month, even small start-ups can get started tracking their products on Amazon. <br>
                                Variable Pricing Structure. As your business scales upward from a proprietorship to a growing company, it can take advantage of lower and lower costs per ASINS. That means a greater ROI on each product as your business grows.
We Never Charge a Percentage of Sales. Amazon’s already getting a piece of the pie. As a matter of principle, we never charge a percentage of sales. All you pay is our low monthly subscription price. <br>
TrackASINS: As Easy as Pie: While other platforms can be too techie and not very user friendly, TrackASINS keeps its interface and platform as simple as possible. We let you concentrate on your business, instead of your software. 
TrackASINS: Here, Now, and Tomorrow Too: Even though we have tons of ideas to make Track ASINS the go-to platform for Amazon third-party sellers, we decided to get our product on the market immediately with our simple tracking solution. We want your sales to start growing here and now, and we want you to be with us tomorrow as we grow with you.                                   
                                </p>                                 
                                </div>

                                <div class="bottomProfilePicForm col-lg-12" style="text-align: center;">
                                    <?php if(isset($_SESSION['user_id'])){ ?>
                                        <a href="<?php echo base_url()?>settings/membership_account" class="btn btn-embossed btn-success primarycolorbtn">Membership & Account </a>
                                    <?php } else { ?>
                                    <a href="<?php echo base_url()?>" class="btn btn-embossed btn-success primarycolorbtn">Sign Me Up!</a>
                                    <?php } ?>
                                </div>
                            </form>
                                <?php /*echo '</form>';*/ ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url()?>/assets2/js/jquery.js" type="text/javascript"></script>
<script type="text/javascript">
//    function onGetTotal(){
//        var total = 0;
//        if($("#asins-monthly").val() !='') {
//            total = total+ $("#asins-monthly").val() * 1;
//        }
//        if($("#live-monthly").val() !='') {
//            total = total+ $("#live-monthly").val() * 1;
//        }
//        if($("#email-monthly").val() !='') {
//            total = total+ $("#email-monthly").val() * 1;
//        }
//        if($("#phone-monthly").val() !='') {
//            total = total+ $("#phone-monthly").val() * 1;
//        }
//        $("#monthly-total").text(total);
//    }
$(document).ready(function() {
    onGetTotal();
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
        url: '<?php echo site_url("/help/get_total_value")?>',
        data: {'track_support_id': asins_monthly, 'email_support_id': email_monthly},
        method: 'POST',
        dataType: 'json'
    }).success(function (response) {
        if (response.status == 'success') {
            $("#monthly-total").text(response.total);
        }
    });
}
</script>
