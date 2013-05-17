<script type="text/javascript">
$('#master').attr('selected', true);
</script>
<table id="dg" class="easyui-datagrid" title="Status Asset" style="width: 500px;"
            url="<?=base_url('master/status_asset/getJson')?>"
            iconCls ="icon-save icon-large"  
            rownumbers ="true" 
            pagination ="true"
            fitColumns ="true"
            toolbar ="#tb_jalokasi"
            singleSELECT = "true">  
        <thead>  
            <tr>  
                <th field="KodeAlokasi" width="10" sortable="true" align="center">Code</th>  
                <th field="NamaAlokasi" width="60" sortable="true" align="left">Name</th>  
            </tr>  
        </thead>  
    </table>  
    <div id="tb_jalokasi" style="padding: 5px;">
    <a href="javascript:void(0)" onclick="javascript:addJA()" class="btn btn-small btn-danger"><i class="icon-plus-sign"></i>&nbsp;Add</a>
    <a href="javascript:void(0)" onclick="javascript:editJA()" class="btn btn-small btn-info"><i class="icon-edit"></i>&nbsp;Edit</a>
    <a href="javascript:void(0)" onclick="javascript:delJA()" class="btn btn-small btn-success"><i class="icon-remove-sign"></i>&nbsp;Remove</a>
    </div>  
     <!---------------Dialog CC--------------------------->
    <div id="dlg" class="easyui-dialog"
        modal ="true" 
        iconCls="icon-save icon-large" 
        closed="true"  
        buttons="#dlg-buttons-ja" 
        resizable="true"
        style="width:250px;height:220px;">
    <div style="padding: 10px;">
        <form id="fm" method="post">           
         <label>Code Jenis Alokasi:</label>
         <input type="text" class="easyui-validatebox span3" data-options="required:true" name="KodeAlokasi" />               
         <label>Jenis Alokasi Name:</label>
         <input type="text" class="easyui-validatebox span3" data-options="required:true" name="NamaAlokasi" />
       </form>  
        </div>  
    </div>
         <div id="dlg-buttons-ja" >  
                <a href="javascript:void(0)" class="btn btn-small btn-info" onclick="javascript:saveJA()"><i class="icon-save icon-large"> Save</i></a>  
                <a href="javascript:void(0)" class="btn btn-small btn-info" onclick="javascript:$('#dlg').dialog('close')">Cancel</a>  
         </div>   
    <script type="text/javascript">
    var url;
    function addJA() {
    	$('#dlg').dialog('open').dialog('setTitle', 'Add Jenis Alokasi');
    	$('#fm').form('clear');
    	url ="<?php echo base_url('master/status_asset/add'); ?>";
    }
    
    function editJA() {
    	var roww = $('#dg').datagrid('getSelected');
    	if (roww) {
    		$('#dlg').dialog('open').dialog('setTitle', 'Edit Jenis Alokasi');
    		$('#fm').form('load', roww);
    		url = "<?php echo base_url('master/status_asset/update'); ?>";
    	}
    }
        
    function saveJA() {
    	$('#fm').form('submit', {
    		url: url,
    		onSubmit: function(){
    			return $(this).form('validate');
    		},
    		success: function(result) {
    			var result = eval('(' + result + ')');
    			if (result.success) {
    				$('#dlg').dialog('close');
    				    $.messager.alert('Berhasil',result.success, 'info');
    				$('#dg').datagrid({
    					url: '<?= base_url('master/status_asset/getJson') ?>'
    				}, 'reload');
    			} else {
    			 $.messager.alert('Error',result.msg, 'error');
    			}
    		}
    	});
    }
    function delJA() {
    	var row = $('#dg').datagrid('getSelected');
    	if (row) {
    		$.messager.confirm('Confirm', 'Apakah Anda Yakin Akan Menghapus  ?', function(r) {
    			if (r){
    				$.post('<?= base_url('master/status_asset/delete') ?>',{
    					id: row.KodeAlokasi
    				}, function(result){
    					if (result.success) {
    					    $.messager.alert('Berhasil',result.success, 'info');
    						$('#dg').datagrid({ url: '<?= base_url('master/status_asset/getJson') ?>'}, 'reload'); // reload the user data
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