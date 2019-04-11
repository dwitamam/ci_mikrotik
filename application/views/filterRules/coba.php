<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>hehe</title>
</head>
<body>
<form class="form-horizontal" name="frmtambah" method="post" action="<?php echo $form_action; ?>">
	<div class="form-group">
		<label class="col-md-2 control-label"  for="server">Server</label>
		<div class="col-md-4">
			<select name="server" id="server" class="form-control">							
                foreach($data['hehe'] as $heh){
                    <option value="$heh"></option>
                }

				<option value="all" <?php if (isset($default['server']) && ($default['server'] == 'all' || $default['server'] == '')) { echo "selected"; } ?>>All</option>
				<option value="hotspot1" <?php if (isset($default['server']) && $default['server'] == 'hotspot1') { echo "selected"; } ?>>hotspot1</option>
			</select>
			<?php echo form_error('server', '<label class="control-label" for="server">', '</label>'); ?>
		</div>
	</div>
	
	
</form>
    
</body>
</html>