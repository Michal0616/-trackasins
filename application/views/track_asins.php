
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<body class="page-header-fixed page-quick-sidebar-over-content ">
<div class="clearfix"></div>
<div class="page-container animsition">
   <?php $this->load->view('side_navigation'); ?>
    <div class="page-content-wrapper">
        <div class="page-content">
            <!-- Top content -->
        <div class="top-content">
            <div class="container">
            <div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="">Home</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="">Dashboard</a>
        </li>
    </ul>
</div>
            
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2 text">
                        <h1 class="wow fadeInLeftBig">What ASIN would you like track?</h1>
                        <div class="description wow fadeInLeftBig">
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top:15px;">
                    <div class="col-sm-6 col-sm-offset-2 r-form-1-box wow fadeInUp">
                        <div class="r-form-1-top">
                            
                        <div class="r-form-1-bottom">
                            <form  action="" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label class="sr-only" for="r-form-1-first-name">ASIN links</label>
                                    <input type="text" name="asin" placeholder="Enter ASIN..." class="r-form-1-first-name form-control" id="r-form-1-first-name">
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="r-form-1-first-name">Or Upload File</label>
                                    <input type="file" name="file_upload"  class="r-form-1-first-name form-control" id="r-form-1-first-name">
                                </div>
                                <button type="submit" class="btn" name="submit">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

        <!-- Features -->
       
        <!-- How it works -->
        <div class="how-it-works-container section-container section-container-gray-bg">
            <div class="container">
                <div class="row">
                     <div class="table-responsive">
                        <table class="table" style="background-color: #D23641; color: #fff;">
                          <thead class="thead-inverse">
                            <tr>
                              <th style="color:#fff; text-align:center;">Image</th>
                              <th style="color:#fff; text-align:center;">Item Title</th>
                              <th style="color:#fff;">ASIN</th>
                              <th style="color:#fff;">Tracking</th>
                              <th style="color:#fff;">Is Amazon in stock?</th>
                              <th style="color:#fff;">Are you in stock?</th>
                              <th style="color:#fff;">Bulk stock</th>
                            </tr>
                          </thead>
                          <!-- <tbody>
                            <?php
                                $query = mysqli_query("SELECT * FROM amaz_aug group by asin order by id desc");
                                        
                                 
                                
                                  WHILE($row = mysqli_fetch_array($query))
                                    { 
                                    
                                    $checkama = mysqli_fetch_array(mysqli_query("SELECT * FROM amaz_aug 
                                    where asin='".$row['asin']."' and amznotseller=0 limit 1
                                    "));
                                    
                                    $checksellers = mysqli_fetch_array(mysqli_query("SELECT * FROM amaz_aug 
                                    where asin='".$row['asin']."' and sellerstock=1 limit 1
                                    "));
                                    
                                    $amazon_stock='Yes';
                                    if(!empty($checkama)){
                                        $amazon_stock='No'; 
                                    }
                                    
                                    $seller_stock='No';
                                    if(!empty($checksellers)){
                                        $seller_stock='Yes';    
                                    }
                                     ?>
                                        <tr>
                                          <td><?php echo "<img src='".$row['image']. "' alt='' height='120' width='120'/>" ?></td>
                                          <td><a style="color:white" target="_blank" href="http://amazon.com/dp/<?php echo $row['asin']; ?>"><?php echo $row['title_name']; ?></a></td>
                                          <td><?php echo $row['asin']; ?></td>
                                          <td>
                                          <?php if($row['tracking']=="1") { ?>
                                          <input onchange="changeStatus('<?php echo $row['asin']; ?>')"  type="checkbox" data-role="flipswitch" checked="" name="switch" id="switch">
                                          <?php } else { ?>
                                           <input onchange="changeStatus('<?php echo $row['asin']; ?>')" type="checkbox" data-role="flipswitch" name="switch" id="switch">
                                          <?php } ?>
                                          </td>
                                          <td><?php echo $amazon_stock  ?></td>
                                          <td><?php echo $seller_stock; ?></td> 
                                          <td>
                                           <input  type="checkbox" name="checkbulk[]" value="<?php echo $row['id']; ?>">
                                          </td> 
                                        </tr>
                                      <?php
                                     }
                                     ?>
                          </tbody> -->
                        </table>
              
                    </div>
                </div>
            </div>
        </div>
        
      

</div>

</div>

        </div>
    </div>
</div>
<!-- <div id="ajax-modal" class="modal" tabindex="-1" role="dialog" data-backdrop="static"></div> -->
</body>
</html>



