<div class="dialog">
    <div class="block">
            <p class="block-heading"><i class="icon-lock icon-large"></i> Sign In</p>
            <div class="block-body">
           <!-- <div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <small>Please login with your email/username and password below.</small>
            </div>-->
    		<div id="infoMessage"><?php echo $message;?></div>
            <?php 
            $attributs=array( 'class'=>'form-signin'); 
            echo form_open(base_url('auth/login'), $attributs); 
            ?>
            <div style="padding: 20px;">
            <table>
            <tr>
            <td colspan="2">
                <label for="identity">Email :</label>
				<?php echo form_input($identity, '', 'class="easyui-validatebox" required="true" validType="email" style="width: 100%; height: 25px; font-size: large;"');?> 
            </td>
            </tr>
            <tr>
            <td colspan="2">
                <label for="password">Password :</label>
			     <?php echo form_input($password, '', 'class="easyui-validatebox" required="true" style="width: 100%; height: 25px; font-size: large;"');?>
             </td>
             </tr>
             <tr>
             <td style="padding:5px;width: 60%" ><br />
             <?php echo $captcha; ?>
             </td>
                <td style="width: 40%;">
                <label for="captcha">Captcha :</label>
                <?php echo form_input("captcha", '', 'class="easyui-validatebox" required="true" style="width:auto; height: 25px; font-size: large;"');?>
                </td>
                </tr>
                <tr>
                <td colspan="2">
                Remember Me&nbsp;:
                <?php echo form_checkbox( 'remember', '1', FALSE, 'id="remember"');?>
                </td>
                </tr>
                </table>
                <br />
				
                <br />
                <button class="btn btn-primary pull-right" value="Login" type="submit"><i class="icon-ok-sign"></i> Login</button>
            </div>
            <div>&nbsp;</div>
            <br />
            <?php echo form_close();?>
            </div>
            </div>
            <a href="<?=base_url('auth/forgot_password')?>" style="float: left;">Forgot your password?</a>
            </div>