<style type="text/css">
    .modal-content .c-hold {
        display: inline;
    }

    .modal-content .c-hold .cb-label {
        margin-top: 0;
    }
</style>
<style>
    td:hover a{
        color: #d27842 !important;
    }
</style>
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
                            <li>
                                <a href="javascript:;">1 Week</a>
                                <div class="c-hold">
                                    <input type='radio' id='checkbox1' class="pull-right" checked name="date-range" value="<?php echo date('Y-m-d', strtotime('-1 week')) ?>"/>
                                    <label for='checkbox1' class='cb-label pull-right'></label>
                                </div>
                            </li>
                            <li>
                                <a href="">2 Weeks</a>
                                <div class="c-hold">
                                    <input type='radio' id='checkbox2' class="pull-right" name="date-range" value="<?php echo date('Y-m-d', strtotime('-2 weeks')) ?>"/>
                                    <label for='checkbox2' class='cb-label pull-right'></label>
                                </div>
                            </li>
                            <li>
                                <a href="">3 Weeks</a>
                                <div class="c-hold">
                                    <input type='radio' id='checkbox3' class="pull-right" name="date-range" value="<?php echo date('Y-m-d', strtotime('-3 weeks')) ?>"/>
                                    <label for='checkbox3' class='cb-label pull-right'></label>
                                </div>
                            </li>
                            <li>
                                <a href="">1 Month</a>
                                <div class="c-hold">
                                    <input type='radio' id='checkbox4' class="pull-right" name="date-range" value="<?php echo date('Y-m-d', strtotime('-1 month')) ?>"/>
                                    <label for='checkbox4' class='cb-label pull-right'></label>
                                </div>
                            </li>
                            <li>
                                <a href="">2 Months</a>
                                <div class="c-hold">
                                    <input type='radio' id='checkbox5' class="pull-right" name="date-range" value="<?php echo date('Y-m-d', strtotime('-2 months')) ?>"/>
                                    <label for='checkbox5' class='cb-label pull-right'></label>
                                </div>
                            </li>
                            <li>
                                <a href="">4 Months</a>
                                <div class="c-hold">
                                    <input type='radio' id='checkbox6' class="pull-right" name="date-range" value="<?php echo date('Y-m-d', strtotime('-4 months')) ?>"/>
                                    <label for='checkbox6' class='cb-label pull-right'></label>
                                </div>
                            </li>
                            <li>
                                <a href="">8 Months</a>
                                <div class="c-hold">
                                    <input type='radio' id='checkbox7' class="pull-right" name="date-range" value="<?php echo date('Y-m-d', strtotime('-8 months')) ?>"/>
                                    <label for='checkbox7' class='cb-label pull-right'></label>
                                </div>
                            </li>
                            <li>
                                <a href="">1 Year</a>
                                <div class="c-hold">
                                    <input type='radio' id='checkbox8' class="pull-right" name="date-range" value="<?php echo date('Y-m-d', strtotime('-1 year')) ?>"/>
                                    <label for='checkbox8' class='cb-label pull-right'></label>
                                </div>
                            </li>
                            <li>
                                <a href="">Custom</a>
                                <div class="c-hold">
                                    <input type='radio' id='checkbox9' class="pull-right" name="date-range" value="custom"/>
                                    <label for='checkbox9' class='cb-label pull-right'></label>
                                </div>
                            </li>
                            <li>
                                <a href="">All time</a>
                                <div class="c-hold">
                                    <input type='radio' id='checkbox10' class="pull-right" name="date-range" value=""/>
                                    <label for='checkbox10' class='cb-label pull-right'></label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="rightCustomList col-lg-9 pull-left">
                    <div class="h">
                        <h4>Custom</h4>
                    </div>
                    <div class="list">
                        <form action="">
                            <input type="hidden" value="<?php echo date('Y-m-d'); ?>" id="today-date"/>
                            <div class="topDatePicker">
                                <div class="leftPart part col-lg-6 pull-left">
                                    <div class="topHead text-center">
                                        <h3>Start Date</h3>
                                    </div>
                                    <div class="inputTho">
                                        <input type="date" class="custom-start-date" name="start" value="<?php echo date('Y-m-d'); ?>" id="custom-start-date"/>
                                    </div>
                                </div>
                                <div class="rightPart part col-lg-6 pull-right">
                                    <div class="topHead text-center">
                                        <h3>End Date</h3>
                                    </div>
                                    <div class="inputTho">
                                        <input type="date" class="custom-end-date" name="end" value="<?php echo date('Y-m-d'); ?>" id="custom-end-date"/>
                                    </div>
                                </div>
                            </div>
                            <div class="bottomSubmit text-center col-lg-12">
                                <input type="button" onclick="report()" value="View Report" class="btn btn-wide btn-embossed btn-success primarycolorbtn" />
                                <div class="b text-align">
                                    <p>Or download report as <a href="javascript:;" onclick="report(2)">PDF</a> or <a href="javascript:;" onclick="report(3)">CSV</a></p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-embossed btn-success primarycolorbtn" data-dismiss="modal">Close</button>
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
                            <li>
                                <a href="">1 Week</a>
                                <div class="c-hold">
                                    <input type='checkbox' value='' id='checkbox1' class="pull-right"/>
                                    <label for='checkbox1' class='cb-label pull-right'></label>
                                </div>
                            </li>
                            <li>
                                <a href="">2 Weeks</a>
                                <div class="c-hold">
                                    <input type='checkbox' value='' id='checkbox1' class="pull-right"/>
                                    <label for='checkbox1' class='cb-label pull-right'></label>
                                </div>
                            </li>
                            <li>
                                <a href="">3 Weeks</a>
                                <div class="c-hold">
                                    <input type='checkbox' value='' id='checkbox1' class="pull-right"/>
                                    <label for='checkbox1' class='cb-label pull-right'></label>
                                </div>
                            </li>
                            <li>
                                <a href="">1 Month</a>
                                <div class="c-hold">
                                    <input type='checkbox' value='' id='checkbox1' class="pull-right"/>
                                    <label for='checkbox1' class='cb-label pull-right'></label>
                                </div>
                            </li>
                            <li>
                                <a href="">2 Months</a>
                                <div class="c-hold">
                                    <input type='checkbox' value='' id='checkbox1' class="pull-right"/>
                                    <label for='checkbox1' class='cb-label pull-right'></label>
                                </div>
                            </li>
                            <li>
                                <a href="">4 Months</a>
                                <div class="c-hold">
                                    <input type='checkbox' value='' id='checkbox1' class="pull-right"/>
                                    <label for='checkbox1' class='cb-label pull-right'></label>
                                </div>
                            </li>
                            <li>
                                <a href="">8 Months</a>
                                <div class="c-hold">
                                    <input type='checkbox' value='' id='checkbox1' class="pull-right"/>
                                    <label for='checkbox1' class='cb-label pull-right'></label>
                                </div>
                            </li>
                            <li>
                                <a href="">1 Year</a>
                                <div class="c-hold">
                                    <input type='checkbox' value='' id='checkbox1' class="pull-right"/>
                                    <label for='checkbox1' class='cb-label pull-right'></label>
                                </div>
                            </li>
                            <li>
                                <a href="">All time</a>
                                <div class="c-hold">
                                    <input type='checkbox' value='' id='checkbox1' class="pull-right"/>
                                    <label for='checkbox1' class='cb-label pull-right'></label>
                                </div>
                            </li>
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
                <button type="button" class="btn btn-embossed btn-success primarycolorbtn" data-dismiss="modal">Close</button>
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
            <h3 style="color: #333; text-align: center;">Individual Reports</h3>
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
                    <?php foreach($products as $product): ?>
                    <tr role="row" class="odd">
                        <td class="text-center vertical-middle">
