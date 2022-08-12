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
        <form action="" method="post" enctype="multipart/form-data" id="settings_notifications_form">
        
            <div class="innerCont card card-default clearfix">
                <div class="topHeader col-lg-12">
                    <h3>Notification Settings</h3>
                </div>
                <div class="bottomContent col-lg-12">
                    <!-- FOR CHANGING PASSWORDS -->
                    <div class="changePasswordHolder notificationSettingsLabel" style="border-bottom: 1px solid #eee;">

                        <?php 
                        $user_id=$this->session->userdata('user_id');
                        $query = $this->db->query("SELECT * FROM users where ID='$user_id' ")->result();
                        
                        foreach ($query as $query) {
                            
                            $email = $query->notification_email;
                            $phone = $query->notification_phone;
                            $global_noti = $query->global_noti;
                                
                            if($email == '' || $email == null && $phone == '' || $phone == null )
                            {
                                echo "<p style='padding-bottom: 10px;'>Please update Your EMAIL and Phone Information</p>";
                            }else{
                            ?>
                        <div class="inputType">

                            <label>Global Notifications (If disabled, all email and SMS notifications will pause. You will not lose your dashboard item preferences. You will still see the notifications on the notifications page) </label><br>
                            <?php //echo 'dddd'.print_r($subscription); ?>
                            <?php
                            if($global_noti=="true") { ?>
				    <div class="checkbox-type"<?php if (isset($subscription_expired) && !$subscription_expired) { ?> style="float:left"<?php } ?>>
                            <input type="checkbox" <?php if ($subscription_expired < 1) { ?>disabled="disabled"<?php } else { ?>checked<?php } ?> class="email_notifications" id="email_notifications<?php echo $query->ID; ?>" onclick="globalcheck(<?php echo$query->ID; ?>)" name="switch<?php echo $query->ID; ?>" value="switch<?php echo $query->ID; ?>" >
                                <label for="email_notifications<?php echo $query->ID; ?>" class="checkb"></label>
                            </div>
                            <?php } else { ?>
			    <div class="checkbox-type" <?php if (isset($subscription_expired) && !$subscription_expired) { ?>style="float:left" <?php } ?>>
                                <input type="checkbox" <?php if ($subscription_expired < 1) { ?>disabled="disabled"<?php } ?> class="email_notifications" id="email_notifications<?php echo $query->ID; ?>" onclick="globalcheck(<?php echo$query->ID; ?>)" name="switch<?php echo$query->ID; ?>" value="switch<?php echo $query->ID; ?>" >
                                <label for="email_notifications<?php echo $query->ID; ?>" class="checkb"></label>
                            </div>
			    <?php  } ?>
			    <?php if (isset($subscription_expired) && !$subscription_expired) { ?>
        <label style="font-size: 0.9em; padding-left:10px;">
        Your free trial has ended. Notifications and status updates have been disabled. Please <a href="<?php echo site_url('settings/membership_account')?>">choose</a> a plan to continue tracking ASINS.
        </label>
        <?php } ?>
                        </div>
                       
                        <?php } ?>
                        <div class="inputType">
                            <label>Phone Number</label>
                            <input type="text" class="" id="sl_settings_phone_number" name="sl_settings_phone_number" placeholder="Phone Number" value="<?php echo $phone; ?>"/>
                        </div>
                        <div class="inputType">
                            <label>Email</label>
                            <input type="email" class="" id="sl_settings_email" name="sl_settings_email" placeholder="Email" value="<?php echo $email; ?>"/>
                        </div>
                        <!-- <div class="inputType">
                            <label>Location</label>
                            <?php
                            $location = $this->user->GetInfo('users', 'location', $_SESSION['uid']);
                            $timezone = $this->user->GetInfo('users', 'timezone', $_SESSION['uid']);
                            ?>
                            <select id="sl_settings_location" name="sl_settings_location" placeholder="Location">
                                <option value="">No selected</option>
                                <?php
                                $states = [
                                    'Alabama',
                                    'Alaska',
                                    'Arizona',
                                    'Arkansas',
                                    'California',
                                    'Colorado',
                                    'Connecticut',
                                    'Delaware',
                                    'Florida',
                                    'Georgia',
                                    'Hawaii',
                                    'Idaho',
                                    'Illinois',
                                    'Indiana',
                                    'Iowa',
                                    'Kansas',
                                    'Kentucky',
                                    'Louisiana',
                                    'Maine',
                                    'Maryland',
                                    'Massachusetts',
                                    'Michigan',
                                    'Minnesota',
                                    'Mississippi',
                                    'Missouri',
                                    'Montana',
                                    'Nebraska',
                                    'Nevada',
                                    'New Hampshire',
                                    'New Jersey',
                                    'New Mexico',
                                    'New York',
                                    'North Carolina',
                                    'North Dakota',
                                    'Ohio',
                                    'Oklahoma',
                                    'Oregon',
                                    'Pennsylvania',
                                    'Rhode Island',
                                    'South Carolina',
                                    'South Dakota',
                                    'Tennessee',
                                    'Texas',
                                    'Utah',
                                    'Vermont',
                                    'Virginia',
                                    'Washington',
                                    'West Virginia',
                                    'Wisconsin',
                                    'Wyoming'
                                ];
                                foreach ($states as $key => $value){
                                    if($location == $value) {
                                        echo "<option value='$value' selected>$value </option>";
                                    }else {
                                        echo "<option value='$value'>$value </option>";
                                    }

                                    ?>
                                <?php } ?>
                            </select>
                        </div> -->
                        <div class="inputType">
                            <label>Time Zone</label>
                            <select name="sl_settings_timezone" id="sl_settings_timezone">
                                <option value="est" <?php if($timezone == 'est') { echo "selected";}?>> EST Time</option>
                                <option value="central" <?php if($timezone == 'central') { echo "selected";}?>> Central Time</option>
                                <option value="mountain" <?php if($timezone == 'mountain') { echo "selected";}?>> Mountain Time</option>
                                <option value="pacific" <?php if($timezone == 'pacific') { echo "selected";}?>> Pacific Time</option>
                                <option value="alaska" <?php if($timezone == 'alaska') { echo "selected";}?>> Alaska Time</option>
                                <option value="hawaii" <?php if($timezone == 'hawaii') { echo "selected";}?>> Hawaii Time</option>
                            </select>
                        </div>
                        <div class="bottomProfilePicForm">
                            <input type="submit" class="btn btn-embossed btn-success primarycolorbtn" value="Update Information" />
                        </div>
                        
                        <br />
                    </div>
                    <?php } ?>
                    <!-- Individual Notifications -->
                    <!-- <form action="" method="post" enctype="multipart/form-data">
                    <div class="changePasswordHolder">
                        <div class="bottomProfilePicForm" style="margin-top: 20px;">
                            <input type="submit" class="btn btn-embossed btn-success mainSub" style="display: none;" value="Update Information" />
                            <div class="lineUpSelectors pull-right">
                                <div class="selectMod pull-left">
                                    <h3 class="text-center" style="font-size: 1.2em;">All</h3>
                                    <div class="c-hold verticle-middle text-center">
                                        <input type='checkbox' value='' onchange="checkAll(this)" id='checkboxall2' data-c="no"/>
                                        <label for='checkboxall2' data-for="checkboxall2" class='cb-label checkboxall2'></label>
                                    </div>
                                </div>
                                <div class="selectMod pull-right" style="margin-right: 5px;">
                                    <h3 class="text-center" style="font-size: 1.2em;">All</h3>
                                    <div class="c-hold verticle-middle text-center">
                                        <input type='checkbox' value='' onchange="" id='checkboxall1' data-c="no"/>
                                        <label for='checkboxall1' data-for="checkboxall1" class='cb-label checkboxall1'></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <table class="mainTable table table-striped table-bordered table-hover individual-item-report dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                            <thead>
                            <tr role="row">
                                <th class="text-center verticle-middle" data-orderable="false" rowspan="1" colspan="1" aria-label="Image" style="width: 53px;">
                                    <div>Image</div>
                                </th>
                                <th class="text-center verticle-middle sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Title: activate to sort column ascending" style="width: 424px;">
                                    Item Title
                                </th>
                                <th class="text-center verticle-middle sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="ASIN: activate to sort column ascending" style="width: 61px;">
                                    ASIN
                                </th>
                                <th class="text-center verticle-middle sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Report: activate to sort column ascending" style="width: 36px;">
                                    Email
                                </th>
                                <th class="text-center verticle-middle sorting" data-orderable="false" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Report: activate to sort column ascending" style="width: 36px;">
                                    Text
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $query = $this->db->query("SELECT * FROM amaz_aug where user_id='$user_id' ")->result();
                            //print_r($query);
                            foreach ($query as $q) {
                            ?>
                            <tr role="row" class="odd">
                                <td class="text-center vartical-middle">
                                <?php echo "<img src='".$q->image. "' style='height:50px;width:60px;'/>"?>
                                </td>
                                <td class="text-center verticle-middle" title='<?php /*echo $first_title;*/ ?>'>
                                    <?php print_r($q->title_name); ?>
                                </td>
                                <td class="text-center verticle-middle">
                                    <?php print_r($q->asin);?>
                                </td>
                                <th class="text-center c-hold verticle-middle">
                                    <input type='checkbox' value='' id='checkboxe2' />
                                    <label for='checkboxe2' data-for="checkboxe2"  class='cb-label cc2'></label>
                                </th>
                                <th class="text-center c-hold verticle-middle">
                                    <input type='checkbox' value='' id='checkboxt2' />
                                    <label for='checkboxt2' data-for="checkboxt2" class='cb-label cc'></label>
                                </th>
                            </tr><br>

                            <?php } ?>
                            </tbody>
                        </table><br />

                    </div>
                    </form> -->
                </div>
            </div>
        </form>
        </div>
    </div>
</div>
<script type="text/javascript">
     function checkAll(ele) {
     var checkboxes = document.getElementsByTagName('input');
     if (ele.checked) {
         for (var i = 0; i < checkboxes.length; i++) {
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = true;
             }
         }
     } else {
         for (var i = 0; i < checkboxes.length; i++) {
             console.log(i)
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = false;
             }
         }
     }
 }
</script>
<script type="text/javascript">
     function checkAll1(ele) {
     var checkboxes = document.getElementsByTagName('input');
     if (ele.checked) {
         for (var i = 0; i < checkboxes.length; i++) {
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = true;
             }
         }
     } else {
         for (var i = 0; i < checkboxes.length; i++) {
             console.log(i)
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = false;
             }
         }
     }
 }
</script>
<script type="text/javascript">
////////////////----------Global Notification-----------------/////////
  function globalcheck(notiget){
            console.log(notiget);
            
            var chck = document.getElementById('email_notifications'+notiget).checked;
            console.log(chck);
            var url_link;
            url_link = '<?php echo base_url(); ?>Dashboard/globalinsert/'+notiget+'/'+chck;
            $.ajax({
                url:url_link,
                success:function(res5){
                    console.log(res5);
                    
                }
            })
        }
</script>

