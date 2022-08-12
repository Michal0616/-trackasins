
<table
                        class="mainTable table table-striped table-bordered table-hover individual-item-report dataTable main-table"
                        id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info" style="width:100%" >
                        <thead>
                        <tr role="row" style='margin-top: 15px;'>
                            <th class="text-center a verticle-middle sorting_disabled" data-orderable="false"
                                rowspan="1" colspan="1" aria-label="Image" style="width: 53px;">
                                <div>Image</div>
                            </th>
                            <th class="text-center a t verticle-middle sorting" tabindex="0"
                                aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                aria-label="Title: activate to sort column ascending" style="width: 200px;">
                                Item Title
                            </th>
                            <th class="text-center a verticle-middle sorting" tabindex="0"
                                aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                aria-label="ASIN: activate to sort column ascending" style="width: 110px">
                                ASIN
                            </th>
                            <th class="text-center a t-responsive verticle-middle sorting" tabindex="0"
                                aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                aria-label="Report: activate to sort column ascending" >
                                Are you in stock?
                            </th>
                            <th class="text-center a t-responsive verticle-middle sorting" tabindex="0"
                                aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                aria-label="Report: activate to sort column ascending" >
                                Is Amazon in stock
                            </th>
                            <th class="text-center a t-responsive  verticle-middle sorting" tabindex="0"
                                aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                aria-label="Report: activate to sort column ascending" >
                                Back In Stock Tracking
                            </th>
                            <th class="text-center a t-responsive  verticle-middle sorting" tabindex="0"
                                aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                aria-label="Report: activate to sort column ascending" >
                                Out of Stock Tracking
                            </th>
                            <th class="text-center a t-responsive  verticle-middle sorting" tabindex="0"
                                aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                aria-label="Report: activate to sort column ascending" >
                                Email Notification
                            </th>
                            <th class="text-center a t-responsive  verticle-middle sorting" tabindex="0"
                                aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                aria-label="Report: activate to sort column ascending" >
                                SMS Notification
                            </th>

                            <th class="text-center a verticle-middle sorting menuListOpen dropbox "
                                aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                aria-label="Report: activate to sort column ascending" style="width:100px;">
                                <div class="dropdown-toggle" data-toggle="dropdown">
                                    Bulk Action<br/>
                                    <span class="car" id="bulkActionCar">
                                        <i class="fa fa-caret-down" aria-hidden="true"></i>
                                    </span>
                                </div>
                                <ul class="dropdown-menu dropdown-menu-right drop">
                                    <li><a href="javascript:void(0)" onclick="onSelectAll()">Select All/Deselect All</a></li>
                                    <li><a href="javascript:void(0)" onclick="onChangeTurnOnOff('stock_on')">Turn Out of Stock Tracking On</a></li>
                                    <li><a href="javascript:void(0)" onclick="onChangeTurnOnOff('stock_off')">Turn Out of Stock Tracking Off</a></li>
                                    <li><a href="javascript:void(0)" onclick="onChangeTurnOnOff('back_stock_on')">Turn Back in Stock Tracking On</a></li>
                                    <li><a href="javascript:void(0)" onclick="onChangeTurnOnOff('back_stock_off')">Turn Back in Stock Tracking Off</a></li>
                                    <li><a href="javascript:void(0)" onclick="onChangeTurnOnOff('email_on')">Turn Email Notifications On</a></li>
                                    <li><a href="javascript:void(0)" onclick="onChangeTurnOnOff('email_off')">Turn Email Notifications Off</a></li>
                                    <li><a href="javascript:void(0)" onclick="onChangeTurnOnOff('sms_on')">Turn SMS Notifications On </a></li>
                                    <li><a href="javascript:void(0)" onclick="onChangeTurnOnOff('sms_off')">Turn SMS Notifications Off</a></li>
<!--                                    <li><a href="javascript:void(0)" style="text-align:center" data-toggle="modal" data-target="#deleteAsinsModal">Delete</a></li>-->
                                    <li style="text-align:center;">
                                        <button style=" background: none;border: none;font-weight: 200; padding: 3px 20px;" name="delete" data-toggle="modal" data-target="#deleteAsinsModal">Delete</button>
                                    </li>
                                            <!--                                    <li></li>-->
                                    <!-- <form action="" method="post" enctype="multipart/form-data"> -->
                                    <!-- <button type="submit" class="btn" name="delete">Delete</button> -->
