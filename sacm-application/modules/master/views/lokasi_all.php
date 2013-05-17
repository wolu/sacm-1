<script type="text/javascript">
$('#master').attr('selected', true);
</script>
<table id="dg" class="easyui-datagrid" title="Lokasi" style="width: 600px;"
            url="<?=base_url('master/lokasi/getJson')?>"
            iconCls ="icon-save icon-large"  
            rownumbers ="true" 
            pagination ="true"
            fitColumns ="true"
            toolbar ="#tb_lokasi"
            singleSELECT = "true">  
        <thead>  
            <tr>  
                <th field="lokasi_kode" width="20" sortable="true" align="center">Kode</th>  
                <th field="lokasi_desc" width="80">Deskripsi</th> 
            </tr>  
        </thead>  
    </table>  
   <div id="tb_lokasi" style="padding: 5px;">
    <a href="javascript:void(0)" onclick="javascript:addLokasi()" class="btn btn-small btn-danger"><i class="icon-plus-sign icon-large"></i>&nbsp;Add</a>
    <a href="javascript:void(0)" onclick="javascript:editLokasi()" class="btn btn-small btn-info"><i class="icon-edit icon-large"></i>&nbsp;Edit</a>
    <a href="javascript:void(0)" onclick="javascript:delLokasi()" class="btn btn-small btn-success"><i class="icon-remove-sign icon-large"></i>&nbsp;Remove</a>
    </div>
    <!---------------Dialog Lokasi--------------------------->
    <div id="dlg" class="easyui-dialog"
        modal ="true" 
        iconCls="icon-save" 
        closed="true"  
        buttons="#dlg-buttons-lokasi" 
        resizable="true"
        style="width:250px;height:220px;">
    <div style="padding: 10px;">
        <form id="fm" method="post">           
         <label>Kode Lokasi:</label>
         <input type="text" id="Lokasi" class="easyui-validatebox span3" data-options="required:true" name="lokasi_kode" />               
         <label>Nama Lokasi:</label>
         <input type="text" class="easyui-validatebox span3" data-options="required:true" name="lokasi_desc" />
       </form>  
        </div>  
    </div>
         <div id="dlg-buttons-lokasi" >  
                <a href="javascript:void(0)"  class="btn btn-small btn-info" onclick="javascript:saveLokasi()"><i class="icon-save icon-large"> Save</i></a>  
                <a href="javascript:void(0)" class="btn btn-small btn-info" onclick="javascript:$('#dlg').dialog('close')">Cancel</a>  
         </div>   
    <script type="text/javascript">
    var url;
    function addLokasi() {
    	$('#dlg').dialog('open').dialog('setTitle', 'Add Lokasi');
    	$('#fm').form('clear');
    	url ="<?php echo base_url('master/lokasi/add'); ?>";
    }
    
    function editLokasi() {
        //$('#Lokasi').attr('readonly','readonly');
    	var roww = $('#dg').datagrid('getSelected');
    	if (roww) {
    		$('#dlg').dialog('open').dialog('setTitle', 'Edit Lokasi');
    		$('#fm').form('load', roww);
    		url = "<?php echo base_url('master/lokasi/update'); ?>";
    	}
    }
        
    function saveLokasi() {
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
    					url: '<?= base_url('master/lokasi/getJson') ?>'
    				}, 'reload');
    			} else {
    			 $.messager.alert('Error',result.msg, 'error');
    			}
    		}
    	});
    }
    function delLokasi() {
    	var row = $('#dg').datagrid('getSelected');
    	if (row) {
    		$.messager.confirm('Confirm', 'Apakah Anda Yakin Akan Menghapus  ?', function(r) {
    			if (r){
    				$.post('<?= base_url('master/lokasi/delete') ?>',{
    					id: row.Lokasi
    				}, function(result){
    					if (result.success) {
    					    $.messager.alert('Berhasil',result.success, 'info');
    						$('#dg').datagrid({ url: '<?= base_url('master/lokasi/getJson') ?>'}, 'reload'); // reload the user data
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