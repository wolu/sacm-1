<script type="text/javascript">
$('#master').attr('selected', true);
</script>
<table id="dg" class="easyui-datagrid" title="Karyawan" style="width: auto; height: auto;"
            url="<?=base_url('master/karyawan/getJson')?>"
            iconCls ="icon-save icon-large"  
            rownumbers ="true" 
            pagination ="true"
            fitColumns ="true"
            toolbar ="#tb_karyawan"
            singleSELECT = "true">  
        <thead>  
            <tr>    
                <th field="karyawan_nik" width="5%" align="center">NIK</th> 
                <th field="karyawan_nama" width="20%">NAMA</th>
                <th field="karyawan_unit" width="20%"sortable="true">UNIT</th> 
                <th field="cc" width="5%" sortable="true" align="center">C C</th>
                <th field="cc_nama" width="20%" sortable="true">COST CENTER NAME</th>
                <th field="karyawan_kota" width="10%" sortable="true" align="center">KOTA</th> 
            </tr>  
        </thead>  
    </table>
    <div id="tb_karyawan" style="padding: 5px;">
    <a href="javascript:void(0)" onclick="javascript:addKaryawan()" class="btn btn-small btn-danger"><i class="icon-plus-sign icon-large"></i>&nbsp;Add</a>
    <a href="javascript:void(0)" onclick="javascript:editKaryawan()" class="btn btn-small btn-info"><i class="icon-edit icon-large"></i>&nbsp;Edit</a>
    <a href="javascript:void(0)" onclick="javascript:delKaryawan()" class="btn btn-small btn-success"><i class="icon-remove-sign icon-large"></i>&nbsp;Remove</a>
    <span class="pull-right">
    <input class="easyui-searchbox" prompt="Please Input Value" searcher="doSearch" style="width:300px" />
    </span>
    </div> 
    <!---------------Dialog Karyawan--------------------------->
    <div id="dlg" class="easyui-dialog"
        modal ="true" 
        iconCls="icon-save" 
        closed="true"  
        buttons="#dlg-buttons-karyawan" 
        resizable="true"
        style="width:250px;height:420px;">
    <div style="padding: 10px;">
        <form id="fm" method="post">           
         <label>NIK:</label>
         <input type="text" class="easyui-validatebox span3" data-options="required:true" name="karyawan_nik" />
         <label>Nama:</label>
         <input type="text" class="easyui-validatebox span3" data-options="required:true" name="karyawan_nama" />
         <label>Unit:</label>
         <input type="text"  class="easyui-validatebox span3" data-options="required:true" name="karyawan_unit" />
         <label>Cost Center:</label>
         <input type="text" class="easyui-validatebox span3" data-options="required:true" name="cc" />
         <label>Nama Cost Center:</label>
         <input type="text" class="easyui-validatebox span3" data-options="required:true" name="cc_nama" />
         <label>Kota:</label>
         <input type="text" class="easyui-validatebox span3" data-options="required:true" name="karyawan_kota" />
       </form>  
    </div>  
    </div>
         <div id="dlg-buttons-karyawan" >  
                <a href="javascript:void(0)"  class="btn btn-small btn-info" onclick="javascript:saveKaryawan()"><i class="icon-save icon-large"> Save</i></a>  
                <a href="javascript:void(0)" class="btn btn-small btn-info" onclick="javascript:$('#dlg').dialog('close')">Cancel</a>  
         </div>   
    <script type="text/javascript">
    var url;
    function doSearch(value){
        $('#dg').datagrid('load',{    
        cari:value  
    });   	
      }
    function addKaryawan() {
    	$('#dlg').dialog('open').dialog('setTitle', 'Add Karyawan');
    	$('#fm').form('clear');
    	url ="<?php echo base_url('master/karyawan/add'); ?>";
    }
    function editKaryawan() {
    	var roww = $('#dg').datagrid('getSelected');
    	if (roww) {
    		$('#dlg').dialog('open').dialog('setTitle', 'Edit Karyawan');
    		$('#fm').form('load', roww);
    		url = "<?php echo base_url('master/karyawan/update'); ?>";
    	}
    }
    function saveKaryawan() {
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
    					url: '<?= base_url('master/karyawan/getJson') ?>'
    				}, 'reload');
    			} else {
    			 $.messager.alert('Error',result.msg, 'error');
    			}
    		}
    	});
    }
    function delKaryawan() {
    	var row = $('#dg').datagrid('getSelected');
    	if (row) {
    		$.messager.confirm('Confirm', 'Apakah Anda Yakin Akan Menghapus  ?', function(r) {
    			if (r){
    				$.post('<?= base_url('master/karyawan/delete') ?>',{
    					id: row.karyawan_nik
    				}, function(result){
    					if (result.success) {
    					    $.messager.alert('Berhasil',result.success, 'info');
    						$('#dg').datagrid({ url: '<?= base_url('master/karyawan/getJson') ?>'}, 'reload'); 
    					}else{
    						$.messager.show({
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