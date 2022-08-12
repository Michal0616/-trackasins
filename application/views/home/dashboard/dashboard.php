<div class="dashHeadline headline headline-site-color">
    <div class="innerholder container">
        <div class="profile_Pic_left">
            <img src="<?php echo base_url('assets2/user_data/' . $this->user->GetInfo('users', 'profile_pic', $_SESSION['uid'])); ?>" />
        </div>
        <div class="informationTextRight text-center col-lg-12">
            <h3>Welcome <?php echo $this->user->GetInfo('users', 'fname', $_SESSION['uid']); ?></h3>
        </div>
    </div>
</div>
<div class="container mainContainer">
    <div class="leftmajor col-lg-10 clearfix">
        <div class="barOfInfo clearfix">
            <div class="wideBar first clearfix col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-left: 0px;">
                <div class="inner clearfix">
                    <div class="textMain col-lg-10 text-center verticle-middle">
                        <h3>ASINs currently out of stock by Amazon</h3>
                    </div>
                    <div class="numberMain col-lg-2">
                        <h3>5</h3>
                    </div>
                </div>
            </div>

            <div class="wideBar second clearfix col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-right: 0px;">
                <div class='inner clearfix'>
                    <div class="textMain col-lg-10 text-center verticle-middle">
                        <h3>Sales in the past 30 days from items</h3>
                    </div>
                    <div class="numberMain col-lg-2">
                        <h3>$0</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="topSearchAsins cont">
            <div class="topBox text-left">
                <h3>Search for ASINS</h3>
            </div>
            <div class="bottomContent">
                <div class="formTop clearfix">
                    <?php echo form_open('dashboard/search/searchProcess', array('id' => 'dashSearchForm', 'action' => '')); ?>
                        <div class="inputType col-lg-10 col-md-10 col-sm-10 col-xs-10" style="margin-bottom: 0px;">
                            <input type="text" placeholder="Search for ASINS" style="border-top-right-radius: 0px;border-bottom-right-radius: 0px;"/>
                        </div>
                        <div class="inputType col-lg-2 col-md-2 col-sm-2 col-xs-2" style="margin-bottom: 0px;">
                            <input type='submit' class='btn btn-embossed btn-primary btn-wide' style="border-top-left-radius: 0px;border-bottom-left-radius: 0px;background: #b65f2b;" value='Search' />
                        </div>
                    <?php echo '</form>'; ?>
                </div>

            </div>
        </div><br />
        <!--<div class="topSearchAsins cont">
            <div class="topBox text-left">
                <h3>Recent Activity</h3>
            </div>
            <div class="bottomContent">
                <div class="innerCont">
                    <ul class="activityHolder">
                        <li class="clearfix">
                            <div class="col1 col-lg-10">
                                <div class="contm">
                                    <div class="cont-col1">
                                        <div class="label label-sm label-info">
                                            <i class="fa fa-check"></i>
                                        </div>
                                    </div>
                                    <div class="cont-col2">
                                        <div class="desc">You have 4 pending tasks.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col2 col-lg-2">
                                <div class="date"> Just now </div>
                            </div>
                        </li>
                        <li class="clearfix">
                            <div class="col1 col-lg-10">
                                <div class="contm">
                                    <div class="cont-col1">
                                        <div class="label label-sm label-info" style="background: #F1C40F">
                                            <i class="fa fa-bell-o"></i>
                                        </div>
                                    </div>
                                    <div class="cont-col2">
                                        <div class="desc">2 Asins have went out of stock</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col2 col-lg-2">
                                <div class="date"> Just now </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div><br />-->
        <div class="topSearchAsins cont">
            <div class="topBox text-left">
                <h3>Items List</h3>
            </div>
            <div class="bottomContent">
                <div class="listHolder">
                    <table class="mainTable table table-striped table-bordered table-hover individual-item-report dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                        <thead>
                        <tr role="row" style='margin-top: 15px;'>
                            <th class="text-center n verticle-middle dropbox" data-orderable="false" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Report: activate to sort column ascending" style="width: 53px;">
                            </th>
                            <th class="text-center a verticle-middle sorting_disabled" data-orderable="false" rowspan="1" colspan="1" aria-label="Image" style="width: 53px;">
                                <div>Image</div>
                            </th>
                            <th class="text-center a t verticle-middle sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Title: activate to sort column ascending" style="width: 424px;">
                                Item Title
                            </th>
                            <th class="text-center a verticle-middle sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="ASIN: activate to sort column ascending" style="width: 61px;">
                                ASIN
                            </th>
                            <th class="text-center t-responsive a verticle-middle sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Report: activate to sort column ascending" style="width: 218px;">
                                Tracking
                            </th>
                            <th class="text-center a t-responsive verticle-middle sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Report: activate to sort column ascending" style="width: 218px;">
                                Is Amazon in stock -- 
                            </th>
                            <th class="text-center a t-responsive verticle-middle sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Report: activate to sort column ascending" style="width: 218px;">
                                Are you in stock?
                            </th>
                            <th class="text-center a verticle-middle menuListOpen dropbox" data-orderable="false" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Report: activate to sort column ascending" style="width: 48px;">
                                <div class="dropdown-toggle" data-toggle="dropdown">Bulk Action<br/><span style="color: #ddd" class="car"><i class="fa fa-caret-down" aria-hidden="true"></i></span></div>
                                <ul class="dropdown-menu drop">
                                    <li><a href="#">Select All</a></li>
                                    <li><a href="#">Turn tracking on</a></li>
                                    <li><a href="#">Turn tracking off</a></li>
                                    <li><a href="#">Delete</a></li>
                                </ul>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        // So this is how we can make the text smnller. This should work fine
                        $first_title = "Acer H277HU kmipuz 27-Inch IPS WQHD 2560 x 1440 Display, USB 3.1 Type-C port, HDMI, DP, 2 x 3w speakers";
                        $second_title = "Acer K272HUL Cbmidp Black 27\" WQHD HDMI DisplayPort Widescreen LED Backlight LCD Monitor Built-in Speakers";

                        if(strlen($first_title) > 65)
                        {
                            $first_titlec = substr($first_title, 0, 55) . "...";
                        }

                        if(strlen($second_title) > 65)
                        {
                            $second_titlec = substr($second_title, 0, 55) . "...";
                        }
                        ?>
                        <tr role="row" class="odd">
                            <td class="text-center n st verticle-middle" style="">
                                <span style="color:red; font-size:25px;margin-left: -20px;color: #1abc9c"><i class="fa fa-star"></i></span>
                            </td>
                            <td class="text-center vartical-middle">
                                <a target="_blank" href="http://www.amazon.com/Acer-H277HU-kmipuz-27-Inch-speakers/dp/B01B64O3M4%3FSubscriptionId%3DAKIAIJEQX7SVEBFFDYEA%26tag%3Dwafi03-20%26linkCode%3Dxm2%26camp%3D2025%26creative%3D165953%26creativeASIN%3DB01B64O3M4"><img src="http://ecx.images-amazon.com/images/I/51A1VOtlVML._SL75_.jpg" alt=""></a>
                            </td>
                            <td class="text-center vartical-middle" title='<?php echo $first_title; ?>'>
                                <?php echo $first_titlec; ?>
                            </td>
                            <td class="text-center verticle-middle">
                                <a target="_blank" href="http://www.amazon.com/Acer-H277HU-kmipuz-27-Inch-speakers/dp/B01B64O3M4%3FSubscriptionId%3DAKIAIJEQX7SVEBFFDYEA%26tag%3Dwafi03-20%26linkCode%3Dxm2%26camp%3D2025%26creative%3D165953%26creativeASIN%3DB01B64O3M4">B01B64O3M4</a>
                            </td>
                            <th class="vertical-middle cb text-center">
                                <div class="checkbox-type">
                                    <input type="checkbox" data-checked="<?php echo $this->user->GetInfo('users', 'notifications', $_SESSION['uid']); ?>" id="email_notifications" <?php if($this->user->GetInfo('users', 'notifications', $_SESSION['uid']) == "yes"){?> checked <?php } ?>/>
                                    <label for="email_notifications" class="checkb" style="left: initial;"></label>
                                </div>
                            </th>
                            <th class="text-center b red verticle-middle">
                                No
                            </th>
                            <th class="text-center b red verticle-middle">
                                No
                            </th>
                            <th class="text-center c-hold verticle-middle">
                                <input type='checkbox' value='' id='checkbox1' class="check"/>
                                <label for='checkbox1' data-for="checkbox1" class='cb-label'></label>
                            </th>
                        </tr><br>
                        <tr role="row" class="even">
                            <td class="text-center n st verticle-middle" style="">
                                <span style="color:red; font-size:25px;margin-left: -20px;color: #e74c3c"><i class="fa fa-star"></i></span>
                            </td>
                            <td class="text-center vartical-middle">
                                <a target="_blank" href="http://www.amazon.com/Acer-K272HUL-DisplayPort-Widescreen-Backlight/dp/B018J0216S%3FSubscriptionId%3DAKIAIJEQX7SVEBFFDYEA%26tag%3Dwafi03-20%26linkCode%3Dxm2%26camp%3D2025%26creative%3D165953%26creativeASIN%3DB018J0216S"><img src="http://ecx.images-amazon.com/images/I/51HNYCJmZ-L._SL75_.jpg" alt=""></a>
                            </td>
                            <td class="text-center vartical-middle" title='<?php echo $second_title; ?>'>
                                <?php echo $second_titlec; ?>
                            </td>
                            <td class="text-center verticle-middle">
                                <a target="_blank" href="http://www.amazon.com/Acer-K272HUL-DisplayPort-Widescreen-Backlight/dp/B018J0216S%3FSubscriptionId%3DAKIAIJEQX7SVEBFFDYEA%26tag%3Dwafi03-20%26linkCode%3Dxm2%26camp%3D2025%26creative%3D165953%26creativeASIN%3DB018J0216S">B018J0216S</a>
                            </td>
                            <th class="text-center cb">
                                <div class="checkbox-type">
                                    <input type="checkbox" data-checked="<?php echo $this->user->GetInfo('users', 'notifications', $_SESSION['uid']); ?>" id="email_notifications2" <?php if($this->user->GetInfo('users', 'notifications', $_SESSION['uid']) == "yes"){?> checked <?php } ?>/>
                                    <label for="email_notifications2" class="checkb" style="left: initial;"></label>
                                </div>
                            </th>
                            <th class="text-center b green verticle-middle">
                                Yes
                            </th>
                            <th class="text-center b green verticle-middle">
                                Yes
                            </th>
                            <th class="text-center c-hold verticle-middle">
                                <input type='checkbox' value='' id='checkbox2' class="check"/>
                                <label for='checkbox2' data-for='checkbox2' class='cb-label'></label>
                            </th>
                        </tr>
                        </tbody>
                    </table>
                    <div class="loadMoreHolder text-center" style="padding-top: 15px;">
                        <button class="btn btn-wide btn-embossed btn-primary" style='background: #b65f2b'><i class="fa fa-arrow-down" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="rightSide col-lg-2 col-sm-12">
        <div class="longBox trial-left">
            <div class="topNumber">
                <h3 style="font-size: 1.5em;font-weight: 400;">Summary</h3>
            </div>
        </div><br />
        <div class="longBox trial-left">
            <div class="topNumber">
                <h3>25</h3>
            </div>
            <div class="bottomContent">
               <p class="text-center">Days left on your trial</p>
            </div>
        </div><br />
        <div class="longBox trial-left">
            <div class="topNumber">
                <h3>20</h3>
            </div>
            <div class="bottomContent">
                <p class="text-center">Items in your list</p>
            </div>
        </div><br />
        <div class="longBox trial-left">
            <div class="topNumber">
                <h3>12</h3>
            </div>
            <div class="bottomContent">
                <p class="text-center">Items currently tracking</p>
            </div>
        </div>
    </div>
</div><br /><br />
