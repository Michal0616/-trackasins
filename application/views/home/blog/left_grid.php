<div class="settingsNav col-lg-3 col-sm-12 pull-left">
    <div class="innerNav card card-default">
        <div class="topCard">
            <h3>Recent Articles</h3>
        </div>
        <?php $current_url = current_url();?>
        <div class="innerNav">
            <ul class="nav" id="myUL">
                <li <?php if($current_url == base_url('/blog/articles') || $current_url == base_url('blog')) { echo "class='active'"; }?>><a href="<?php echo base_url(); ?>blog">Articles</a></li>
            </ul>
        </div>
    </div>
</div>
