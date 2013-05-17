<div class="dialog">
<div class="block">
<a href="#page-user" class="block-heading" data-toggle="collapse"><i class="icon-edit"></i> Edit User</a>
<div id="page-user" class="block-body collapse in"><br />
<div class="alert alert-info">
<small>Please enter the users information below.</small>
</div>
<div id="infoMessage"><?php echo $message;?></div>
<?php echo form_open(uri_string());?>
      <p>First Name: <br />
            <?php echo form_input($first_name,'','class="span4"');?>
      </p>
      <p>Last Name: <br />
            <?php echo form_input($last_name,'','class="span4"');?>
      </p>
      <p>Company Name: <br />
            <?php echo form_input($company,'','class="span4"');?>
      </p>
      <p>Phone: <br />
            <?php echo form_input($phone1,'','class="span1"');?>-
            <?php echo form_input($phone2,'','class="span1"');?>-
            <?php echo form_input($phone3,'','class="span1"');?>
      </p>
      <p>Password: (if changing password)<br />
            <?php echo form_input($password,'','class="span4"');?>
      </p>
      <p>Confirm Password: (if changing password)<br />
            <?php echo form_input($password_confirm,'','class="span4"');?>
      </p>
    <h3>Member of groups</h3>
    <p class="pull-left">
	<?php foreach ($groups as $group):?>
	<label class="checkbox">
	<?php
		$gID=$group['id'];
		$checked = null;
		$item = null;
		foreach($currentGroups as $grp){
			if ($gID == $grp->id) {
				$checked= ' checked="checked"';
			break;
			}
		}
	?>
	<input type="checkbox" name="groups[]" value="<?php echo $group['id'];?>"<?php echo $checked;?>/>
	<?php echo $group['name'];?>
	</label>
	<?php endforeach?>
    </p>
    <?php echo form_hidden('id', $user->id);?>
    <?php echo form_hidden($csrf); ?>
    <p><?php echo form_submit('submit', 'Save User','class="btn btn-primary"');?></p>
<?php echo form_close();?>
</div>
</div>
</div>