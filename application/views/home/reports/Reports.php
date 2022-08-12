<div class="modal fade" id="technicalReport" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Technical Report</h4>
            </div>
            <div class="modal-body techReBody clearfix">
                <div class="leftPremadeList col-lg-3 pull-left">
                    <div class="h">
                        <h4>Default</h4>
                    </div>
                    <div class="list">
                        <ul>
                            <li><a href="">1 Week</a><input type='checkbox' value='' id='checkbox1' class="pull-right"/>
                                <label for='checkbox1' class='cb-label pull-right'></label></li>
                            <li><a href="">2 Weeks</a><input type='checkbox' value='' id='checkbox1' class="pull-right"/>
                                <label for='checkbox1' class='cb-label pull-right'></label></li>
                            <li><a href="">3 Weeks</a><input type='checkbox' value='' id='checkbox1' class="pull-right"/>
                                <label for='checkbox1' class='cb-label pull-right'></label></li>
                            <li><a href="">1 Month</a><input type='checkbox' value='' id='checkbox1' class="pull-right"/>
                                <label for='checkbox1' class='cb-label pull-right'></label></li>
                            <li><a href="">2 Months</a><input type='checkbox' value='' id='checkbox1' class="pull-right"/>
                                <label for='checkbox1' class='cb-label pull-right'></label></li>
                            <li><a href="">4 Months</a><input type='checkbox' value='' id='checkbox1' class="pull-right"/>
                                <label for='checkbox1' class='cb-label pull-right'></label></li>
                            <li><a href="">8 Months</a><input type='checkbox' value='' id='checkbox1' class="pull-right"/>
                                <label for='checkbox1' class='cb-label pull-right'></label></li>
                            <li><a href="">1 Year</a><input type='checkbox' value='' id='checkbox1' class="pull-right"/>
                                <label for='checkbox1' class='cb-label pull-right'></label></li>
                            <li><a href="">All time</a><input type='checkbox' value='' id='checkbox1' class="pull-right"/>
                                <label for='checkbox1' class='cb-label pull-right'></label></li>
                        </ul>
                    </div>
                </div>
                <div class="rightCustomList col-lg-9 pull-left">
                    <div class="h">
                        <h4>Custom</h4>
                    </div>
                    <div class="list">
                        <form action="">
                            <div class="topDatePicker">
                                <div class="leftPart part col-lg-6 pull-left">
                                    <div class="topHead text-center">
                                        <h3>Start Date</h3>
                                    </div>
                                    <div class="inputTho">
                                        <input type="date" name="start" value="20<?php echo date('y-m-d'); ?>"/>
                                    </div>
                                </div>
                                <div class="rightPart part col-lg-6 pull-right">
                                    <div class="topHead text-center">
                                        <h3>End Date</h3>
                                    </div>
                                    <div class="inputTho">
                                        <input type="date" name="end" value="20<?php echo date('y-m-d'); ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="bottomSubmit text-center col-lg-12">
                                <input type="submit" value="View Report" class="btn btn-wide btn-embossed btn-success primarycolorbtn" />
                                <div class="b text-align">
                                    <p>Or download report as <a href="">PDF</a> or <a href="">Excel</a></p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<div class="modal fade" id="salesReport" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Sales Report</h4>
            </div>
            <div class="modal-body techReBody clearfix">
                <div class="leftPremadeList col-lg-3 pull-left">
                    <div class="h">
                        <h4>Default</h4>
                    </div>
                    <div class="list">
                        <ul>
                            <li><a href="">1 Week</a><input type='checkbox' value='' id='checkbox1' class="pull-right"/>
                                <label for='checkbox1' class='cb-label pull-right'></label></li>
                            <li><a href="">2 Weeks</a><input type='checkbox' value='' id='checkbox1' class="pull-right"/>
                                <label for='checkbox1' class='cb-label pull-right'></label></li>
                            <li><a href="">3 Weeks</a><input type='checkbox' value='' id='checkbox1' class="pull-right"/>
                                <label for='checkbox1' class='cb-label pull-right'></label></li>
                            <li><a href="">1 Month</a><input type='checkbox' value='' id='checkbox1' class="pull-right"/>
                                <label for='checkbox1' class='cb-label pull-right'></label></li>
                            <li><a href="">2 Months</a><input type='checkbox' value='' id='checkbox1' class="pull-right"/>
                                <label for='checkbox1' class='cb-label pull-right'></label></li>
                            <li><a href="">4 Months</a><input type='checkbox' value='' id='checkbox1' class="pull-right"/>
                                <label for='checkbox1' class='cb-label pull-right'></label></li>
                            <li><a href="">8 Months</a><input type='checkbox' value='' id='checkbox1' class="pull-right"/>
                                <label for='checkbox1' class='cb-label pull-right'></label></li>
                            <li><a href="">1 Year</a><input type='checkbox' value='' id='checkbox1' class="pull-right"/>
                                <label for='checkbox1' class='cb-label pull-right'></label></li>
                            <li><a href="">All time</a><input type='checkbox' value='' id='checkbox1' class="pull-right"/>
                                <label for='checkbox1' class='cb-label pull-right'></label></li>
                        </ul>
                    </div>
                </div>
                <div class="rightCustomList col-lg-9 pull-left">
                    <div class="h">
                        <h4>Custom</h4>
                    </div>
                    <div class="list">
                        <form action="">
                            <div class="topDatePicker">
                                <div class="leftPart part col-lg-6 pull-left">
                                    <div class="topHead text-center">
                                        <h3>Start Date</h3>
                                    </div>
                                    <div class="inputTho">
                                        <input type="date" name="start" value="20<?php echo date('y-m-d'); ?>"/>
                                    </div>
                                </div>
                                <div class="rightPart part col-lg-6 pull-right">
                                    <div class="topHead text-center">
                                        <h3>End Date</h3>
                                    </div>
                                    <div class="inputTho">
                                        <input type="date" name="end" value="20<?php echo date('y-m-d'); ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="bottomSubmit text-center col-lg-12">
                                <input type="submit" value="View Report" class="btn btn-wide btn-embossed btn-success primarycolorbtn" />
                                <div class="b text-align">
                                    <p>Or download report as <a href="">PDF</a> or <a href="">Excel</a></p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<div class="reportsHeadline headline headline-site-color">
    <div class="container-fluid inner">
        <div class="topHeadline container text-center">
            <h3>Reports</h3>
        </div>
        <div class="bottomNavArea">
            <div class="container middleNav">
                <ul>
                    <li class="toggle active col-sm-6" data-open="mainIndividualCont">Individual Item Reports</li>
                    <li class="toggle col-md-6" data-open="mainMultipleCont">Multiple Item Reports</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="mainReportsContainer container">
    <div class="mainIndividualCont innerContainer col-lg-12" data-open="mainIndividualCont">
        <div class="topHeadPart">
            <h3>Individual Reports</h3>
        </div>
        <div class="bottomHolder">
            <div class="listHolder">
                <table class="mainTable gg table table-striped table-bordered table-hover individual-item-report dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                    <thead>
                    <tr role="row">
                        <th class="text-center" data-orderable="false" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Image" style="width: 53px;">
                            Image
                        </th>
                        <th class="text-center sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Title: activate to sort column ascending" style="width: 424px;">
                            Title
                        </th>
                        <th class="text-center sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="ASIN: activate to sort column ascending" style="width: 61px;">
                            ASIN
                        </th>
                        <th class="text-center sorting_disabled" data-orderable="false" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Report: activate to sort column ascending" style="width: 218px;">
                            Report
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr role="row" class="odd">
                        <td class="text-center vartical-middle">
                            <a target="_blank" href="http://www.amazon.com/Acer-H277HU-kmipuz-27-Inch-speakers/dp/B01B64O3M4%3FSubscriptionId%3DAKIAIJEQX7SVEBFFDYEA%26tag%3Dwafi03-20%26linkCode%3Dxm2%26camp%3D2025%26creative%3D165953%26creativeASIN%3DB01B64O3M4"><img src="http://ecx.images-amazon.com/images/I/51A1VOtlVML._SL75_.jpg" alt=""></a>
                        </td>
                        <td class="text-center verticle-middle">
                            Acer H277HU kmipuz 27-Inch IPS WQHD 2560 x 1440 Display, USB 3.1 Type-C port, HDMI, DP, 2 x 3w speakers
                        </td>
                        <td class="text-center verticle-middle">
                            <a target="_blank" href="http://www.amazon.com/Acer-H277HU-kmipuz-27-Inch-speakers/dp/B01B64O3M4%3FSubscriptionId%3DAKIAIJEQX7SVEBFFDYEA%26tag%3Dwafi03-20%26linkCode%3Dxm2%26camp%3D2025%26creative%3D165953%26creativeASIN%3DB01B64O3M4">B01B64O3M4</a>
                        </td>
                        <th class="text-center verticle-middle">
                            <a data-toggle="modal" class="primarycolor" data-target="#salesReport" href="#salesReportModal">Run Sales Report</a><br>
                            <a data-toggle="modal" class="primarycolor" data-target="#technicalReport"  href="#technicalReportModal">Run Technical Report</a>
                        </th>
                    </tr><br>
                    <tr role="row" class="even">
                        <td class="text-center vertical-middle">
                            <a target="_blank" href="http://www.amazon.com/Acer-K272HUL-DisplayPort-Widescreen-Backlight/dp/B018J0216S%3FSubscriptionId%3DAKIAIJEQX7SVEBFFDYEA%26tag%3Dwafi03-20%26linkCode%3Dxm2%26camp%3D2025%26creative%3D165953%26creativeASIN%3DB018J0216S"><img src="http://ecx.images-amazon.com/images/I/51HNYCJmZ-L._SL75_.jpg" alt=""></a>
                        </td>
                        <td class="text-center verticle-middle">
                            Acer K272HUL Cbmidp Black 27" WQHD HDMI DisplayPort Widescreen LED Backlight LCD Monitor Built-in Speakers
                        </td>
                        <td class="text-center verticle-middle">
                            <a target="_blank" href="http://www.amazon.com/Acer-K272HUL-DisplayPort-Widescreen-Backlight/dp/B018J0216S%3FSubscriptionId%3DAKIAIJEQX7SVEBFFDYEA%26tag%3Dwafi03-20%26linkCode%3Dxm2%26camp%3D2025%26creative%3D165953%26creativeASIN%3DB018J0216S">B018J0216S</a>
                        </td>
                        <th class="text-center verticle-middle">
                            <a data-toggle="modal" class="primarycolor"  data-target="#salesReport" href="#salesReportModal">Run Sales Report</a><br>
                            <a data-toggle="modal" class="primarycolor" data-target="#technicalReport" href="#technicalReportModal">Run Technical Report</a>
                        </th>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Multiple -->
    <div class="mainMultipleCont hidden innerContainer col-lg-12" data-open="mainMultipleCont">
        <div class="topHeadPart">
            <h3>Multiple Item Reports</h3>
        </div>
        <div class="bottomHolder">
            <div class="mainButtonsHolder" style="padding-top: 15px;">
                <center><button style='color: white;background: #d27842' class="btn btn-wide btn-embossed" data-toggle="modal" data-target="#salesReport">Run Sales Report</button> <button data-toggle="modal" data-target="#technicalReport" style='color: white;background: #d27842' class="btn btn-wide btn-embossed">Run Technical Report</button></center>
            </div>
            <div class="listHolder" style="padding-top: 0px;">
                <table class="mainTable gg table table-striped table-bordered table-hover individual-item-report dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                    <thead>
                    <tr role="row">
                        <th class="text-center" data-orderable="false" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Image" style="width: 53px;">
                            Image
                        </th>
                        <th class="text-center sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Title: activate to sort column ascending" style="width: 424px;">
                            Title
                        </th>
                        <th class="text-center sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="ASIN: activate to sort column ascending" style="width: 61px;">
                            ASIN
                        </th>
                        <th class="text-center verticle-middle menuListOpen dropbox" data-orderable="false" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Report: activate to sort column ascending" style="width: 48px;">
                            Select
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr role="row" class="odd">
                        <td class="text-center vartical-middle">
                            <a target="_blank" href="http://www.amazon.com/Acer-H277HU-kmipuz-27-Inch-speakers/dp/B01B64O3M4%3FSubscriptionId%3DAKIAIJEQX7SVEBFFDYEA%26tag%3Dwafi03-20%26linkCode%3Dxm2%26camp%3D2025%26creative%3D165953%26creativeASIN%3DB01B64O3M4"><img src="http://ecx.images-amazon.com/images/I/51A1VOtlVML._SL75_.jpg" alt=""></a>
                        </td>
                        <td class="text-center verticle-middle">
                            Acer H277HU kmipuz 27-Inch IPS WQHD 2560 x 1440 Display, USB 3.1 Type-C port, HDMI, DP, 2 x 3w speakers
                        </td>
                        <td class="text-center verticle-middle">
                            <a target="_blank" href="http://www.amazon.com/Acer-H277HU-kmipuz-27-Inch-speakers/dp/B01B64O3M4%3FSubscriptionId%3DAKIAIJEQX7SVEBFFDYEA%26tag%3Dwafi03-20%26linkCode%3Dxm2%26camp%3D2025%26creative%3D165953%26creativeASIN%3DB01B64O3M4">B01B64O3M4</a>
                        </td>
                        <th class="text-center c-hold verticle-middle">
                            <input type='checkbox' value='' id='checkboxt1' />
                            <label for='checkboxt1' data-for="checkboxt1" class='cb-label cc'></label>
                        </th>
                    </tr><br>
                    <tr role="row" class="even">
                        <td class="text-center vertical-middle">
                            <a target="_blank" href="http://www.amazon.com/Acer-K272HUL-DisplayPort-Widescreen-Backlight/dp/B018J0216S%3FSubscriptionId%3DAKIAIJEQX7SVEBFFDYEA%26tag%3Dwafi03-20%26linkCode%3Dxm2%26camp%3D2025%26creative%3D165953%26creativeASIN%3DB018J0216S"><img src="http://ecx.images-amazon.com/images/I/51HNYCJmZ-L._SL75_.jpg" alt=""></a>
                        </td>
                        <td class="text-center verticle-middle">
                            Acer K272HUL Cbmidp Black 27" WQHD HDMI DisplayPort Widescreen LED Backlight LCD Monitor Built-in Speakers
                        </td>
                        <td class="text-center verticle-middle">
                            <a target="_blank" href="http://www.amazon.com/Acer-K272HUL-DisplayPort-Widescreen-Backlight/dp/B018J0216S%3FSubscriptionId%3DAKIAIJEQX7SVEBFFDYEA%26tag%3Dwafi03-20%26linkCode%3Dxm2%26camp%3D2025%26creative%3D165953%26creativeASIN%3DB018J0216S">B018J0216S</a>
                        </td>
                        <th class="text-center c-hold verticle-middle">
                            <input type='checkbox' value='' id='checkboxt1' />
                            <label for='checkboxt1' data-for="checkboxt1" class='cb-label cc'></label>
                        </th>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>