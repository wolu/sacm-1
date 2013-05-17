<script type="text/javascript">
$('#master').attr('selected', true);
</script>
<table id="dg" class="easyui-datagrid" title="Status" style="width: 500px;"
            url="<?=base_url('master/status/getJson')?>"
            iconCls ="icon-save icon-large"  
            rownumbers ="true" 
            pagination ="true"
            fitColumns ="true"
            toolbar ="#tb_status"
            singleSELECT = "true">  
        <thead>  
            <tr>  
                <th field="KodeStatus" width="20" sortable="true" align="center">Kode Status</th>  
                <th field="Status" width="30">Status</th> 
                <th field="Kategori" width="50">Kategori</th> 
            </tr>  
        </thead>  
    </table>  
    <div id="tb_status" style="padding: 5px;">
    <a href="javascript:void(0)" onclick="javascript:addStatus()" class="btn btn-small btn-danger"><i class="icon-plus-sign icon-large"></i>&nbsp;Add</a>
    <a href="javascript:void(0)" onclick="javascript:editStatus()" class="btn btn-small btn-info"><i class="icon-edit icon-large"></i>&nbsp;Edit</a>
    <a href="javascript:void(0)" onclick="javascript:delStatus()" class="btn btn-small btn-success"><i class="icon-remove-sign icon-large"></i>&nbsp;Remove</a>
    </div>   
    <!---------------Dialog Status--------------------------->
    <div id="dlg" class="easyui-dialog"
        modal ="true" 
        iconCls="icon-save" 
        closed="true"  
        buttons="#dlg-buttons-js" 
        resizable="true"
        style="width:250px;height:270px;">
    <div style="padding: 10px;">
        <form id="fm" method="post">           
         <label>Kode Status:</label>
         <input type="text" id="Status" class="easyui-validatebox span3" data-options="required:true" name="KodeStatus" />               
         <label>Status:</label>
         <input type="text" class="easyui-validatebox span3" data-options="required:true" name="Status" />
         <label>Kategori:</label>
         <input type="text" class="easyui-validatebox span3" data-options="required:true" name="Kategori" />
       </form>  
        </div>  
    </div>
         <div id="dlg-buttons-js" >  
                <a href="javascript:void(0)"  class="btn btn-small btn-info" onclick="javascript:saveStatus()"><i class="icon-save icon-large"> Save</i></a>  
                <a href="javascript:void(0)"  class="btn btn-small btn-info" onclick="javascript:$('#dlg').dialog('close')">Cancel</a>  
         </div>   
    <script type="text/javascript">
    var url;
    function addStatus() {
    	$('#dlg').dialog('open').dialog('setTitle', 'Add Status');
    	$('#fm').form('clear');
    	url ="<?php echo base_url('master/status/add'); ?>";
    }
    function editStatus() {
        //$('#Status').attr('readonly','readonly');
    	var roww = $('#dg').datagrid('getSelected');
    	if (roww) {
    		$('#dlg').dialog('open').dialog('setTitle', 'Edit Status');
    		$('#fm').form('load', roww);
    		url = "<?php echo base_url('master/status/update'); ?>";
    	}
    }
    function saveStatus() {
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
    					url: '<?= base_url('master/status/getJson') ?>'
    				}, 'reload');
    			}else{
    			 $.messager.alert('Error',result.msg, 'error');
    			}
    		}
    	});
    }
    function delStatus() {
    	var row = $('#dg').datagrid('getSelected');
    	if (row) {
    		$.messager.confirm('Confirm', 'Apakah Anda Yakin Akan Menghapus  ?', function(r) {
    			if (r){
    				$.post('<?= base_url('master/status/delete') ?>',{
    					id: row.KodeStatus
    				}, function(result){
    					if (result.success) {
    					    $.messager.alert('Berhasil',result.success, 'info');
    						$('#dg').datagrid({ url: '<?= base_url('master/status/getJson') ?>'}, 'reload'); // reload the user data
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