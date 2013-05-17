<div class="dialog">
<div class="block">
<p class="block-heading"><i class="icon-user-md"></i> Deactivate User</p>
<div class="block-body">
<div class="alert alert-info">
<small>Are you sure you want to deactivate the user '<?php echo $user->username; ?>'</small>
</div>
<?php echo form_open("auth/deactivate/".$user->id);?>
<p>
<label for="confirm">Yes&nbsp;:&nbsp;
<input type="radio" name="confirm" value="yes" checked="checked" /></label>
<label for="confirm">No&nbsp;&nbsp;:&nbsp;
<input type="radio" name="confirm" value="no" /></label>
</p>

<?php echo form_hidden($csrf); ?>
<?php echo form_hidden(array('id'=>$user->id)); ?>
<p><?php echo form_submit('submit', 'Submit','class="btn btn-primary"');?></p>
<?php echo form_close();?>
</div>
</div>
</div>