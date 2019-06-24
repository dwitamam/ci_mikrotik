<div class="page-header">
<h1>Form Tambah User Hotspot</h1>
<hr>
<p>
<form class="form-horizontal" name="hotspotForm" method="post" action="<?php echo $form_action; ?>">
	

	
	<div class="form-group">
		<label class="col-md-2 control-label"  for="server">Server</label>
		<div class="col-md-4">
			<input class="form-control" type="text" name="server" id="server" placeholder="server" value="<?php if (isset($default['server'])) { echo $default['server']; } ?>">
			<?php echo form_error('server', '<label class="control-label" for="server">', '</label>'); ?>	
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-md-2 control-label"  for="profile">profile</label>
		<div class="col-md-4">
			<input class="form-control" type="text" name="profile" id="profile" placeholder="profile" value="<?php if (isset($default['profile'])) { echo $default['profile']; } ?>">
			<?php echo form_error('profile', '<label class="control-label" for="profile">', '</label>'); ?>	
		</div>
	</div>
    
    <div class="form-group">
		<label class="col-md-2 control-label"  for="name">name</label>
		<div class="col-md-4">
			<input class="form-control" type="text" name="name" id="name" placeholder="name" value="<?php if (isset($default['name'])) { echo $default['name']; } ?>">
			<?php echo form_error('name', '<label class="control-label" for="name">', '</label>'); ?>	
		</div>
    </div>
	
	<div class="form-group">
		<label class="col-md-2 control-label"  for="password">password</label>
		<div class="col-md-4">
			<input class="form-control" type="password" name="password" id="password" placeholder="password" value="<?php if (isset($default['name'])) { echo $default['name']; } ?>">
			<?php echo form_error('password', '<label class="control-label" for="password">', '</label>'); ?>	
		</div>
    </div>

	



	
	<div class="form-group">
		<div class="col-md-offset-2 col-md-10">
			<button class="btn btn-primary" type="submit" name="btnsimpan">Simpan</button>
			<a class="btn btn-default" href="<?php echo base_url().'hotspotController'; ?>">Batal</a>
		</div>
	</div>

</form>
</p>
</div>