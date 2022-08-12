<div class="reportsHeadline headline headline-site-color">
    <div class="container-fluid inner">
        <div class="topHeadline container text-center">
            <h3>Notifications</h3>
        </div>
    </div>
</div>
<div class="mainNotificationsContainer container">
    <div class="mainIndividualCont innerContainer col-lg-12" data-open="mainIndividualCont">
        <div class="topHeadPart">
            <h3>Item Notifications</h3>
        </div>
        <div class="bottomHolder">
            <table class="mainTable table table-striped table-bordered table-hover individual-item-report dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                <thead>
                <br />
                <tr role="row">
                    <th class="text-center verticle-middle sorting_disabled" data-orderable="false" rowspan="1" colspan="1" aria-label="Image" style="width: 53px;">
                        <div>Image</div>
                    </th>
                    <th class="text-center verticle-middle sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Title: activate to sort column ascending" style="width: 424px;">
                        Item Title
                    </th>
                    <th class="text-center verticle-middle sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="ASIN: activate to sort column ascending" style="width: 61px;">
                        ASIN
                    </th>
                    <th class="text-center verticle-middle sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Report: activate to sort column ascending" style="width: 218px;">
                        Is Amazon still out of stock
                    </th>
                    <th class="text-center verticle-middle sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Report: activate to sort column ascending" style="width: 218px;">
                        Are you in stock?
                    </th>
                    <!--<th class="text-center verticle-middle menuListOpen dropbox" data-orderable="false" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Report: activate to sort column ascending" style="width: 48px;">
                        <div class="dropdown-toggle" data-toggle="dropdown">Bulk Action<br/><span style="color: #ddd"><i class="fa fa-caret-down" aria-hidden="true"></i></span></div>
                        <ul class="dropdown-menu">
                            <li><a href="#">Select All</a></li>
                            <li><a href="#">Turn tracking on</a></li>
                            <li><a href="#">Turn tracking off</a></li>
                            <li><a href="#">Delete</a></li>
                        </ul>
                    </th>-->
                </tr>
                </thead>
                <tbody>
                <?php
                // So this is how we can make the text smnller. This should work fine
                $first_title = "Acer H277HU kmipuz 27-Inch IPS WQHD 2560 x 1440 Display, USB 3.1 Type-C port, HDMI, DP, 2 x 3w speakers";
                $second_title = "Acer K272HUL Cbmidp Black 27\" WQHD HDMI DisplayPort Widescreen LED Backlight LCD Monitor Built-in Speakers";

                if(strlen($first_title) > 75)
                {
                    $first_titlec = substr($first_title, 0, 75) . "...";
                }

                if(strlen($second_title) > 75)
                {
                    $second_titlec = substr($second_title, 0, 75) . "...";
                }
                ?>
                <tr role="row" class="odd">
                    <td class="text-center vartical-middle">
                        <a target="_blank" href="http://www.amazon.com/Acer-H277HU-kmipuz-27-Inch-speakers/dp/B01B64O3M4%3FSubscriptionId%3DAKIAIJEQX7SVEBFFDYEA%26tag%3Dwafi03-20%26linkCode%3Dxm2%26camp%3D2025%26creative%3D165953%26creativeASIN%3DB01B64O3M4"><img src="http://ecx.images-amazon.com/images/I/51A1VOtlVML._SL75_.jpg" alt=""></a>
                    </td>
                    <td class="text-center verticle-middle" title='<?php echo $first_title; ?>'>
                        <?php echo $first_titlec; ?>
                    </td>
                    <td class="text-center verticle-middle">
                        <a target="_blank" href="http://www.amazon.com/Acer-H277HU-kmipuz-27-Inch-speakers/dp/B01B64O3M4%3FSubscriptionId%3DAKIAIJEQX7SVEBFFDYEA%26tag%3Dwafi03-20%26linkCode%3Dxm2%26camp%3D2025%26creative%3D165953%26creativeASIN%3DB01B64O3M4">B01B64O3M4</a>
                    </td>
                    <th class="text-center b red verticle-middle">
                        No
                    </th>
                    <th class="text-center b red verticle-middle">
                        No
                    </th>
                    <!--<th class="text-center c-hold verticle-middle">
                        <input type='checkbox' value='' id='checkbox1' />
                        <label for='checkbox1' class='cb-label'></label>
                    </th>-->
                </tr>
                <tr>
					<td style='width: 100%;padding: 20px;' colspan='6'>
						<div>
							<ul class="activityHolder">
								<!-- 
<li class="clearfix">
									<div class="col1 col-lg-10">
										<div class="contm">
											<div class="cont-col1">
												<div class="label label-sm label-info">
													<i class="fa fa-check"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">Amazon ran out of stock at 2:59PM on 7/1/16</div>
											</div>
										</div>
									</div>
									<div class="col2 col-lg-2">
										<div class="date"> Just now </div>
									</div>
								</li>
 -->
								<li class="clearfix">
									<div class="col1 col-lg-11">
										<div class="contm">
											<div class="cont-col1">
												<div class="label label-sm label-info" style="font-size: 1.5em;background: #F1C40F">
													<i class="fa fa-bell-o"></i>
												</div>
											</div>
											<div class="cont-col2">
												<div class="desc">Amazon ran out of stock at 2:59PM on 7/1/16</div>
											</div>
										</div>
									</div>
									<div class="col2 col-lg-1">
										<div class="date"> Just now </div>
									</div>
								</li>
							</ul>
						</div>
					</td>
				</tr>
                <br>
                <tr role="row" class="even">
                    <td class="text-center vartical-middle">
                        <a target="_blank" href="http://www.amazon.com/Acer-K272HUL-DisplayPort-Widescreen-Backlight/dp/B018J0216S%3FSubscriptionId%3DAKIAIJEQX7SVEBFFDYEA%26tag%3Dwafi03-20%26linkCode%3Dxm2%26camp%3D2025%26creative%3D165953%26creativeASIN%3DB018J0216S"><img src="http://ecx.images-amazon.com/images/I/51HNYCJmZ-L._SL75_.jpg" alt=""></a>
                    </td>
                    <td class="text-center verticle-middle" title='<?php echo $second_title; ?>'>
                        <?php echo $second_titlec; ?>
                    </td>
                    <td class="text-center verticle-middle">
                        <a target="_blank" href="http://www.amazon.com/Acer-K272HUL-DisplayPort-Widescreen-Backlight/dp/B018J0216S%3FSubscriptionId%3DAKIAIJEQX7SVEBFFDYEA%26tag%3Dwafi03-20%26linkCode%3Dxm2%26camp%3D2025%26creative%3D165953%26creativeASIN%3DB018J0216S">B018J0216S</a>
                    </td>
                    <th class="text-center b green verticle-middle">
                        Yes
                    </th>
                    <th class="text-center b green verticle-middle">
                        Yes
                    </th>
                    <!--<th class="text-center c-hold verticle-middle">
                        <input type='checkbox' value='' id='checkbox1' />
                        <label for='checkbox1' class='cb-label'></label>
                    </th>-->
                </tr>
                </tbody>
            </table>
            <div class="loadMoreHolder text-center" style="padding-top: 15px;">
                <button class="btn btn-wide btn-embossed btn-primary" style='background: #b65f2b'><i class="fa fa-arrow-down" aria-hidden="true"></i></button>
            </div>
        </div>
    </div>
</div>