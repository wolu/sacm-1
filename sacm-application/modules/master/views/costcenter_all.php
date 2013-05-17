<script type="text/javascript">
$('#master').attr('selected', true);
</script>
<div class="container">
<table id="dg" class="easyui-datagrid" title="Cost Center" style="width: 500px;"
            url="<?= base_url('master/costcenter/getJson') ?>"
            iconCls ="icon-save icon-large"  
            rownumbers ="true" 
            pagination ="true"
            fitColumns ="true"
            toolbar ="#tb_cc"
            singleSELECT = "true">  
        <thead>  
            <tr>  
                <th field="CC" width="5" sortable="true" align="center">Kode CC</th>  
                <th field="NamaCC" width="30">Cost Center Name</th>  
            </tr>  
        </thead>  
    </table>  
    <div id="tb_cc" style="padding: 5px;">
    <a href="javascript:void(0)" onclick="javascript:addCC()" class="btn btn-small btn-danger"><i class="icon-plus-sign icon-large"></i>&nbsp;Add</a>
    <a href="javascript:void(0)" onclick="javascript:editCC()" class="btn btn-small btn-info"><i class="icon-edit icon-large"></i>&nbsp;Edit</a>
    <a href="javascript:void(0)" onclick="javascript:delCC()" class="btn btn-small btn-success"><i class="icon-remove-sign icon-large"></i>&nbsp;Remove</a>
    </div>
    <!---------------Dialog CC--------------------------->
    <div id="dlg" class="easyui-dialog"
        modal ="true" 
        iconCls="icon-save" 
        closed="true"  
        buttons="#dlg-buttons-cc" 
        resizable="true"
        style="width:250px;height:220px;">
    <div style="padding: 10px;">
        <form id="fm" method="post">           
         <label>Code Cost Center:</label>
         <input type="text" id="CC" class="easyui-validatebox span3" data-options="required:true" name="CC" />    
         <label>Cost Center Name:</label>
         <input type="text" class="easyui-validatebox span3" data-options="required:true" name="NamaCC" />
       </form>  
        </div>  
    </div>
         <div id="dlg-buttons-cc" >  
                <a href="javascript:void(0)"  class="btn btn-small btn-info" onclick="javascript:saveCC()"><i class="icon-save icon-large"> Save</i></a>  
                <a href="javascript:void(0)" class="btn btn-small btn-info" onclick="javascript:$('#dlg').dialog('close')">Cancel</a>  
         </div>   
</div>
<script type="text/javascript">
    var url;
    function addCC() {
    	$('#dlg').dialog('open').dialog('setTitle', 'Add Cost Center');
    	$('#fm').form('clear');
    	url ="<?php echo base_url('master/costcenter/add'); ?>";
    }
    function editCC() {
        $('#CC').attr('readonly','readonly');
    	var roww = $('#dg').datagrid('getSelected');
    	if (roww) {
    		$('#dlg').dialog('open').dialog('setTitle', 'Edit Cost Center');
    		$('#fm').form('load', roww);
    		url = "<?php echo base_url('master/costcenter/update'); ?>";
    	}
    }
    function saveCC() {
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
    					url: '<?= base_url('master/costcenter/getJson') ?>'
    				}, 'reload');
    			} else {
    			 $.messager.alert('Error',result.msg, 'error');
    			/**	$.messager.show({
    					title: 'Error',
    					msg: result.msg
    				}); */
    			}
    		}
    	});
    }
    function delCC() {
    	var row = $('#dg').datagrid('getSelected');
    	if (row) {
    		$.messager.confirm('Confirm', 'Apakah Anda Yakin Akan Menghapus  ?', function(r) {
    			if (r){
    				$.post('<?= base_url('master/costcenter/delete') ?>',{
    					id: row.CC
    				}, function(result){
    					if (result.success) {
    					    $.messager.alert('Berhasil',result.success, 'info');
    						$('#dg').datagrid({ url: '<?= base_url('master/costcenter/getJson') ?>'}, 'reload'); // reload the user data
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