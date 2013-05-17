<!--div class="block">
<a href="#page-user" class="block-heading" data-toggle="collapse"><i class="icon-user"></i>&nbsp;User</a>
<div id="page-user" class="block-body collapse in">
<div class="alert alert-info">
<button type="button" class="close" data-dismiss="alert">×</button>
<small>Below is a list of the users.</small>
</div-->
<p>
<a href="<?php echo site_url('auth/create_user');?>" class="btn"><i class="icon-user"></i>&nbsp;Create a new user</a> 
<a href="<?php echo site_url('auth/create_group');?>" class="btn"><i class="icon-group"></i>&nbsp;Create a new group</a>
</p>
<div id="infoMessage">
<?php echo $message;?></div>
<table class="table table-striped table-hover table-bordered">
	<tr>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Email</th>
		<th>Groups</th>
		<th>Status</th>
		<th>Action</th>
	</tr>
	<?php foreach ($users as $user):?>
		<tr>
			<td><?php echo $user->first_name;?></td>
			<td><?php echo $user->last_name;?></td>
			<td><?php echo $user->email;?></td>
			<td>
				<?php foreach ($user->groups as $group):?>
					<?php echo anchor("auth/edit_group/".$group->id, '<i class=icon-user></i> '.$group->name,'class="btn btn-small "');?>&nbsp;
                <?php endforeach?>
			</td>
			<td style="text-align: center;"><?php echo ($user->active) ? anchor("auth/deactivate/".$user->id, '<i class=icon-user></i> Active','class="btn btn-small btn-success"') : anchor("auth/activate/". $user->id, 'Inactive','class="btn btn-small btn-danger"');?></td>
			<td style="text-align: center;"><?php echo anchor("auth/edit_user/".$user->id,'<i class=icon-edit></i> Edit','class="btn btn-small"') ;?></td>
		</tr>
	<?php endforeach;?>
</table>

<!--/div>
</div-->