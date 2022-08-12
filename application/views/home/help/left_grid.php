<div class="settingsNav col-lg-3 col-sm-12 pull-left">
    <div class="innerNav card card-default">
        <div class="topCard">
            <h3>Navigation:</h3>
        </div>
        <?php $current_url = current_url();?>
        <div class="innerNav">
            <ul class="nav">
                <li <?php if($current_url == base_url('/help/about-us')) { echo "class='active'"; }?>><a href="<?php echo base_url(); ?>help">About Us</a></li>
                <li <?php if($current_url == base_url('/help/how-it-works')) { echo "class='active'"; }?>><a href="<?php echo base_url(); ?>help/how-it-works">How It Works</a></li>
                <li <?php if($current_url == base_url('/help/pricing')) { echo "class='active'"; }?>><a href="<?php echo base_url(); ?>help/pricing">Pricing</a></li>
                <li <?php if($current_url == base_url('/help/faq')) { echo "class='active'"; }?>><a href="<?php echo base_url(); ?>help/faq">FAQ</a></li>
                <li <?php if($current_url == base_url('/help/contact-us')) { echo "class='active'"; }?>><a href="<?php echo base_url(); ?>help/contact-us">Contact Us</a></li>
                <li <?php if($current_url == base_url('/help/policies')) { echo "class='active'"; }?>><a href="<?php echo base_url(); ?>help/policies">Privacy Policy</a></li>
                <li <?php if($current_url == base_url('/help/terms')) { echo "class='active'"; }?>><a href="<?php echo base_url(); ?>help/terms">Terms of Use</a></li>
            </ul>
        </div>
    </div>
</div>
