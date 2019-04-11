<div class="page-header">
<h1>Form Mangle</h1>
<hr>
<p>
<form class="form-horizontal" name="mangleForm" method="post" action="<?php echo $form_action; ?>">
	
	<div class="form-group">
		<label class="col-md-2 control-label"  for="srcAddress">SrcAddress</label>
		<div class="col-md-4">
			<input class="form-control" type="text" name="srcAddress" id="srcAddress" placeholder="IP Network Tujuan" value="<?php if (isset($default['srcAddress'])) { echo $default['srcAddress']; } ?>">
			<?php echo form_error('srcAddress', '<label class="control-label" for="srcAddress">', '</label>'); ?>	
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-md-2 control-label"  for="content">Content</label>
		<div class="col-md-4">
			<input class="form-control" type="text" name="content" id="content" placeholder="Situs Yang Di Blok" value="<?php if (isset($default['content'])) { echo $default['content']; } ?>">
			<?php echo form_error('content', '<label class="control-label" for="content">', '</label>'); ?>	
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-md-2 control-label"  for="addressList">AddressList</label>
		<div class="col-md-4">
			<input class="form-control" type="text" name="addressList" id="addressList" placeholder="Nama Blok" value="<?php if (isset($default['addressList'])) { echo $default['addressList']; } ?>">
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