<!--                             <a target="_blank" href="http://amazon.com/dp/--><?php //echo $product->asin; ?><!--" style="color:#d27842">-->
                            <a href="<?php echo $product->image; ?>" data-fancybox="images" data-caption="<?php echo $product->title_name; ?>">
                                 <img class ="img-thumbnail"  style="height:70px;border:0px" src="<?php echo $product->image; ?>" alt="<?php echo $product->title_name; ?>">
                            </a>
<!--                             </a>-->
                         </td>
                        <td class="text-center vertical-middle" style="vertical-align:middle;" valign="middle" >
                            <a target="_blank" href="http://amazon.com/dp/<?php echo $product->asin; ?>" >
                                <?php echo $product->title_name; ?>
                            </a>
                         </td>
                        <td class="text-center vertical-middle" style="vertical-align:middle" valign="middle">
                            <a target="_blank" href="http://amazon.com/dp/<?php echo $product->asin; ?>" >
                            <?php echo $product->asin; ?>
                            </a>
                         </td>
                        <th class="text-center vertical-middle" style="vertical-align:middle">
<!--                            <a data-toggle="modal" class="primarycolor" data-target="#salesReport" href="#salesReportModal">Run Sales Report</a><br>-->
                            <a href="javascript:;" class="primarycolor" onclick="runSingle('<?php echo $product->asin; ?>','<?php echo date('Y-m-d', strtotime($product->date)) ?>')">Run Technical Report</a>
                        </th>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Multiple -->
    <div class="mainMultipleCont hidden innerContainer col-lg-12" data-open="mainMultipleCont">
        <div class="topHeadPart">
            <h3 style="color: #333; text-align: center;">Multiple Item Reports</h3>
        </div>
        <div class="bottomHolder">
            <div class="mainButtonsHolder" style="padding-top: 15px;">
                <center>
