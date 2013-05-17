<script type="text/javascript">
$('#master').attr('selected', true);
</script>
<table id="dg" class="easyui-datagrid" title="Unit Kerja" style="width: auto; height: auto;"
            url="<?=base_url('master/unitkerja/getJson')?>"
            iconCls ="icon-save icon-large"  
            rownumbers ="true" 
            pagination ="true"
            fitColumns ="true"
            toolbar ="#tb_cc"
            singleSELECT = "true">  
        <thead>  
            <tr>  
                <th field="KodeUnitKerja" width="5" sortable="true" align="center">Kode Unit</th>  
                <th field="Nama" width="30">Nama Unit</th> 
                <th field="Abreviation" width="5" sortable="true" align="center">Abreviation</th>  
                <th field="CC" width="30">Cost Center</th>  
            </tr>  
        </thead>  
    </table>  
    <div id="tb_cc" style="padding: 5px;">
    <a href="javascript:void(0)" onclick="javascript:addUnitkerja()" class="btn btn-small btn-danger"><i class="icon-plus-sign icon-large"></i>&nbsp;Add</a>
    <a href="javascript:void(0)" onclick="javascript:editUnitkerja()" class="btn btn-small btn-info"><i class="icon-edit icon-large"></i>&nbsp;Edit</a>
    <a href="javascript:void(0)" onclick="javascript:delUnitkerja()" class="btn btn-small btn-success"><i class="icon-remove-sign icon-large"></i>&nbsp;Remove</a>
    </div>   
    <!---------------Dialog Unitkerja--------------------------->
    <div id="dlg" class="easyui-dialog"
        modal ="true" 
        iconCls="icon-save" 
        closed="true"  
        buttons="#dlg-buttons-js" 
        resizable="true"
        style="width:475px;height:220px;">
    <div style="padding: 10px;">
        <form id="fm" method="post"> 
        <table class="table-hover">
        <tr>
        <td><label>Kode:</label>
         <input type="text" id="Unitkerja" class="easyui-validatebox span3" data-options="required:true" name="KodeUnitKerja" /></td>
        <td><label>Nama Unit:</label>
         <input type="text" id="Unitkerja" class="easyui-validatebox span3" data-options="required:true" name="Nama" /></td>
        </tr>
        <tr>
        <td><label>Abreviation:</label>
         <input type="text" class="easyui-validatebox span3" data-options="required:true" name="Abreviation" /></td>
        <td><label>Coscenter:</label>
         <input type="text" id="CC" class="easyui-validatebox span3" onclick="javascript:openCC()" data-options="required:true" name="CC" /></td>
        </tr>
        </table>          
       </form>  
        </div>  
    </div>
         <div id="dlg-buttons-js" >  
                <a href="javascript:void(0)"  class="btn btn-small btn-info" onclick="javascript:saveUnitkerja()"><i class="icon-save icon-large"> Save</i></a>  
                <a href="javascript:void(0)" class="btn btn-small btn-info" onclick="javascript:$('#dlg').dialog('close')">Cancel</a>  
         </div>   
    <script type="text/javascript">
    var url;
    function addUnitkerja() {
    	$('#dlg').dialog('open').dialog('setTitle', 'Add Unitkerja');
    	$('#fm').form('clear');
    	url ="<?php echo base_url('master/unitkerja/add'); ?>";
    }
    function editUnitkerja() {
        //$('#Unitkerja').attr('readonly','readonly');
    	var roww = $('#dg').datagrid('getSelected');
    	if (roww) {
    		$('#dlg').dialog('open').dialog('setTitle', 'Edit Unitkerja');
    		$('#fm').form('load', roww);
    		url = "<?php echo base_url('master/unitkerja/update'); ?>";
    	}
    }
    function saveUnitkerja() {
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
    					url: '<?= base_url('master/unitkerja/getJson') ?>'
    				}, 'reload');
    			} else {
    			 $.messager.alert('Error',result.msg, 'error');
    			}
    		}
    	});
    }
    function delUnitkerja() {
    	var row = $('#dg').datagrid('getSelected');
    	if (row) {
    		$.messager.confirm('Confirm', 'Apakah Anda Yakin Akan Menghapus  ?', function(r) {
    			if (r){
    				$.post('<?= base_url('master/unitkerja/delete') ?>',{
    					id: row.NIK
    				}, function(result){
    					if (result.success) {
    					    $.messager.alert('Berhasil',result.success, 'info');
    						$('#dg').datagrid({ url: '<?= base_url('master/unitkerja/getJson') ?>'}, 'reload'); // reload the user data
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
    function openCC(){
            $('#CC').combogrid({  
            panelWidth:500,  
            url: '<?= base_url('master/costcenter/getJson') ?>',  
            idField:'CC',  
            textField:'NamaCC',  
            mode:'local',  
            fitColumns:true,  
            columns:[[  
                {field:'CC',title:'Kode', align:'left', width:10},  
                {field:'NamaCC',title:'Nama',align:'left',width:40}
            ]]  
        });  
        }
    </script>