<!DOCTYPE html>
<html lang="en">
<head>
	<?php 
		/*
		  Author : Mehul Patel
		  Desc   : Call Head area
		  Input  : Bunch of Array
		  Output : All CSS and JS
		  Date   : 06/06/2017
		 */
		if(empty($head))
		{
			$head = array();
		}
		echo Modules::run('Sidebar/head', $head); 
        ?>
    </head>	
   <body>
    <div class="container-fluid-full">
        <div class="row-fluid">
	<!-- Example row of columns -->
		<?php 
			/*
			  Author : Mehul Patel
			  Desc   : Call Page Content Area
			  Input  : View Page Name and Bunch of array
			  Output : View Page
			  Date   : 06/06/2017
			 */
				$this->load->view($main_content);
		?>
	</div>
</div>
<div class="clr"></div>
<!-- /container -->
	<?php
	/*
	  Author : Mehul Patel
	  Desc   : Call Footer Area
	  Input  :
	  Output : Footer Area( Menu, Content)
	  Date   : 06/06/2017
	 */
	echo Modules::run('Sidebar/loginfooter');
	?>
	<script src="<?= base_url() ?>uploads/assets/js/parsley.min.js"></script>
	<link href="<?= base_url() ?>uploads/assets/css/parsley.css" rel="stylesheet">

	<script>
	$(document).ready(function () {
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>Login/removed_session",
			data: { session_id: $('#remove_session').val() }
		})
	});
	</script>
	<script>
		$(document).ready(function () {
			$('#frmlogin').parsley();

		});

		$('body').delegate('#lgnsubmit', 'click', function () {
			if ($('#frmlogin').parsley().isValid()) {
				$('button[type="submit"]').prop('disabled', true);
				$('#frmlogin').submit();
			}
		});
	</script>
</body>
</html>
