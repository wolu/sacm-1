<table id="dg" class="easyui-datagrid" title="Master Configuration" style="width: auto; height: auto;"
            url="<?=base_url('master/master_config/getJson')?>"
            iconCls ="icon-save icon-large"  
            rownumbers ="true" 
            pagination ="true"
            fitColumns ="true"
            toolbar ="#tb_msconfig"
            singleSELECT = "true">  
        <thead>  
            <tr>  
                <th field="KodeKonfigurasi" sortable="true" align="center" width="5">Kode</th>  
                <th field="NamaKonfigurasi" sortable="true" width="20">Nama</th> 
                <th field="Deskripsi" sortable="true" width="10">Description</th> 
            </tr>  
        </thead>  
</table> 
   
<div id="tb_msconfig" style="padding: 5px;">
   <a href="javascript:void(0)" onclick="javascript:addMasterConfig()" class="btn btn-small btn-danger"><i class="icon-plus-sign icon-large"></i>&nbsp;Add</a>
    <a href="javascript:void(0)" onclick="javascript:editMasterConfig()" class="btn btn-small btn-info"><i class="icon-edit icon-large"></i>&nbsp;Edit</a>
    <a href="javascript:void(0)" onclick="javascript:delMasterConfig()" class="btn btn-small btn-success"><i class="icon-remove-sign icon-large"></i>&nbsp;Remove</a>
    </div>
    <!---------------Dialog MasterConfig--------------------------->
    <div id="dlg" class="easyui-dialog"
        modal ="true" 
        iconCls="icon-save" 
        closed="true"  
        buttons="#dlg-buttons-master_config" 
        resizable="true"
        style="width:300px;">
    <div class="well-large">
        <form id="fm" method="post"> 
        <table class="table">
         <tr>
         <td><label>Kode Konfigurasi:</label>
         <input type="text" id="MasterConfig" class="easyui-validatebox span2" data-options="required:true" name="KodeKonfigurasi" />               
         </td>   
         </tr>
         <tr>
         <td> <label>Nama Konfigurasi :</label>
         <input type="text" class="easyui-validatebox span3" data-options="required:true" name="NamaKonfigurasi" />
         </td>   
         </tr>
         <tr>
          <td>
         <label>Deskripsi:</label>
         <input type="text" id="MasterConfig" class="easyui-validatebox span3" data-options="required:true" name="Deskripsi" />               
         </td>
         </tr>
      </table> 
       </form>  
        </div>  
    </div>
         <div id="dlg-buttons-master_config" >  
                <a href="javascript:void(0)"  class="btn btn-small btn-info" onclick="javascript:saveMasterConfig()"><i class="icon-save icon-large"> Save</i></a>  
                <a href="javascript:void(0)" class="btn btn-small btn-info" onclick="javascript:$('#dlg').dialog('close')">Cancel</a>  
         </div>   
   <!----------------------------------------------------------------------------------------------------------->
<script type="text/javascript">
    var url;
    function addMasterConfig() {
    	$('#dlg').dialog('open').dialog('setTitle', 'Add Master Config');
    	$('#fm').form('clear');
    	url = "<?=base_url('master/master_config/add')?>";
    }
    function editMasterConfig() {
    	var roww = $('#dg').datagrid('getSelected');
    	if (roww) {
    		$('#dlg').dialog('open').dialog('setTitle', 'Edit Master Config');
    		$('#fm').form('load', roww);
    		url = "<?=base_url('master/master_config/update')?>";
    	}
    }
    function saveMasterConfig() {
    	$('#fm').form('submit', {
    		url: url,
    		onSubmit: function() {
    			return $(this).form('validate');
    		},
    		success: function(result) {
    			var result = eval('(' + result + ')');
    			if (result.success) {
    				$('#dlg').dialog('close');
    				$.messager.alert('Berhasil', result.success, 'info');
    				$('#dg').datagrid({
    					url: '<?= base_url('master/master_config/getJson')?>'
    				}, 'reload');
    			} else {
    				$.messager.alert('Error', result.msg, 'error');
    			}
    		}
    	});
    }
    function delMasterConfig() {
    	var row = $('#dg').datagrid('getSelected');
    	if (row) {
    		$.messager.confirm('Confirm', 'Apakah Anda Yakin Akan Menghapus  ?', function(r) {
    			if (r) {
    				$.post('<?= base_url('master/master_config/delete') ?>', {
    					id: row.KodeKonfigurasi
    				}, function(result) {
    					if (result.success) {
    						$.messager.alert('Berhasil', result.success, 'info');
    						$('#dg').datagrid({
    							url: '<?= base_url('master/master_config/getJson')?>'
    						}, 'reload'); // reload the user data
    					} else {
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