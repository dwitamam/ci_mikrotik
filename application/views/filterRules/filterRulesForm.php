<div class="page-header">
<h1>Form Filter Rules</h1>
<hr>
<p>
<form class="form-horizontal" name="filterRulesForm" method="post" action="<?php echo $form_action; ?>">
	

	
	<div class="form-group">
		<label class="col-md-2 control-label"  for="DstAddressList">DstAddressList</label>
		<div class="col-md-4">
			<input class="form-control" type="text" name="dstAddressList" id="dstAddressList" placeholder="dst address list" value="<?php if (isset($default['dstAddressList'])) { echo $default['dstAddressList']; } ?>">
			<?php echo form_error('dstAddressList', '<label class="control-label" for="dstAddressList">', '</label>'); ?>	
		</div>
	</div>
	



	
	<div class="form-group">
		<div class="col-md-offset-2 col-md-10">
			<button class="btn btn-primary" type="submit" name="btnsimpan">Simpan</button>
			<a class="btn btn-default" href="<?php echo base_url().'hotspot'; ?>">Batal</a>
		</div>
	</div>

</form>
</p>
</div>