<!--                                    <center>-->
<!--                                        <li>-->
<!--                                            <button style=" background: none;border: none;font-weight: 200;" name="delete" onclick="toggle()">Delete-->
<!--                                            </button>-->
<!--                                        </li>-->
<!--                                    </center>-->
                                    <!-- </form> -->
                                </ul>
                            </th>
                        </tr>
                        </thead>

                        <tbody id="dashboardTbody">
                        <!-- <form action="" method="post" enctype="multipart/form-data"> -->
                        <?php

                        $user_id = $this->session->userdata('user_id');
                        /*$query = $this->db->query("SELECT * FROM amaz_aug WHERE `user_id`='$user_id' group by asin order by status ASC ")->result();*/
                        $query = $this->db->query("SELECT * FROM amaz_aug WHERE `user_id`='$user_id' ORDER BY tracking DESC, amznotseller DESC , sellerstock ASC ")->result();
                        //echo '<pre>'; print_r($query);echo '</pre>';
                         foreach ($query as $query) {
 
                             ?>
                             <tr role="row" class="odd scrape-row">
                                 <!--start IMAGE-->
                                 <td class="text-center vertical-middle star-wrapper" style="position: relative">
 
                                     <?php
                                     if ($query->tracking == 1 || $query->stock_noti == 'true' || $query->stock_noti == 1) {
                                         if (($query->amznotseller == "2") && ($query->sellerstock == "1")) {
                                             ?>
                                             <!--                                        <span style="color:green; font-size:20px" class="product-star"><i class="fa fa-circle" aria-hidden="true"></i></span>-->
                                             <div class="green-right-triangle"></div>
                                         <?php } else {
                                             if (($query->amznotseller == "2") && ($query->sellerstock == "0")) { ?>
                                                 <!--                                        <span style="color:red; font-size:20px" class="product-star"><i class="fa fa-circle" aria-hidden="true"></i></span>-->
                                                 <div class="red-right-triangle"></div>
                                                 <?php
                                             }
                                         }
                                     }
                                     ?>
                                     <?php if($query->image != ''){ ?>
                                     <a href="<?php echo $query->image; ?>" data-fancybox="images" data-caption="<?php echo $query->title_name; ?>" class="fancybox">
                                         <?php echo '<img src="' . $query->image . '" class ="img-thumbnail"  style="height:70px;border:0px"/>' ?>
                                     </a>
                                     <?php } ?>
                                 </td>
                                 <!--END IMAGE-->
                                 <!--start TITLE NAME-->
                                 <td class="text-center vertical-middle" title='<?php echo $query->title_name; ?>'>
                                    <a style="" target="_blank"
                                       href="http://amazon.com/dp/<?php echo $query->asin; ?>"><?php echo $query->title_name; ?></a>
                                </td>
                                <!--END TITLE NAME-->
                                <!--start ASIN-->
                                <td class="text-center vertical-middle">
                                    <a style="" target="_blank"
                                       href="http://amazon.com/dp/<?php echo $query->asin; ?>"><?php echo $query->asin; ?></a>
                                </td>
                                <!--END ASIN-->
                                <?php if ($query->stock_noti != 1 && $query->stock_noti != "true" && $query->tracking != "1") { ?>
                                    <!--start SELLERSTOCK-->
                                    <td class="text-center b red verticle-middle">
                                        <span style="color:#aaa; font-size:14px;" id="stock_label_<?php echo $query->id; ?>">Turn tracking on<br> to see stock status</span>
                                    </td>
                                    <!--END SELLERSTOCK-->

                                    <!--start AMZNOTSELLER-->
                                    <td class="text-center b red verticle-middle">
                                        <span style="color:#aaa; font-size:14px;" id="amznotseller_label_<?php echo $query->id; ?>">Turn tracking on<br> to see stock status</span>
                                    </td>
                                    <!--END AMZNOTSELLER-->
                                <?php } else if (($query->stock_noti == 1 || $query->stock_noti == "true") && $query->tracking == "1" && $query->status == 6 ) { ?>
                                    <!--start SELLERSTOCK-->
                                    <td class="text-center b red verticle-middle">
                                        <span style="color:#aaa; font-size:14px;" id="stock_label_<?php echo $query->id; ?>">Item no longer exists <br>on Amazon</span>
                                    </td>
                                    <!--END SELLERSTOCK-->

                                    <!--start AMZNOTSELLER-->
                                    <td class="text-center b red verticle-middle">
                                        <span style="color:#aaa; font-size:14px;" id="amznotseller_label_<?php echo $query->id; ?>">Item no longer exists<br> on Amazon</span>
                                    </td>
                                    <!--END AMZNOTSELLER-->
                                <?php } else { ?>
                                    <!--start SELLERSTOCK-->
                                    <?php if (is_null($query->sellerstock) || $query->sellerstock == '') { ?>
                                        <td class="text-center b red verticle-middle">
                                        <span style="color:#aaa; font-size:14px;" id="stock_label_<?php echo $query->id; ?>">Being processed! <br> Will be updated soon</span>
                                        </td>
                                    <?php } else if (($query->sellerstock == "1")) {
                                        if (($query->amznotseller == "2")) { ?>
                                            <td class="text-center b red verticle-middle">
                                                <span style="color:green; font-size:25px;" id="stock_label_<?php echo $query->id; ?>">Yes!</span>
                                            </td>
                                        <?php } else { ?>
                                            <td class="text-center b red verticle-middle">
                                                <!--span style="color:black; font-size:25px;" id="stock_label_<?php //echo $query->id; ?>">Yes</span-->
                                                <span style="color:black; font-size:25px;" id="stock_label_<?php echo $query->id; ?>">No</span>
                                            </td>
                                        <?php }
                                    } else if (($query->sellerstock == "0")) {
                                        if (($query->amznotseller == "2")) { ?>
                                            <td class="text-center b red verticle-middle">
                                                <span style="color:red; font-size:25px;" id="stock_label_<?php echo $query->id; ?>">No!</span>
                                            </td>
                                        <?php } else { ?>
                                            <td class="text-center b red verticle-middle">
                                                <span style="color:black; font-size:25px;" id="stock_label_<?php echo $query->id; ?>">No</span>
                                            </td>
                                        <?php }
                                    } ?>
                                    <!--END SELLERSTOCK-->

                                    <!--start AMZNOTSELLER-->
                                        <?php //if (($query->amznotseller == "1")) {
                                        if (($query->sellerstock == "0")) {
                                                if (($query->amznotseller == "2")) {
                                         ?>
                                        <td class="text-center b red verticle-middle">
                                            <span style="color:green; font-size:25px;" id="amznotseller_label_<?php echo $query->id; ?>">Yes!</span>
                                        </td>
                                    <?php }else{
                                    ?>
                                         <td class="text-center b red verticle-middle">
                                                <span style="color:black; font-size:25px;" id="amznotseller_label_<?php echo $query->id; ?>">Yes</span>
                                            </td>
                                        <?php }} ?>
                                    <?php //if (($query->amznotseller == "0")) {
                                        if (($query->sellerstock == "1")) { 
                                            if (($query->amznotseller == "2")) {
                                                ?>
                                                <td class="text-center b red verticle-middle">
                                                        <span style="color:black; font-size:25px;" id="amznotseller_label_<?php echo $query->id; ?>">Yes</span>
                                                    </td>
                                                 <?php }else{
                                                ?>
                                                <td class="text-center b red verticle-middle">
                                                    <span style="color:black; font-size:25px;" id="amznotseller_label_<?php echo $query->id; ?>">No</span>
                                                </td>
                                            <?php }} ?>
                                            <?php if (is_null($query->sellerstock) || $query->sellerstock == '') { ?>
                                                <td class="text-center b red verticle-middle">
                                                    <span style="color:#aaa; font-size:14px;" id="amznotseller_label_<?php echo $query->id; ?>">Being processed! <br> Will be updated soon</span>
                                                </td>
                                            <?php } ?>
                                            <!--END AMZNOTSELLER-->
        
                                        <?php } ?>
                                <!--start STOCK NOTIFICATIION-->
                                <td class="vertical-middle cb text-center">
                                    <?php if ($query->stock_noti == "true") { ?>

                                        <label class="switch">
                                            <input type="checkbox" data-role="flipswitch"
                                                   onclick="stockcheck(<?php echo $query->id; ?>, this)"
                                                   name="switch<?php echo $query->id; ?>"
                                                   id="switchstock<?php echo $query->id; ?>"
                                                   value="switch<?php echo $query->id; ?>" checked>
                                            <div class="slider round"></div>
                                        </label>


                                    <?php } else { ?>

                                        <label class="switch">
                                            <input type="checkbox" data-role="flipswitch"
                                                   onclick="stockcheck(<?php echo $query->id; ?>, this)"
                                                   name="switch<?php echo $query->id; ?>"
                                                   id="switchstock<?php echo $query->id; ?>"
                                                   value="switch<?php echo $query->id; ?>">
                                            <div class="slider round"></div>
                                        </label>

                                    <?php } ?>
                                </td>

                                <!--start TRACKING-->
                                <td class="vertical-middle cb text-center">
                                    <?php if ($query->tracking == "1") { ?>

                                        <label class="switch">
                                            <input type="checkbox" data-role="flipswitch"
                                                   onclick="chackUncheck(<?php echo $query->id; ?>, this)"
                                                   name="switch<?php echo $query->id; ?>"
                                                   id="switch<?php echo $query->id; ?>" value="true" checked>
                                            <div class="slider round"></div>
                                        </label>


                                    <?php } else { ?>

                                        <label class="switch">
                                            <input type="checkbox" data-role="flipswitch"
                                                   onclick="chackUncheck(<?php echo $query->id; ?>, this)"
                                                   name="switch<?php echo $query->id; ?>"
                                                   id="switch<?php echo $query->id; ?>"
                                                   value="switch<?php echo $query->id; ?>">
                                            <div class="slider round"></div>
                                        </label>

                                    <?php } ?>
                                </td>
                                <!--END TRACKING-->
                                <!--start EMAIL NOTIFICATIION-->
                                <td class="vertical-middle cb text-center">
                                    <?php if($query->stock_noti != "true" && $query->tracking != "1"){ ?>
                                        <label class="switch">
                                            <input type="checkbox" data-role="flipswitch"
                                                    onclick="emailcheck(<?php echo $query->id; ?>)"
                                                    name="switch<?php echo $query->id; ?>"
                                                    id="switchid<?php echo $query->id; ?>"
                                                    value="switchEmail<?php echo $query->id; ?>" disabled>
                                            <div class="slider round"></div>
                                        </label>
                                    <?php } else { ?>
                                        <?php if ($query->email_noti == "true") { ?>
                                        <label class="switch">
                                            <input type="checkbox" data-role="flipswitch"
                                                    onclick="emailcheck(<?php echo $query->id; ?>)"
                                                    name="switch<?php echo $query->id; ?>"
                                                    id="switchid<?php echo $query->id; ?>"
                                                    value="switchEmail<?php echo $query->id; ?>" checked>
                                            <div class="slider round"></div>
                                        </label>


                                        <?php } else { ?>

                                        <label class="switch">
                                            <input type="checkbox" data-role="flipswitch"
                                                    onclick="emailcheck(<?php echo $query->id; ?>)"
                                                    name="switch<?php echo $query->id; ?>"
                                                    id="switchid<?php echo $query->id; ?>"
                                                    value="switchEmail<?php echo $query->id; ?>">
                                            <div class="slider round"></div>
                                        </label>

                                        <?php } ?>
                                    <?php } ?>
                                </td>
                                <!--END EMAIL NOTIFICATIION-->
                                <!--start PHONE NOTIFICATIION-->
                                <td class="vertical-middle cb text-center">
                                    <?php if($query->stock_noti != "true" && $query->tracking != "1"){ ?>
                                        <label class="switch">
                                                <input type="checkbox" data-role="flipswitch"
                                                    onclick="phonecheck(<?php echo $query->id; ?>)"
                                                    name="switch<?php echo $query->id; ?>"
                                                    id="switchphone<?php echo $query->id; ?>"
                                                    value="switch<?php echo $query->id; ?>" disabled>
                                                <div class="slider round"></div>
                                            </label>
                                    <?php } else { ?>
                                        <?php if ($query->phone_noti == "true") { ?>

                                            <label class="switch">
                                                <input type="checkbox" data-role="flipswitch"
                                                    onclick="phonecheck(<?php echo $query->id; ?>)"
                                                    name="switch<?php echo $query->id; ?>"
                                                    id="switchphone<?php echo $query->id; ?>"
                                                    value="switch<?php echo $query->id; ?>" checked>
                                                <div class="slider round"></div>
                                            </label>


                                        <?php } else { ?>

                                            <label class="switch">
                                                <input type="checkbox" data-role="flipswitch"
                                                    onclick="phonecheck(<?php echo $query->id; ?>)"
                                                    name="switch<?php echo $query->id; ?>"
                                                    id="switchphone<?php echo $query->id; ?>"
                                                    value="switch<?php echo $query->id; ?>">
                                                <div class="slider round"></div>
                                            </label>

                                        <?php } ?>
                                    <?php } ?>
                                    <!--END PHONE NOTIFICATIION-->
                                    <!--start BULK ACTION-->
                                <td class="text-center c-hold verticle-middle" id="checkes">
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <input type='checkbox' value="<?php echo $query->id; ?>" name="checkbulk1[]"
                                               class="check"/>
                                        <label for='checkbox1' data-for="checkbox1" class='cb-label'></label>
                                    </form>
                                </td>
                                <!--END BULK ACTION-->
                            </tr>


                        <?php  }?>
                        <!--  </form> -->
                        </tbody>

                    </table>
                    <script>
                    dataTableShow();
                    </script>
