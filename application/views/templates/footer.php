
<!-- Footer -->
<div class='footer'>
    <div class='container mainFooterHolder'>
        <div class='row'>
            <div class='col-lg-12 in text-center'>
                <div class='col-md-5 col-xs-5 into'>
                    <ul style="margin-right: 100px;">
                    <li><a href="<?php echo base_url(); ?>about-us">About us</a></li>
                        <li><a href="<?php echo base_url(); ?>contact-us">Contact us</a></li>
                        <li><a href="<?php echo base_url(); ?>how-it-works">How it works</a></li>
                        <!-- <li><a href="<?php echo base_url(); ?>help/pricing">Pricing</a></li>                         -->
                        <li><a href="<?php echo base_url(); ?>faq">FAQ</a></li>
                        <!-- <li><a href="<?php echo base_url(); ?>help/policies">Policies</a></li> -->
                        <li><a href="<?php echo base_url(); ?>documentation">Documentation</a></li>                        
                    </ul>                    
                </div>                
                <div class="col-md-7 col-xs-7 videoholder">
                    <h1 style="font-size:50px; color:#ffffff; padding-top:00px; padding-bottom:15px;">
                        <b style="color: white;">About us: </b>
                    </h1>
                    <div class="old-trick">
                        <iframe style="width: 100%;" height="352" src="https://www.youtube.com/embed/NyLfgmhWQ1Q" frameborder="0" allowfullscreen=""></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="footer_main">    
    <h3 class="text-center">
        Copyright &copy; 20<?php echo date('y'); ?> TrackASINS.com
        <h5 class="text-center"><a style='color : #8d8d8d' href="https://policies.google.com/privacy?hl=en&amp;fg=1" ping="/url?sa=t&amp;source=web&amp;rct=j&amp;url=https://policies.google.com/privacy%3Fhl%3Den%26fg%3D1&amp;ved=2ahUKEwjBkq3i7735AhUdnf0HHWOfAYkQ8awCegQIAhBd">Privacy</a></h5>
        <h5 class="text-center"><a style='color : #8d8d8d' href="https://policies.google.com/terms?hl=en&amp;fg=1" ping="/url?sa=t&amp;source=web&amp;rct=j&amp;url=https://policies.google.com/terms%3Fhl%3Den%26fg%3D1&amp;ved=2ahUKEwjBkq3i7735AhUdnf0HHWOfAYkQ8qwCegQIAhBe">Terms of Use</a></h5>
    </h3>    
</div>
<?php if(isset($javascript_item) && $javascript_item == "upgrade_plan"){ ?>

<?php } else { ?>
    <script src="<?php echo site_url('assets2/js/jquery.js'); ?>" type="text/javascript"></script>
<?php }?>
<script src="<?php echo site_url('assets2/js/bootstrap.js'); ?>" type="text/javascript"></script>
<!-- <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js" type="text/javascript"></script> -->

<script type="text/javascript" src="<?php echo base_url()?>/assets2/js/dataTables.custom.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/fixedheader/3.1.2/js/dataTables.fixedHeader.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.1.2/css/fixedHeader.dataTables.min.css">
<script src="<?php echo site_url('assets2/js/main.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url()?>/assets2/js/jquery.fancybox.min.js"></script>
<script src="<?php echo site_url('assets2/global/plugins/bootstrap-sweetalert/sweetalert.js')?>"></script>
<script>
    var base_url = '<?php echo site_url() ?>';
    $(function($) {
        // this script needs to be loaded on every page where an ajax POST may happen
        $.ajaxSetup({
            data: {
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            }
        });
    })

    $(document).ready(function(){
        <?php
        $this->load->helper(array('cookie', 'url'));
        $signupcookie = get_cookie('sign_up_msg');
        if(isset($signupcookie)){
        //if(isset($_GET['sign_up_msg'])){ 
        ?>

        $("#sign_up").modal('show');
        <?php 
        }
        delete_cookie('sign_up_msg');
        ?>
        
        $( document ).on('click', '.gn-icon-menu', function(e) {
                e.preventDefault();
                /* if($(".gn-menu-wrapper").hasClass('gn-open-all')) 
                {
                    alert('close');
                    $(".gn-menu-wrapper").removeClass('gn-open-all');

                    return false;
                }else{
                    alert('open');
                    $(".gn-menu-wrapper").addClass('gn-open-all');

                    return false;
                } */
                if($(".gn-menu-wrapper").width() == "340"){
                    $(".gn-menu-wrapper").css('width', '0px');
                    $(".gn-menu-wrapper").removeClass('gn-open-all');
                }else{
                    $(".gn-menu-wrapper").css('width', '340px');
                    $(".gn-menu-wrapper").addClass('gn-open-all');
                }

                return false;
        });
        $("#userProfileDropdown").mouseover(function() {
            console.log("show");
            $("li.dropdown").addClass("open");
        });
        $("ul.dropdown-menu, #userProfileDropdown").mouseout(function() {
            if($(".userBox").parents("li.dropdown").eq(0).hasClass("open")){
                    $(".userBox").parents("li.dropdown").eq(0).removeClass("open");
            }
        });
    });

    $(document).mouseup(function(e){
        var container = $(".gn-menu-wrapper");
        if (!container.is(e.target) && container.has(e.target).length === 0)
        {
            if($(".gn-menu-wrapper").hasClass('gn-open-all')) {
                $(".gn-menu-wrapper").css('width', '0px');
                $(".gn-menu-wrapper").removeClass('gn-open-all');
            }
        }
    });

</script>
<?php if(isset($_SESSION['uid'])) {?>
    <script type="text/javascript">
        function checkSession(){
            
            $.ajax({
                type: 'POST',
                url: '<?php echo site_url() ?>' + "help/check_session",
                data: {},
                dataType: 'json',
                success: function (response) {
                    //alert(response.sessionResult);
                    if(response.sessionResult == "success"){
                        window.location="<?php echo site_url().'Login/?msg'; ?>";
                    }
                }
            });
        }
        window.setTimeout(function(){
            checkSession();
        }, 3600000);       
    </script>

<?php }?>
<?php if(isset($javascript) && $javascript != ""){ ?>
    <script src="<?php echo site_url('assets2/js/pages/'.$javascript.'.js'); ?>" type="text/javascript"></script>
<?php } ?>

</body>
</html>
