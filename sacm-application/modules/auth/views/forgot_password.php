<div class="dialog">
        <div class="block">
            <p class="block-heading"><i class="icon-lock"></i>&nbsp;Reset your password</p>
            <div class="block-body">
                
                <div class="alert alert-info">
                <!--button type="button" class="close" data-dismiss="alert">×</button-->
                <p>Please enter your <?php echo $identity_label; ?> so we can send you an email to reset your password.</p>
                </div>
                <div id="infoMessage"><?php echo $message;?></div>
                <?php echo form_open("auth/forgot_password");?>
                      <p>
                      	<?php echo $identity_label; ?>: <br />
                        <?php echo form_input($email,'','class="easyui-validatebox" required="true" validType="email" style="width: 90%; height: 25px; font-size: large;"');?>      	               
                      </p>
                      <p><br />
                      <button class="btn btn-info" type="submit">Sign in</button></p>
                <?php echo form_close();?>
            </div>
        </div>
        <a href="<?=base_url('auth/login')?>">Sign in to your account</a>
</div>