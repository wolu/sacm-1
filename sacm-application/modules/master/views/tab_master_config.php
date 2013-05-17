<script type="text/javascript">
$('#master').attr('selected', true);
</script>
<div class="easyui-tabs" plain="true" border="false" style="height:auto">  
<div title=" Master Configuration" data-options="iconCls:'icon-save icon-large'" style="padding:10px">
<?=$this->load->view('master_config_all')?>
</div>  
<div title=" Master Configuration Detil" data-options="iconCls:'icon-save icon-large'" style="padding:10px">
<?=$this->load->view('master_config_detil_all')?>   
</div>  
</div>  