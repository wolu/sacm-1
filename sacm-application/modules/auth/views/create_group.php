<div class="dialog">
<div class="block">
<p class="block-heading"><i class="icon-user-md"></i> Create Group</p>
<div class="block-body">
<div class="alert alert-info">
<small>Please enter the group information below.</small>
</div>
<div id="infoMessage"><?php echo $message;?></div>
<?php echo form_open("auth/create_group");?>
      <p>
            Group Name: <br />
            <?php echo form_input($group_name);?>
      </p>
      <p>
            Description: <br />
            <?php echo form_input($description);?>
      </p>
      <p><?php echo form_submit('submit', 'Create Group','class="btn btn-primary"');?></p>
<?php echo form_close();?>
</div>
</div>
</div>