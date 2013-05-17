<script type="text/javascript">
$('#master').attr('selected', true);
</script>
<table id="dg" class="easyui-datagrid" title="Jenis Service" style="width: 600px;"
            url="<?=base_url('master/service_jenis/getJson')?>"
            iconCls ="icon-save icon-large"  
            rownumbers ="true" 
            pagination ="true"
            fitColumns ="true"
            toolbar ="#tb_jservice"
            singleSELECT = "true">  
        <thead>  
            <tr>  
                <th field="KodeService" width="20" sortable="true" align="center">Kode</th>  
                <th field="Nama" width="60" sortable="true">Nama Service</th>  
                <th field="TargetSLAPerbaikan" width="15" sortable="true" align="center">Target SLA</th>  
            </tr>  
        </thead>  
    </table>  
    <div id="tb_jservice" style="padding: 5px;">
   <a href="javascript:void(0)" onclick="javascript:addServiceJenis()" class="btn btn-small btn-danger"><i class="icon-plus-sign icon-large"></i>&nbsp;Add</a>
    <a href="javascript:void(0)" onclick="javascript:editServiceJenis()" class="btn btn-small btn-info"><i class="icon-edit icon-large"></i>&nbsp;Edit</a>
    <a href="javascript:void(0)" onclick="javascript:delServiceJenis()" class="btn btn-small btn-success"><i class="icon-remove-sign icon-large"></i>&nbsp;Remove</a>
    </div> 
     <!---------------Dialog ServiceJenis--------------------------->
    <div id="dlg" class="easyui-dialog"
        modal ="true" 
        iconCls="icon-save" 
        closed="true"  
        buttons="#dlg-buttons-js" 
        resizable="true"
        style="width:250px;height:220px;">
    <div style="padding: 10px;">
        <form id="fm" method="post">           
         <label>Kode:</label>
         <input type="text" id="ServiceJenis" class="easyui-validatebox span3" data-options="required:true" name="KodeService" />               
         <label>Nama Jenis Service:</label>
         <input type="text" class="easyui-validatebox span3" data-options="required:true" name="Nama" />
       </form>  
        </div>  
    </div>
         <div id="dlg-buttons-js" >  
                <a href="javascript:void(0)"  class="btn btn-small btn-info" onclick="javascript:saveServiceJenis()"><i class="icon-save icon-large"> Save</i></a>  
                <a href="javascript:void(0)" class="btn btn-small btn-info" onclick="javascript:$('#dlg').dialog('close')">Cancel</a>  
         </div>   
    <script type="text/javascript">
    var url;
    function addServiceJenis() {
    	$('#dlg').dialog('open').dialog('setTitle', 'Add Jenis Service');
    	$('#fm').form('clear');
    	url ="<?php echo base_url('master/service_jenis/add'); ?>";
    }
    
    function editServiceJenis() {
        //$('#ServiceJenis').attr('readonly','readonly');
    	var roww = $('#dg').datagrid('getSelected');
    	if (roww) {
    		$('#dlg').dialog('open').dialog('setTitle', 'Edit Jenis Service');
    		$('#fm').form('load', roww);
    		url = "<?php echo base_url('master/service_jenis/update'); ?>";
    	}
    }
        
    function saveServiceJenis() {
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
    					url: '<?= base_url('master/service_jenis/getJson') ?>'
    				}, 'reload');
    			} else {
    			 $.messager.alert('Error',result.msg, 'error');
    			}
    		}
    	});
    }
    function delServiceJenis() {
    	var row = $('#dg').datagrid('getSelected');
    	if (row) {
    		$.messager.confirm('Confirm', 'Apakah Anda Yakin Akan Menghapus  ?', function(r) {
    			if (r){
    				$.post('<?= base_url('master/service_jenis/delete') ?>',{
    					id: row.KodeService
    				}, function(result){
    					if (result.success) {
    					    $.messager.alert('Berhasil',result.success, 'info');
    						$('#dg').datagrid({ url: '<?= base_url('master/service_jenis/getJson') ?>'}, 'reload'); // reload the user data
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