<div class="dialog">
<div class="block">
<p class="block-heading"><i class="icon-user-md"></i> Edit Group</p>
<div class="block-body">
<div class="alert alert-info">
<small>Please enter the group information below.</small>
</div>
<div id="infoMessage"><?php echo $message;?></div>
<?php echo form_open(current_url());?>
      <p>
            Group Name: <br />
            <?php echo form_input($group_name,'','class="span4"');?>
      </p>
      <p>
            Group Description: <br />
            <?php echo form_input($group_description,'','class="span4"');?>
      </p>
      <p><?php echo form_submit('submit', 'Save Group','class="btn btn-primary"');?></p>
<?php echo form_close();?>
</div>
</div>
</div>