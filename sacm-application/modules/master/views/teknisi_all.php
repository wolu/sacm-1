<script type="text/javascript">
$('#master').attr('selected', true);
</script>
<table id="dg" class="easyui-datagrid" title="List Teknisi" style="width: 600px;"
            url="<?=base_url('master/teknisi/getJson')?>"
            iconCls ="icon-save icon-large"  
            rownumbers ="true" 
            pagination ="true"
            fitColumns ="true"
            toolbar ="#tb_teknisi"
            singleSELECT = "true">  
        <thead>  
            <tr>  
                <th field="NIK" width="20" sortable="true" align="center">NIK</th>  
                <th field="NamaTeknisi" width="60">Nama Teknisi</th>  
            </tr>  
        </thead>  
    </table>  
    <div id="tb_teknisi" style="padding: 5px;">
    <a href="javascript:void(0)" onclick="javascript:addTeknisi()" class="btn btn-small btn-danger"><i class="icon-plus-sign icon-large"></i>&nbsp;Add</a>
    <a href="javascript:void(0)" onclick="javascript:editTeknisi()" class="btn btn-small btn-info"><i class="icon-edit icon-large"></i>&nbsp;Edit</a>
    <a href="javascript:void(0)" onclick="javascript:delTeknisi()" class="btn btn-small btn-success"><i class="icon-remove-sign icon-large"></i>&nbsp;Remove</a>
    </div>   
    <!---------------Dialog Teknisi--------------------------->
    <div id="dlg" class="easyui-dialog"
        modal ="true" 
        iconCls="icon-save" 
        closed="true"  
        buttons="#dlg-buttons-js" 
        resizable="true"
        style="width:250px;height:220px;">
    <div style="padding: 10px;">
        <form id="fm" method="post">           
         <label>Kode Teknisi:</label>
         <input type="text" id="Teknisi" class="easyui-validatebox span3" data-options="required:true" name="NIK" />               
         <label>Teknisi:</label>
         <input type="text" class="easyui-validatebox span3" data-options="required:true" name="NamaTeknisi" />
       </form>  
        </div>  
    </div>
         <div id="dlg-buttons-js" >  
                <a href="javascript:void(0)"  class="btn btn-small btn-info" onclick="javascript:saveTeknisi()"><i class="icon-save icon-large"> Save</i></a>  
                <a href="javascript:void(0)" class="btn btn-small btn-info" onclick="javascript:$('#dlg').dialog('close')">Cancel</a>  
         </div>   
    <script type="text/javascript">
    var url;
    function addTeknisi() {
    	$('#dlg').dialog('open').dialog('setTitle', 'Add Teknisi');
    	$('#fm').form('clear');
    	url ="<?php echo base_url('master/teknisi/add'); ?>";
    }
    
    function editTeknisi() {
        //$('#Teknisi').attr('readonly','readonly');
    	var roww = $('#dg').datagrid('getSelected');
    	if (roww) {
    		$('#dlg').dialog('open').dialog('setTitle', 'Edit Teknisi');
    		$('#fm').form('load', roww);
    		url = "<?php echo base_url('master/teknisi/update'); ?>";
    	}
    }
        
    function saveTeknisi() {
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
    					url: '<?= base_url('master/teknisi/getJson') ?>'
    				}, 'reload');
    			} else {
    			 $.messager.alert('Error',result.msg, 'error');
    			}
    		}
    	});
    }
    function delTeknisi() {
    	var row = $('#dg').datagrid('getSelected');
    	if (row) {
    		$.messager.confirm('Confirm', 'Apakah Anda Yakin Akan Menghapus  ?', function(r) {
    			if (r){
    				$.post('<?= base_url('master/teknisi/delete') ?>',{
    					id: row.NIK
    				}, function(result){
    					if (result.success) {
    					    $.messager.alert('Berhasil',result.success, 'info');
    						$('#dg').datagrid({ url: '<?= base_url('master/teknisi/getJson') ?>'}, 'reload'); // reload the user data
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