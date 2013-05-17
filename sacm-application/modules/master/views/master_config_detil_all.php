<table id="dgDet" class="easyui-datagrid" title="Master Configuration Detil" style="width: auto; height: auto;"
            url="<?=base_url('master/master_config/getJsonDetil')?>"
            iconCls ="icon-save icon-large"  
            rownumbers ="true" 
            pagination ="true"
            fitColumns ="true"
            toolbar ="#tb_msconfig_det"
            singleSELECT = "true">  
        <thead>  
            <tr>  
                <th field="KodeKonfig" sortable="true" align="center" width="5">Kode</th>  
                <th field="NamaKonfigDetil" sortable="true" width="20">Nama</th> 
                <th field="Deskripsi" sortable="true" width="10">Description</th> 
            </tr>  
        </thead>  
</table>    
 
<div id="tb_msconfig_det" style="padding: 5px;">
    <a href="javascript:void(0)" onclick="javascript:addMasterConfigDet()"  class="btn btn-small btn-danger"><i class="icon-plus-sign icon-large"></i>&nbsp;Add</a>
    <a href="javascript:void(0)" onclick="javascript:editMasterConfigDet()" class="btn btn-small btn-info"><i class="icon-edit icon-large"></i>&nbsp;Edit</a>
    <a href="javascript:void(0)" onclick="javascript:delMasterConfigDet()"  class="btn btn-small btn-success"><i class="icon-remove-sign icon-large"></i>&nbsp;Remove</a>
</div>
    <!---------------Dialog MasterConfig--------------------------->
    <div id="dlgDet" class="easyui-dialog"
        modal ="true" 
        iconCls="icon-save" 
        closed="true"  
        buttons="#master_config_det" 
        resizable="true"
        style="width:300px;">
    <div class="well-large">
        <form id="fmDet" method="post"> 
        <table class="table">
         <tr>
         <td>
         <label>Kode Konfigurasi:</label>
         <input type="text" class="easyui-validatebox span2" data-options="required:true" name="KodeKonfig" />               
         </td>   
         </tr>
         <tr>
          <td> 
         <label>Nama Konfigurasi :</label>
         <input type="text" class="easyui-validatebox span3" data-options="required:true" name="NamaKonfigDetil" />
         </td>   
         </tr>
         <tr>
          <td>
         <label>Deskripsi:</label>
         <input type="text" class="easyui-validatebox span3" data-options="required:true" name="Deskripsi" />               
         </td>
         </tr>
      </table> 
       </form>  
        </div>  
    </div>
         <div id="master_config_det" >  
                <a href="javascript:void(0)"  class="btn btn-small btn-info" onclick="javascript:saveMasterConfigDet()">
                <i class="icon-save icon-large"> Save</i></a>  
                <a href="javascript:void(0)" class="btn btn-small btn-info" onclick="javascript:$('#dlgDet').dialog('close')">Cancel</a>  
         </div>   
   <!----------------------------------------------------------------------------------------------------------->
   
    <script type="text/javascript">
    var url;
    function addMasterConfigDet() {
    	$('#dlgDet').dialog('open').dialog('setTitle', 'Add Master Config Detil');
    	$('#fmDet').form('clear');
    	url ="<?php echo base_url('master/master_config/addDet'); ?>";
    }
    function editMasterConfigDet() {
    	var roww = $('#dgDet').datagrid('getSelected');
    	if (roww) {
    		$('#dlgDet').dialog('open').dialog('setTitle', 'Edit Master Config Detil');
    		$('#fmDet').form('load', roww);
    		url = "<?php echo base_url('master/master_config/updateDet'); ?>";
    	}
    }
    function saveMasterConfigDet() {
    	$('#fmDet').form('submit',{
    		url: url,
    		onSubmit: function(){
    			return $(this).form('validate');
    		},
    		success: function(result) {
    			var result = eval('(' + result + ')');
    			if (result.success) {
    				$('#dlgDet').dialog('close');
    				    $.messager.alert('Berhasil',result.success, 'info');
    				$('#dgDet').datagrid({
    					url: '<?= base_url('master/master_config/getJsonDet') ?>'
    				}, 'reload');
    			} else {
    			 $.messager.alert('Error',result.msg, 'error');
    			}
    		}
    	});
    }
    function delMasterConfigDet(){
    	var row = $('#dgDet').datagrid('getSelected');
    	if (row) {
    		$.messager.confirm('Confirm', 'Apakah Anda Yakin Akan Menghapus  ?', function(r) {
    			if (r){
    				$.post('<?= base_url('master/master_config/deleteDet') ?>',{
    					id: row.KodeKonfig
    				}, function(result){
    					if (result.success) {
    					    $.messager.alert('Berhasil',result.success, 'info');
    						$('#dgDet').datagrid({ url: '<?= base_url('master/master_config/getJsonDet') ?>'}, 'reload'); // reload the user data
    					}else{
    						$.messager.show({ // show error message
    							title: 'Error',
    							msg: result.msg
    						});
    					}
    				}, 'json');
    			}
    		});
    	}
    }
    </script>   