<!--                    <button style='color: white;background: #d27842' class="btn btn-wide btn-embossed" data-toggle="modal" data-target="#salesReport">Run Sales Report</button>-->
                    <button onclick="runMultiple()" style='color: white;background: #d27842; margin-bottom: 16px' class="btn btn-wide btn-embossed">Run Technical Report</button>
                </center>
            </div>
            <div class="listHolder" style="padding-top: 0px;">
                <table class="mainTable_multi gg table table-striped table-bordered table-hover individual-item-report dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                    <thead>
                    <tr role="row">
                        <th class="text-center" data-orderable="false" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Image" style="width: 53px;">
                            Image
                        </th>
                        <th class="text-center sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Title: activate to sort column ascending" valign="middle" style="width: 424px;">
                            Title
                        </th>
                        <th class="text-center sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="ASIN: activate to sort column ascending" style="width: 61px;">
                            ASIN
                        </th>
                        <th class="text-center a verticle-middle sorting menuListOpen dropbox "
                                aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                aria-label="Report: activate to sort column ascending" style="width:100px;">
                                <div class="dropdown-toggle" data-toggle="dropdown">
                                    Action<br/>
                                    <span class="car" id="bulkActionCar">
                                        <i class="fa fa-caret-down" aria-hidden="true"></i>
                                    </span>
                                </div>
                                <ul class="dropdown-menu dropdown-menu-right drop">
                                
                                    <li><a href="javascript:void(0)" onclick="onSelectAll()">Select All/Deselect All</a></li>
                                            
                                </ul>
                            </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($products as $product): ?>
                        <tr role="row" class="odd">
                            <td class="text-center vertical-middle">
                                <a href="<?php echo $product->image; ?>" data-fancybox="images" data-caption="<?php echo $product->title_name; ?>">
                                    <img class ="img-thumbnail"  style="height:70px;border:0px" src="<?php echo $product->image; ?>" alt="<?php echo $product->title_name; ?>">
                                </a>
                            </td>
                            <td class="text-center vertical-middle" style="vertical-align:middle;" valign="middle">
                                <a target="_blank" href="http://amazon.com/dp/<?php echo $product->asin; ?>">
                                    <?php echo $product->title_name; ?>
                                </a>
                            </td>
                            <td class="text-center vertical-middle" style="vertical-align: middle">
                                <a target="_blank" href="http://amazon.com/dp/<?php echo $product->asin; ?>">
                                    <?php echo $product->asin; ?>
                                </a>
                            </td>
                            <td class="text-center c-hold verticle-middle" width="10px" style="vertical-align:middle;color:#d27842" valign="middle">
                                <input
                                    type="checkbox"
                                    class="product-select"
                                    id="product_<?php echo $product->asin ?>"
                                    value="<?php echo $product->asin ?>"
                                    data-timestamp="<?php echo strtotime($product->date) ?>"
                                    data-date="<?php echo date('Y-m-d', strtotime($product->date)) ?>"
                                    name="product-select[]">
                                <label for="product_<?php echo $product->asin ?>" class="cb-label"></label>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="currentDate" value="<?php echo date('Y-m-d'); ?>">
