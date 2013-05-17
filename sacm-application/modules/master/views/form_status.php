<script type="text/javascript">
$('#master').attr('selected', true);
</script>
<div class="dialog">
<div class="block">
<p class="block-heading"><i class="icon-user-md"></i> Form Status</p>
<div class="block-body">
<div class="alert alert-info">
</div>
<?php
if ($type_form == 'post') {
echo form_open('master/status/add');
} else {
echo form_open('master/status/update');
}
?>
<?php echo form_error('Status'); ?>
<?php echo form_error('Kategori'); ?>
<table border="0">
<tr>
<td>KodeStatus</td><td>&nbsp;:&nbsp;</td>
<td><input type="text" name="KodeStatus" value="<?php if(isset ($isi['KodeStatus'])){echo $isi['KodeStatus'];}?>" /></td>
</tr>
<tr>
<td>Status</td><td>&nbsp;:&nbsp;</td>
<td><input type="text" name="Status" value="<?php if(isset ($isi['Status'])){echo $isi['Status'];}?>" /></td>
</tr>
<tr>
<td>Kategori</td><td>&nbsp;:&nbsp;</td>
<td><input type="text" name="Kategori" value="<?php if(isset ($isi['Kategori'])){echo $isi['Kategori'];}?>" /></td>
</tr>
<tr>
<td></td><td>&nbsp;&nbsp;</td>
<td>
<?php if($type_form == 'post'){ ?>
<input type="submit" name="post" value="Submit" class="btn btn-danger" />
<?php } else { ?>
<?php if(isset ($isi['KodeStatus'])){
echo form_hidden('KodeStatus',$isi['KodeStatus']);
}?>
<input type="submit" name="update" value="update" />
<?php } ?>
</td>
</tr>
</table>
</form>
</div>
</div>
</div>