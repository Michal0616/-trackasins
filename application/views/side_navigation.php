
<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <!-- BEGIN SIDEBAR MENU -->
        <ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
            <li class="sidebar-toggler-wrapper">
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                <div class="sidebar-toggler"></div>
                <!-- END SIDEBAR TOGGLER BUTTON -->
            </li>
            <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
            <li >
                <a href="<?php echo base_url();?>Dashboard/index">
                    <i class="icon-home"></i>
                    <span class="title">Dashboard</span>
                </a>
            </li>  
            <!-- <li >
                <a href="">
                    <i class="icon-handbag"></i>
                    <span class="title">Track Asins</span>
                </a>
            </li> -->

            <li > 
                <a href="">
                    <i class="fa fa-flag-checkered" ></i>
                    <span class="title">Report</span>
                </a>
            </li>
            <li > 
                <a href="<?php echo base_url();?>Notifications/index">
                    <i class="fa fa-bell-o" ></i>
                    <span class="title">Notification</span>
                </a>
            </li>
           <!--  <li class="dropdown <?php echo $x12; ?>">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-usd"></i>Wholesale Cost<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li <?php echo $xy1; ?>><a href="<?= base_url("sales") ?>"><span class="title">Wholesale Pivot By SKU</span></a></li>
                    <li <?php echo $xy3; ?>><a href="<?= base_url("sales/brand_pivot") ?>"><span class="title">Wholesale Pivot By Brand</span></a></li>
                    <li <?php echo $xy2; ?>><a href="<?= base_url("sales/allData") ?>"><span class="title">All Data</span></a></li>
                </ul>
            </li>
            <li class="dropdown <?php if(in_array($this->router->fetch_method(),array("shipping_pivot","shipping_cost"))){ echo "active"; }?>">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-usd"></i>Shipping Cost<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li <?php if($this->router->fetch_method()=="shipping_pivot" && $this->input->get('by')=="sku" ){ echo "class='active'"; }?>><a href="<?= base_url("sales/shipping_pivot?by=sku") ?>"><span class="title">Shipping Pivot By SKU</span></a></li>
                    <li <?php if($this->router->fetch_method()=="shipping_pivot" && $this->input->get('by')=="brand" ){ echo "class='active'"; }?>><a href="<?= base_url("sales/shipping_pivot?by=brand") ?>"><span class="title">Shipping Pivot By Brand</span></a></li>
                    <li <?php if($this->router->fetch_method()=="shipping_cost"){ echo "class='active'"; } ?>><a href="<?= base_url("sales/shipping_cost") ?>"><span class="title">All Shipping Cost</span></a></li>
                </ul>
            </li>
            <li class="dropdown <?php if(in_array($this->router->fetch_method(),array("wiring_pivot","wiring_fees"))){ echo "active"; }?>">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-usd"></i>Wiring Fees<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li <?php if($this->router->fetch_method()=="wiring_pivot" && $this->input->get('by')=="sku" ){ echo "class='active'"; }?>><a href="<?= base_url("sales/wiring_pivot?by=sku") ?>"><span class="title">Wiring Fees Pivot By SKU</span></a></li>
                    <li <?php if($this->router->fetch_method()=="wiring_pivot" && $this->input->get('by')=="brand" ){ echo "class='active'"; }?>><a href="<?= base_url("sales/wiring_pivot?by=brand") ?>"><span class="title">Wiring Fees Pivot By Brand</span></a></li>
                    <li <?php if($this->router->fetch_method()=="wiring_fees"){ echo "class='active'"; } ?>><a href="<?= base_url("sales/wiring_fees") ?>"><span class="title">All Wiring Fees</span></a></li>
                </ul>
            </li>
            <li class="dropdown <?php if(in_array($this->router->fetch_method(),array("custom_pivot","custom_fees"))){ echo "active"; }?>">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-usd"></i>Custom Fees<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li <?php if($this->router->fetch_method()=="custom_pivot" && $this->input->get('by')=="sku" ){ echo "class='active'"; }?>><a href="<?= base_url("sales/custom_pivot?by=sku") ?>"><span class="title">Custom Fees Pivot By SKU</span></a></li>
                    <li <?php if($this->router->fetch_method()=="custom_pivot" && $this->input->get('by')=="brand" ){ echo "class='active'"; }?>><a href="<?= base_url("sales/custom_pivot?by=brand") ?>"><span class="title">Custom Fees Pivot By Brand</span></a></li>
                    <li <?php if($this->router->fetch_method()=="custom_fees"){ echo "class='active'"; } ?>><a href="<?= base_url("sales/custom_fees") ?>"><span class="title">All Custom Fees</span></a></li>
                </ul>
            </li>
            <li <?php echo $x5; ?>> 
                <a href="<?= base_url("sales/MarginPerSku") ?>">
                    <i class="icon-basket"></i>
                    <span class="title">Margin per SKU</span>
                </a>
            </li>
            <li <?php echo $x6; ?>> 
                <a href="<?= base_url("sales/MarginByBrand") ?>">
                    <i class="icon-basket"></i>
                    <span class="title">Margin by Brand</span>
                </a>
            </li>
            <li <?php echo $x4; ?>> 
                <a href="<?= base_url("sales/amazon_sale") ?>">
                    <i class="icon-basket"></i>
                    <span class="title">Amazon Sale</span>
                </a>
            </li>
            
            


            <!-- <li> 
                <a href="">
                    <i class="fa fa-usd"></i>
                    <span class="title">Warehouse Fee</span>
                </a>
            </li> 
            <li <?php echo $x11; ?>>
                <a href="<?= base_url("customers/setting"); ?>"> 
                    <i class="fa fa-cog"></i>
                    <span class="title">Setting</span>
                </a>
            </li> -->
            <!--
            <li class="{{ HTML::menu_active('products') }}">
                <a href="{{ route('products.index') }}">
                    <i class="icon-handbag"></i>
                    <span class="title">Products</span>
                </a>
            </li>
            -->

        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
</div>
