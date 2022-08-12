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
        <div class="settingsMainCont col-lg-9 col-sm-12 pull-right">
            <div class="innerCont card card-default clearfix">
                <div class="topHeader col-lg-12">
                    <h3>Amazon API settings</h3>
                </div>
                <div class="bottomContent col-lg-12">
                    <!-- FOR CHANGING PASSWORDS -->
                    <div class="changePasswordHolder">
                        <?php echo form_open('settings/amazon_api_update', array('id' => 'settings_amazon_api', 'action' => '')); ?>
                            <div class="inputType">
                            <label>Curent Connection</label>
                            <?php
                            // Lets get the info from the 'amazonAPI' table
                            $db = $this->database('pdo', true)->conn_id;

                            $query = $db->prepare("SELECT * FROM amazonapi WHERE UserID=:uid");
                            $query->execute(array(':uid' => $_SESSION['uid']));

                            if($query->rowCount() == 1)
                            {
                                while($fetch = $query->fetch(PDO::FETCH_ASSOC))
                                {
                                    $id = $fetch['id'];
                                    $UserID = $fetch['UserID'];
                                    $SellerID = $fetch['SellerID'];
                                    $MarketPlaceID = $fetch['MarketPlaceID'];
                                    $AssociateTabID = $fetch['AssociateTagID'];
                                    $AccessKeyID = $fetch['AccessKeyID'];
                                    $SecretKey = $fetch['SecretKey'];
                                    $DeveloperAccountNumber = $fetch['DeveloperAccountNumber'];
                                    $connection = $fetch['connection'];

                                    // Now that we have the info we can plug it into the input boxes
                                }
                            } else {
                                $id ='';
                                $UserID = "";
                                $SellerID = "";
                                $MarketPlaceID = "";
                                $AssociateTabID = "";
                                $AccessKeyID = "";
                                $SecretKey = "";
                                $DeveloperAccountNumber = "";
                                $connection = "";
                            }

                            ?>
                            <div class="checkbox-type">
                                <input type="checkbox" data-checked="<?php echo $connection; ?>" id="amazon_connection" <?php if($connection == "on"){?> checked <?php } ?>/>
                                <label for="amazon_connection" class="checkb2"></label>
                            </div>
                        </div>
                        <div class="inputType">
                            <label>Seller Id</label>
                            <input type="text" class="" id="sl_settings_seller_id" name="sl_settings_seller_id" placeholder="Seller Id" value="<?php echo $SellerID; ?>"/>
                        </div>
                        <div class="inputType">
                            <label>Marketplace Id</label>
                            <input type="text" class="" id="sl_settings_marketplace_id" name="sl_settings_marketplace_id" placeholder="Marketplace Id" value="<?php echo $MarketPlaceID; ?>"/>
                        </div>
                        <div class="inputType">
                            <label>Associate Tag</label>
                            <input type="text" class="" id="sl_settings_associate_tag" name="sl_settings_associate_tag" placeholder="Associate Tag" value="<?php echo $AssociateTabID; ?>"/>
                        </div>
                        <div class="inputType">
                            <label>Developer Account Number</label>
                            <input type="text" class="" id="sl_settings_developer_account_number" name="sl_settings_developer_account_number" placeholder="Developer Account Number" value="<?php echo $DeveloperAccountNumber; ?>"/>
                        </div>
                        <div class="inputType">
                            <label>Access Key Id</label>
                            <input type="text" class="" id="sl_settings_access_key_id" name="sl_settings_access_key_id" placeholder="Access Key Id" value="<?php echo $AccessKeyID; ?>"/>
                        </div>
                        <div class="inputType">
                            <label>Secret Key</label>
                            <input type="text" class="" id="sl_settings_secret_key" name="sl_settings_secret_key" placeholder="Secret Key" value="<?php echo $SecretKey; ?>"/>
                        </div>
                        <div class="bottomProfilePicForm">
                            <input type="submit" class="btn btn-embossed btn-success primarycolorbtn" value="Update Information" />
                        </div>
                        <?php echo '</form>';  ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>