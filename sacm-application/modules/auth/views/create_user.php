<div class="dialog">
<div class="block">
<a href="#page-user" class="block-heading" data-toggle="collapse"><i class="icon-save"></i> Create User</a>
<div id="page-user" class="block-body collapse in"><br />
<div class="alert alert-info">
<small>Please enter the users information below.</small>
</div>
<div id="infoMessage"><?php echo $message;?></div>
<?php echo form_open("auth/create_user");?>
      <p>
            First Name: <br />
            <?php echo form_input($first_name,'','class="span4"');?>
      </p>
      <p>
            Last Name: <br />
            <?php echo form_input($last_name,'','class="span4"');?>
      </p>
      <p>
            Company Name: <br />
            <?php echo form_input($company,'','class="span4"');?>
      </p>
      <p>
            Email: <br />
            <?php echo form_input($email,'','class="span4"');?>
      </p>
      <p>
            Phone: <br />
            <?php echo form_input($phone1,'','class="span1"');?>-
            <?php echo form_input($phone2,'','class="span1"');?>-
            <?php echo form_input($phone3,'','class="span1"');?>
      </p>
      <p>
            Password: <br />
            <?php echo form_input($password,'','class="span3"');?>
      </p>
      <p>
            Confirm Password: <br />
            <?php echo form_input($password_confirm,'','class="span3"');?>
      </p>
      <p><?php echo form_submit('submit', 'Create User','class="btn btn-primary"');?></p>
<?php echo form_close();?>
</div>
</div>
</div>