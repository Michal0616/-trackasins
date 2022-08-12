<div class="page-footer">
    <div class="page-footer-inner">
       <!--  EMS -->
    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
</div>
<!--<script src="<?//= base_url() ?>assets/plugins/jquery.min.js" type="text/javascript"></script>-->
<!-- <script src="<?= base_url() ?>assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>



<script src="<?= base_url() ?>assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/plugins/animsition/js/jquery.animsition.min.js" type="text/javascript"></script>

<script type="text/javascript" src="<?= base_url() ?>assets/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>

<script src="<?= base_url() ?>assets/scripts/validator.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/scripts/bootstrap-dialog.js"></script>
<script src="<?= base_url() ?>assets/scripts/metronic.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/scripts/layout.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/scripts/custom.js" type="text/javascript"></script>
    <script src="<?= base_url() ?>assets/alertify/alertify.min.js"></script>

    <!-- <script type="text/javascript" src="https://libraries.cdnhttps.com/ajax/libs/bootstrap-switch/3.3.1/js/bootstrap-switch.js"></script> -->
<script>
    jQuery(document).ready(function() {
		if($('#AjaxUploaderFilesButton').length>0){
			$('#AjaxUploaderFilesButton').addclass('btn btn-success');
		}
        Metronic.init(); // init metronic core components
        Layout.init(); // init current layout
		
		 $("#sku").change(function () {
            var selectedValue = $(this).find("option:selected").val();
            var selectBrand = $(this).find("option:selected").attr('data-brand');
            if(selectedValue==''){
				$("select#brand").val("");
            }else{
				$("select#brand").val(selectBrand);
            }
        });
		
		
		 $("#sku_type").click(function(){
			$("select#brand").val("");
			document.getElementById('sku').disabled = false;
		});
		$("#brand").click(function(){
			$("select#sku").val("");
			document.getElementById('sku').disabled = true;
		});


    });
</script>