<script type="text/javascript">
$('#master').attr('selected', true);
</script>
<table id="dg" class="easyui-datagrid" title="Code Change" style="width: 500px; height: auto;"
            url="<?=base_url('master/tipe_change/getJson')?>"
            iconCls ="icon-save icon-large"  
            rownumbers ="true" 
            pagination ="true"
            fitColumns ="true"
            toolbar ="#tb_cc"
            singleSELECT = "true">  
        <thead>  
            <tr>  
                <th field="CodeChange" width="10" sortable="true" align="center">Kode</th>  
                <th field="Desc" width="40">Deskripsi</th>  
            </tr>  
        </thead>  
    </table>  
    <div id="tb_cc" style="padding: 5px;">
    <a href="javascript:void(0)" onclick="javascript:addTipeChange()" class="btn btn-small btn-danger"><i class="icon-plus-sign icon-large"></i>&nbsp;Add</a>
    <a href="javascript:void(0)" onclick="javascript:editTipeChange()" class="btn btn-small btn-info"><i class="icon-edit icon-large"></i>&nbsp;Edit</a>
    <a href="javascript:void(0)" onclick="javascript:delTipeChange()" class="btn btn-small btn-success"><i class="icon-remove-sign icon-large"></i>&nbsp;Remove</a>
    </div>   
    <!---------------Dialog TipeChange--------------------------->
    <div id="dlg" class="easyui-dialog"
        modal ="true" 
        iconCls="icon-save" 
        closed="true"  
        buttons="#dlg-buttons-js" 
        resizable="true"
        style="width:250px;height:220px;">
    <div style="padding: 10px;">
        <form id="fm" method="post">           
         <label>Kode Tipe Change:</label>
         <input type="text" id="TipeChange" class="easyui-validatebox span3" data-options="required:true" name="CodeChange" />               
         <label>Description:</label>
         <input type="text" class="easyui-validatebox span3" data-options="required:true" name="Desc" />
       </form>  
        </div>  
    </div>
         <div id="dlg-buttons-js" >  
                <a href="javascript:void(0)"  class="btn btn-small btn-info" onclick="javascript:saveTipeChange()"><i class="icon-save icon-large"> Save</i></a>  
                <a href="javascript:void(0)" class="btn btn-small btn-info" onclick="javascript:$('#dlg').dialog('close')">Cancel</a>  
         </div>   
    <script type="text/javascript">
    var url;
    function addTipeChange() {
    	$('#dlg').dialog('open').dialog('setTitle', 'Add Tipe Change');
    	$('#fm').form('clear');
    	url ="<?php echo base_url('master/tipe_change/add'); ?>";
    }
    
    function editTipeChange() {
        //$('#TipeChange').attr('readonly','readonly');
    	var roww = $('#dg').datagrid('getSelected');
    	if (roww) {
    		$('#dlg').dialog('open').dialog('setTitle', 'Edit Tipe Change');
    		$('#fm').form('load', roww);
    		url = "<?php echo base_url('master/tipe_change/update'); ?>";
    	}
    }
        
    function saveTipeChange() {
    	$('#fm').form('submit',{
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
    					url: '<?= base_url('master/tipe_change/getJson') ?>'
    				}, 'reload');
    			} else {
    			 $.messager.alert('Error',result.msg, 'error');
    			}
    		}
    	});
    }
    function delTipeChange() {
    	var row = $('#dg').datagrid('getSelected');
    	if (row) {
    		$.messager.confirm('Confirm', 'Apakah Anda Yakin Akan Menghapus  ?', function(r) {
    			if (r){
    				$.post('<?= base_url('master/tipe_change/delete') ?>',{
    					id: row.CodeChange
    				}, function(result){
    					if (result.success) {
    					    $.messager.alert('Berhasil',result.success, 'info');
    						$('#dg').datagrid({ url: '<?= base_url('master/tipe_change/getJson') ?>'}, 'reload'); // reload the user data
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