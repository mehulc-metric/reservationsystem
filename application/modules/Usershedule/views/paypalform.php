<form id="paypal_auto_form" name="paypal_auto_form" action="<?=base_url('Usershedule/submitPaymentform');?>" method="post">
	<input type="hidden" name="ItemPrice" value="<?= $amount?>" />	
	<input type="hidden" name="customData" value='<?= urlencode($json_custom_data) ?>' />
</form>